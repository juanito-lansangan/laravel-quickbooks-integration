<?php

namespace App\Http\Controllers\Quickbooks;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class OAuthController extends Controller
{
  private $client;

  public function __construct()
  {
    $this->client = new Client();
  }

  public function connect(Request $request)
  {
    
    
    $parameters = [
      'client_id' => config('services.quickbooks.client_id'),
      'response_type' => config('services.quickbooks.response_type'),
      'scope' => config('services.quickbooks.scope'),
      'redirect_uri' => config('services.quickbooks.redirect_uri'),
      'state' => Str::random(32),
    ];
    Log::debug($parameters);
    $authURL = config('services.quickbooks.auth_url');
    $authURL .= '?' . http_build_query($parameters, null, '&', PHP_QUERY_RFC1738);
    Log::debug($authURL);
    header("Location: ".$authURL);
    exit();
  }

  public function callback(Request $request)
  {
    $clientId = config('services.quickbooks.client_id');
    $clientSecret = config('services.quickbooks.client_secret');
    $tokenHeader = base64_encode($clientId . ':' . $clientSecret);
    $tokenHeader = 'Basic ' . $tokenHeader;

    try {
      $body = array(
        'grant_type' => config('services.quickbooks.grant_type'),
        'code' => $request->code,
        'redirect_uri' => config('services.quickbooks.redirect_uri')
      );
      
      $headers = [
        'Accept' => 'application/json',
        'Authorization' => $tokenHeader,
        'Content-Type' => 'application/x-www-form-urlencoded'
      ];

      Log::debug($headers);
      Log::debug($body);
      $accessTokenURL = config('services.quickbooks.token_url');
      $response = $this->client->request('POST', $accessTokenURL, [
        'headers' => $headers,
        'form_params' => $body
      ]);
      
      
      $response = $response->getBody()->getContents();
      // dd($response);
      // Log::debug($response);
      return $response;

    } catch (\Exception $e) {
      Log::error($e);
    }

    return 'Failed to get token';
  }
}