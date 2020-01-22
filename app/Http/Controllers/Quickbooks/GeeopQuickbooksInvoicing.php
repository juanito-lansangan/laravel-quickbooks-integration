protected function generateInvoice($billBy, array $input = [])
    {
        $this->refreshConnection();
        $response = new ModuleResponse();
        $response->success = true;
        $invoiceRepository = new InvoiceRepository($this->getApiContext());
        $lineItemRepository = new LineItemRepository($this->getApiContext());
        //Get full lineItem object
        // $taxCode = isset($input[0]->job->customer->taxOverride->code) ? $input[0]->job->customer->taxOverride->code : null;
        $taxOverride = (isset($input[0]->job->customer->taxOverride) && 'US' !== $this->getQboCompany()->Country) ? $input[0]->job->customer->taxOverride : null;
        $input = $this->expandLineItems($input);
        /**
         * @var Customer[] $customers
         */
        $customers = [];
        /**
         * @var Customer[] $newCustomers
         */
        $newCustomers = [];
        /**
         * @var Invoice[] $invoices
         */
        $invoices = [];
        
        foreach ($input as $lineItem) {
            //Initial loop to check the Customer object; cannot be done in subsequent loop due to Customer prerequisite
            if (is_null($lineItem->job->customer->externalInfo->{InvoicingDefinitions::QUICKBOOKS})) {
                //Customer is not a QuickBooks Customer
                $newCustomers[$lineItem->job->customer->id] = $lineItem->job->customer;
            } else {
                $customers[$lineItem->job->customer->id] = $lineItem->job->customer;
            }
        }

        if (count($newCustomers)) {
            //Create new Customers in Quickbooks
            $newQboCustomers = $this->createQBOCustomers($newCustomers);

            foreach ($newQboCustomers as $qboCustomer) {
                //Merge with original Customers
                $customers[$qboCustomer->id] = InvoicingUtility::mergeObjects(
                    $newCustomers[$qboCustomer->id],
                    $qboCustomer
                );
            }
        }

        foreach ($input as $lineItem) {
            if (is_null($lineItem->billable->externalInfo->{InvoicingDefinitions::QUICKBOOKS})) {
                //Billable is not from QBO, remove reference
                unset($lineItem->billable);
            }
            if (Endpoints::JOB == $billBy) {
                //Index Invoice by Job
                if (!isset($invoices[$lineItem->job->id])) {
                    //Create new Invoice for each unique Job
                    $invoices[$lineItem->job->id] = new Invoice();
                    $invoices[$lineItem->job->id]->job = $lineItem->job;
                    $invoices[$lineItem->job->id]->customer = $customers[$lineItem->job->customer->id];
                    $invoices[$lineItem->job->id]->customer->taxOverride = $taxOverride;
                }
                //Add LineItem to Bill's LineItems
                $invoices[$lineItem->job->id]->lineItem[] = $lineItem;
                // error_log("===============lineItem===================");
                // error_log(var_export($lineItem, true));
            } else {
                //Index Invoice by Customer
                if (!isset($invoices[$lineItem->job->customer->id])) {
                    //Create new Invoice for each unique Customer
                    $invoices[$lineItem->job->customer->id] = new Invoice();
                    $invoices[$lineItem->job->customer->id]->job = $lineItem->job;
                    $invoices[$lineItem->job->customer->id]->customer = $customers[$lineItem->job->customer->id];
                }
                //Add LineItem to Invoice's LineItems
                $invoices[$lineItem->job->customer->id]->lineItem[] = $lineItem;
            }
        }

        foreach ($invoices as $id => $geoInvoice) {
            if (isset($geoInvoice->customer->taxOverride)) {
                //Use an overriding taxCode
                $geoInvoice->taxOverride = $geoInvoice->customer->taxOverride->code;
            }

            $qboInvoice = QuickbooksMap::invoiceToQBO($geoInvoice);

            //Add QBO Bills to bulk API
            $this->getQboConnection()->create(
                $id,
                $qboInvoice
            );
        }

        $qboResponse = $this->getQboConnection()->commit();
        $newInvoices = [];

        trigger_error("QBO invoice response : " . print_r($qboResponse, true), E_USER_WARNING);

        foreach ($qboResponse as $id => $qboInvoice) {
            if ($qboInvoice instanceof Fault) {
                $response->success = false;
                $error = new Errors();
                $error->message = 'QuickBooks Online Error: ' . reset($qboInvoice->Error)->Detail;
                $error->code = reset($qboInvoice->Error)->Code;
                $response->errors[] = $error;
            } else {
                $newInvoice = QuickbooksMap::invoiceFromQBO($qboInvoice);
                // error_log("==========invoice from qbo=============");
                // error_log(var_export($newInvoice, true));
                //Re-link the original references to the Invoice
                $newInvoice->companyData = $this->processCompanyData($this->getGeoCompany());
                // error_log("==========invoice companyData=============");
                // error_log(var_export($newInvoice->companyData, true));
                $newInvoice->invoiceData = $this->processInvoiceData($qboInvoice);
                // error_log("==========invoice invoiceData=============");
                // error_log(var_export($newInvoice->invoiceData, true));
                $newInvoice->lineItem = $invoices[$id]->lineItem;
                $newInvoice->issueDate = date('c');
                $newInvoice->acknowledgeDate = date('c');
                $newInvoice->job = $invoices[$id]->job;
                $newInvoice->customer = $invoices[$id]->customer;
                $newInvoice->version = '1.1.0';
                unset($newInvoice->id);
                $newInvoices[$id] = $newInvoice;
            }
        }

        $lineItems = [];
        $geoInvoices = $invoiceRepository->create($newInvoices);
        error_log("==========invoices data=============");
        error_log(var_export($geoInvoices, true));
        if (false !== $geoInvoices) {
            $response->data[Endpoints::INVOICE] = $geoInvoices;

            foreach ($geoInvoices as $id => $invoice) {
                foreach ($invoices[$id]->lineItem as &$lineItem) {
                    //Re-map LineItems to their original IDs
                    $lineItem->invoice = new Invoice();
                    $lineItem->invoice->id = $invoice->id;
                    $lineItems[] = $lineItem;
                }
            }

            $lineItemRepository->update($lineItems);

            //Import QBO Estimate PDFs
            foreach ($geoInvoices as $id => $invoice) {
                //Import Quickbooks PDF
                $this->importFileInvoice($invoice);
            }
        } else {
            $response->success = false;
            $response->errors = $invoiceRepository->getErrors();
        }

        return $response;
    }