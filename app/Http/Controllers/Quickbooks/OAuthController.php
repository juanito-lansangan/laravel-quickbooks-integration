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
  private $clientId;
  private $secret;
  private $redirectURL;
  private $authURL;
  private $scope;
  private $responseType;
  private $accessTokenURL;
  private $grantType;

  public function __construct()
  {
    $this->client = new Client();
    $this->clientId = env('QBO_CLIENT_ID', false);
    $this->secret = env('QBO_CLIENT_SECRET', false);
    $this->redirectURL = env('QBO_REDIRECT_URL', false);
    $this->authURL = env('QBO_AUTH_URL', false);
    $this->scope = env('QBO_SCOPE', false);
    $this->accessTokenURL = env('QBO_ACCESS_TOKEN_URL', false);
    $this->responseType = env('QBO_RESPONSE_TYPE', false);
    $this->grantType = env('QBO_AUTH_GRANT_TYPE', false);
  }

  public function connect(Request $request)
  {
    
    
    $parameters = [
      'client_id' => $this->clientId,
      'response_type' => $this->responseType,
      'scope' => $this->scope,
      'redirect_uri' => $this->redirectURL,
      'state' => Str::random(32),
    ];

    $this->authURL .= '?' . http_build_query($parameters, null, '&', PHP_QUERY_RFC1738);
    header("Location: ".$this->authURL);
    exit();
  }

  public function callback(Request $request)
  {
    // dump($request->code);
    // dump($request->state);
    // dump($request->realmId);

    $tokenHeader = base64_encode($this->clientId . ':' . $this->secret);
    $tokenHeader = 'Basic ' . $tokenHeader;

    try {
      $body = array(
        'grant_type' => $this->grantType,
        'code' => $request->code,
        'redirect_uri' => $this->redirectURL
      );

      $headers = [
        'Accept' => 'application/json',
        'Authorization' => $tokenHeader,
        'Content-Type' => 'application/x-www-form-urlencoded'
      ];

      Log::debug($headers);
      Log::debug($body);

      $response = $this->client->request('POST', $this->accessTokenURL, [
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