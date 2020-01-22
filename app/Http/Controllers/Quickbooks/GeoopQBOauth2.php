<?php

namespace Geoop\Lib\Invoicing\Service\Quickbooks;

use Geoop\Lib\Core\OAuth2\OAuth2ConfigurationInterface;
use Geoop\Lib\Invoicing\Authenticator\AuthenticatorInterface;
use Geoop\Lib\Invoicing\InvoicingConfiguration;
use Geoop\Model\Company;

class QuickbooksOAuth2 implements AuthenticatorInterface
{
    public static $STATE_REDIRECT = 'URL';
    public static $STATE_AUTHENTICATED = 'Authorized';

    /**
     * @param InvoicingConfiguration $config
     * @param Company                $company
     * @param string                 $integrationCode
     * @param array                  $input
     * @return array
     */
    public function connect(InvoicingConfiguration $config, Company &$company, $integrationCode, array $input = [])
    {
        //Prepare authentication variables for sending
        $accessURI = $config::getSetting(OAuth2ConfigurationInterface::AUTH_ACCESS_URI);
        $authURI = $config::getSetting(OAuth2ConfigurationInterface::AUTH_AUTH_URI);
        $redirectURI = $config::getAuthEndUrl() . '&dataSource=' . $integrationCode;
        error_log('==================oauth2 redirect uri================');
        error_log($redirectURI);

        $clientId = $config::getSetting(OAuth2ConfigurationInterface::AUTH_CLIENT_ID);
        $clientSecret = $config::getSetting(OAuth2ConfigurationInterface::AUTH_CLIENT_SECRET);
        $grantType = $config::getSetting(OAuth2ConfigurationInterface::AUTH_GRANT_TYPE);
        $responseType = $config::getSetting(OAuth2ConfigurationInterface::AUTH_RESPONSE_TYPE);
        $scope = $config::getSetting(OAuth2ConfigurationInterface::AUTH_SCOPE);

        //Retrieve OAuth token and secret
        /**
         * @var array $integrationSettings
         */
        $integrationSettings = $company->externalInfo->{$integrationCode};

        if (!isset($input['code'])) {
            //First step of OAuth2
            $getVars = [
                'client_id'     => $clientId,
                'redirect_uri'  => $redirectURI,
                'response_type' => $responseType,
                'scope'         => $scope,
                'state'         => substr(md5(time()), 0, 16)
            ];

            $uri = $accessURI . '?' . http_build_query($getVars);

            return [
                'message' => self::$STATE_REDIRECT,
                'success' => true,
                'payload' => $uri
            ];
        } else {
            $fields = http_build_query(
                [
                    'code'          => $input['code'],
                    'client_id'     => $clientId,
                    'client_secret' => $clientSecret,
                    'grant_type'    => $grantType,
                    'redirect_uri'  => $redirectURI,
                    'scope'         => $scope
                ]
            );
            $token = base64_encode($clientId . ':' . $clientSecret);
            $header = [
                'Content-Type: application/x-www-form-urlencoded',
                'Authorization: Basic ' . $token
            ];
            $ch = curl_init($authURI);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_VERBOSE, true);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($crl, CURLOPT_HTTPHEADER,$header);

            $response = curl_exec($ch);
            $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $body = substr($response, $headerSize);

            $response = json_decode($body);
            error_log('====================qbo oauth2 response=====================');
            error_log(var_export($response, true));
            $integrationSettings['token'] = $response->access_token;
            $integrationSettings['expire'] = date('c', (time() + $response->expires_in));
            $integrationSettings['refresh_token'] = $response->refresh_token;
            $company->externalInfo->{$integrationCode} = $integrationSettings;
        }

        return [
            'message' => self::$STATE_AUTHENTICATED,
            'success' => true,
            'payload' => null
        ];
    }

    public function refreshConnection(InvoicingConfiguration $config, Company &$company, $integrationCode)
    {
        //Prepare authentication variables for sending
        $clientId = $config::getSetting(OAuth2ConfigurationInterface::AUTH_CLIENT_ID);
        $clientSecret = $config::getSetting(OAuth2ConfigurationInterface::AUTH_CLIENT_SECRET);
        $refreshURL = $config::getSetting(OAuth2ConfigurationInterface::AUTH_RECONN_URL);
        $refreshToken = $company->externalInfo->myob['refresh_token'];
        $grantType = 'refresh_token';

        //Retrieve OAuth token and secret
        /**
         * @var array $integrationSettings
         */
        $integrationSettings = $company->externalInfo->{$integrationCode};

        $fields = http_build_query(
            [
                'client_id'     => $clientId,
                'client_secret' => $clientSecret,
                'refresh_token' => $refreshToken,
                'grant_type'    => $grantType
            ]
        );

        $ch = curl_init($refreshURL);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_HEADER, true);

        $response = curl_exec($ch);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $body = substr($response, $headerSize);

        $response = json_decode($body);

        $integrationSettings['token'] = $response->access_token;
        $integrationSettings['refresh_token'] = $response->refresh_token;
        $integrationSettings['expire'] = date('c', (time() + $response->expires_in));
        $company->externalInfo->{$integrationCode} = $integrationSettings;

        return [
            'message' => self::$STATE_AUTHENTICATED,
            'success' => true,
            'payload' => null
        ];
    }

    /**
     * @param InvoicingConfiguration $config
     * @param Company                $company
     * @param string                 $integrationCode
     * @return bool
     */
    public function getConnection(InvoicingConfiguration $config, Company &$company, $integrationCode)
    {
        return true;
    }
}
