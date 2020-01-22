<?php

namespace App\Http\Controllers\Geoop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class GeoopAuthController extends Controller
{
  private $client;

  public function __construct()
  {
    $this->client = new Client();
  }

  public function connect(Request $request)
  {
    $parameters = [
      'client_id' => "06d7ad5bfd8a996b08504c00aab35ef50a49e7f3",
      'response_type' => "code",
      'scope' => "default",
      'state' => Str::random(32),
      'access_type' => 'offline',
      // 'approval_prompt' => 'auto',
      'redirect_uri' => "https://5f7cd664.ngrok.io/geoop-callback",
    ];
    Log::debug($parameters);
    $authURL = "https://login.geoop.com/oauth2/code";
    $authURL .= '?' . http_build_query($parameters, null, '&', PHP_QUERY_RFC1738);
    Log::debug($authURL);
    header("Location: ".$authURL);
    exit();
  }

  public function callback(Request $request)
  {
    $clientId = "06d7ad5bfd8a996b08504c00aab35ef50a49e7f3";
    $clientSecret = "112bfd21a6735d29b1c3cec1b1b30050317ccd47";

    try {
      $body = array(
        'client_id' => $clientId,
        'client_secret' => $clientSecret,
        // 'grant_type' => "authorization_code",
        'grant_type' => "authorization_code",
        'code' => $request->code,
        'redirect_uri' => "https://5f7cd664.ngrok.io/geoop-callback",
      );
      
      $headers = [
        'Content-Type' => 'application/x-www-form-urlencoded'
      ];

      Log::debug($headers);
      Log::debug($body);
      $accessTokenURL = "https://login.geoop.com/oauth2/token";
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

  public function refreshToken(Request $request)
  {
    $clientId = "06d7ad5bfd8a996b08504c00aab35ef50a49e7f3";
    $clientSecret = "112bfd21a6735d29b1c3cec1b1b30050317ccd47";

    try {
      $body = array(
        'client_id' => $clientId,
        'client_secret' => $clientSecret,
        // 'grant_type' => "authorization_code",
        'grant_type' => "refresh_token",
        'refresh_token' => $request->refresh_token,
      );
      
      $headers = [
        'Content-Type' => 'application/x-www-form-urlencoded'
      ];

      Log::debug($headers);
      Log::debug($body);
      $accessTokenURL = "https://login.geoop.com/oauth2/token";
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