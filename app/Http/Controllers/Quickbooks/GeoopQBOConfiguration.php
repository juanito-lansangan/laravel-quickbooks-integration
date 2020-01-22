<?php


namespace Geoop\Lib\Invoicing\Service\Quickbooks;

use Geoop\Lib\Core\APIConfigurationInterface;
use Geoop\Lib\Core\OAuth2\OAuth2ConfigurationInterface;
use Geoop\Lib\Invoicing\Core\InvoicingDefinitions;
use Geoop\Lib\Invoicing\InvoicingConfiguration;

class QuickbooksConfiguration extends InvoicingConfiguration implements APIConfigurationInterface, OAuth2ConfigurationInterface
{
    public static function overrideConfiguration()
    {
        $config = [];

        if (defined("AWS_GEOOP_QBO_CONSUMER_KEY") && defined("AWS_GEOOP_QBO_CONSUMER_SECRET")) {
            
            $config = [
                'api' => [
                    'uri'         => 'https://sandbox-quickbooks.api.intuit.com',
                    'version'     => 'v3',
                    'contentType' => 'application/json',
                    'maxRecords'  => 400
                ],
                'auth'        => [
                    'accessURI'    => 'https://appcenter.intuit.com/connect/oauth2',
                    'authURI'      => 'https://oauth.platform.intuit.com/oauth2/v1/tokens/bearer',
                    'grantType'    => 'authorization_code',
                    'reconnURL'    => 'https://oauth.platform.intuit.com/oauth2/v1/tokens/bearer',
                    'reconnTime'   => '-10 minutes',
                    'responseType' => 'code',
                    "clientId" => AWS_GEOOP_QBO_CONSUMER_KEY,
                    "clientSecret" => AWS_GEOOP_QBO_CONSUMER_SECRET,
                    'scope'        => 'com.intuit.quickbooks.accounting'
                ],
                'integration' => [
                    'code' => InvoicingDefinitions::QUICKBOOKS
                ]
            ];
        }
        return $config;
    }

    //Refer to https://gist.github.com/keranm/4965549 for detailed OAuth help
    public static $configArrayDev = [
        'api'         => [
            'uri'         => 'https://sandbox-quickbooks.api.intuit.com',
            'version'     => 'v2',
            'contentType' => 'application/json',
            'maxRecords'  => 400
        ],
        'auth'        => [
            'accessURI'    => 'https://appcenter.intuit.com/connect/oauth2',
            'authURI'      => 'https://oauth.platform.intuit.com/oauth2/v1/tokens/bearer',
            'grantType'    => 'authorization_code',
            'reconnURL'    => 'https://oauth.platform.intuit.com/oauth2/v1/tokens/bearer',
            'reconnTime'   => '-10 minutes',
            'responseType' => 'code',
            'clientId'     => 'AB5r6u0wdWO9XNgmdlShx2MGMk71XNLvNeFpcdSQxEh5l4kavq',
            'clientSecret' => 'pW8bVXrCGQJ8blsNYyJG58XHmVZajTfuMj2WN9Fl',
            'scope'        => 'com.intuit.quickbooks.accounting'
        ],
        'integration' => [
            'code' => InvoicingDefinitions::QUICKBOOKS
        ]
    ];

    public static $configArrayTest = [
        'api'         => [
            'uri'         => 'https://sandbox-quickbooks.api.intuit.com',
            'version'     => 'v2',
            'contentType' => 'application/json',
            'maxRecords'  => 400
        ],
        'auth'        => [
            'accessURI'    => 'https://appcenter.intuit.com/connect/oauth2',
            'authURI'      => 'https://oauth.platform.intuit.com/oauth2/v1/tokens/bearer',
            'grantType'    => 'authorization_code',
            'reconnURL'    => 'https://oauth.platform.intuit.com/oauth2/v1/tokens/bearer',
            'reconnTime'   => '-10 minutes',
            'responseType' => 'code',
            'clientId'     => 'AB5r6u0wdWO9XNgmdlShx2MGMk71XNLvNeFpcdSQxEh5l4kavq',
            'clientSecret' => 'pW8bVXrCGQJ8blsNYyJG58XHmVZajTfuMj2WN9Fl',
            'scope'        => 'com.intuit.quickbooks.accounting'
        ],
        'integration' => [
            'code' => InvoicingDefinitions::QUICKBOOKS
        ]
    ];

    public static $configArrayHotfix = [
        'api'         => [
            'uri'         => 'https://sandbox-quickbooks.api.intuit.com',
            'version'     => 'v2',
            'contentType' => 'application/json',
            'maxRecords'  => 400
        ],
        'auth'        => [
            'accessURI'    => 'https://appcenter.intuit.com/connect/oauth2',
            'authURI'      => 'https://oauth.platform.intuit.com/oauth2/v1/tokens/bearer',
            'grantType'    => 'authorization_code',
            'reconnURL'    => 'https://oauth.platform.intuit.com/oauth2/v1/tokens/bearer',
            'reconnTime'   => '-10 minutes',
            'responseType' => 'code',
            'clientId'     => 'AB5r6u0wdWO9XNgmdlShx2MGMk71XNLvNeFpcdSQxEh5l4kavq',
            'clientSecret' => 'pW8bVXrCGQJ8blsNYyJG58XHmVZajTfuMj2WN9Fl',
            'scope'        => 'com.intuit.quickbooks.accounting'
        ],
        'integration' => [
            'code' => InvoicingDefinitions::QUICKBOOKS
        ]
    ];

    public static $configArrayUAT = [
        'api'         => [
            'uri'         => 'https://sandbox-quickbooks.api.intuit.com',
            'version'     => 'v2',
            'contentType' => 'application/json',
            'maxRecords'  => 400
        ],
        'auth'        => [
            'accessURI'    => 'https://appcenter.intuit.com/connect/oauth2',
            'authURI'      => 'https://oauth.platform.intuit.com/oauth2/v1/tokens/bearer',
            'grantType'    => 'authorization_code',
            'reconnURL'    => 'https://oauth.platform.intuit.com/oauth2/v1/tokens/bearer',
            'reconnTime'   => '-10 minutes',
            'responseType' => 'code',
            'clientId'     => 'AB5r6u0wdWO9XNgmdlShx2MGMk71XNLvNeFpcdSQxEh5l4kavq',
            'clientSecret' => 'pW8bVXrCGQJ8blsNYyJG58XHmVZajTfuMj2WN9Fl',
            'scope'        => 'com.intuit.quickbooks.accounting'
        ],
        'integration' => [
            'code' => InvoicingDefinitions::QUICKBOOKS
        ]
    ];

    public static $configArrayLive = [
        'api'         => [
            'uri'         => 'https://sandbox-quickbooks.api.intuit.com',
            'version'     => 'v2',
            'contentType' => 'application/json',
            'maxRecords'  => 400
        ],
        'auth'        => [
            'accessURI'    => 'https://appcenter.intuit.com/connect/oauth2',
            'authURI'      => 'https://oauth.platform.intuit.com/oauth2/v1/tokens/bearer',
            'grantType'    => 'authorization_code',
            'reconnURL'    => 'https://oauth.platform.intuit.com/oauth2/v1/tokens/bearer',
            'reconnTime'   => '-10 minutes',
            'responseType' => 'code',
            'clientId'     => 'AB5r6u0wdWO9XNgmdlShx2MGMk71XNLvNeFpcdSQxEh5l4kavq',
            'clientSecret' => 'pW8bVXrCGQJ8blsNYyJG58XHmVZajTfuMj2WN9Fl',
            'scope'        => 'com.intuit.quickbooks.accounting'
        ],
        'integration' => [
            'code' => InvoicingDefinitions::QUICKBOOKS
        ]
    ];
}
