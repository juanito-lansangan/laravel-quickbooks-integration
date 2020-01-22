<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/qbo-auth', 'Quickbooks\OAuthController@connect');
Route::get('/qbo-callback', 'Quickbooks\OAuthController@callback');


Route::get('/geoop-auth', 'Geoop\GeoopAuthController@connect');
Route::get('/geoop-callback', 'Geoop\GeoopAuthController@callback');


Route::get('/regextest', function () {
    $body = '{"BatchItemRequest":[{"bId":29096945,"operation":"create","Invoice":{"Deposit":null,"AllowIPNPayment":null,"AllowOnlinePayment":null,"AllowOnlineCreditCardPayment":null,"AllowOnlineACHPayment":null,"EInvoiceStatus":null,"ECloudStatusTimeStamp":null,"InvoiceEx":null,"AutoDocNumber":null,"CustomerRef":{"value":"64","name":null,"type":null},"CustomerMemo":null,"BillAddr":null,"ShipAddr":null,"RemitToRef":null,"ClassRef":null,"SalesTermRef":null,"DueDate":null,"SalesRepRef":null,"PONumber":null,"FOB":null,"ShipMethodRef":null,"ShipDate":null,"TrackingNum":null,"GlobalTaxCalculation":null,"TotalAmt":null,"HomeTotalAmt":null,"ApplyTaxAfterDiscount":null,"TemplateRef":null,"PrintStatus":null,"EmailStatus":null,"BillEmail":null,"ARAccountRef":null,"Balance":null,"FinanceCharge":null,"PaymentMethodRef":null,"PaymentRefNum":null,"PaymentType":null,"CheckPayment":null,"CreditCardPayment":null,"DepositToAccountRef":null,"DeliveryInfo":null,"DocNumber":null,"TxnDate":null,"DepartmentRef":null,"CurrencyRef":null,"ExchangeRate":null,"PrivateNote":null,"TxnStatus":null,"LinkedTxn":null,"Line":[{"Id":null,"LineNum":null,"Description":"Guest Book","Amount":25,"LinkedTxn":null,"DetailType":"SalesItemLineDetail","PaymentLineDetail":null,"DiscountLineDetail":null,"TaxLineDetail":null,"SalesItemLineDetail":{"ItemRef":{"value":"17","name":null,"type":null},"TaxCodeRef":{"value":"13"},"Qty":"1.0000","UnitPrice":"25.0000"},"DescriptionLineDetail":null,"ItemBasedExpenseLineDetail":null,"AccountBasedExpenseLineDetail":null,"DepositLineDetail":null,"PurchaseOrderItemLineDetail":null,"SalesOrderItemLineDetail":null,"ItemReceiptLineDetail":null,"JournalEntryLineDetail":null,"GroupLineDetail":null,"SubTotalLineDetail":null,"CustomField":null,"LineEx":null}],"TxnTaxDetail":{"DefaultTaxCodeRef":null,"TxnTaxCodeRef":{"name":null,"type":null,"value":null},"TotalTax":null,"TaxLine":null},"TxnSource":null,"Id":null,"SyncToken":null,"MetaData":null,"CustomField":null,"AttachableRef":null,"domain":null,"status":null,"sparse":true}}]}';
    $body = preg_replace('/,\s*"[^"]+":null|"[^"]+":null,?/', '', $body);
    return $body;
});