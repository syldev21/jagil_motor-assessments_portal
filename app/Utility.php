<?php

namespace App;

use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Utility extends Model
{
    private $grant_type;
    private $client_id;
    private $client_secret;
    private $username;
    private $password;
    private $api_endpoint;
    private $token;
    /**
     * load default configs from environment
     */
    public function __construct()
    {
        //$this->grant_type = env('GRANT_TYPE');
        //$this->client_id  = env('CLIENT_ID');
        //$this->client_secret = env('CLIENT_SECRET');
        //$this->api_username = env('API_USERNAME');
        //$this->api_password = env('API_PASSWORD');
        $this->grant_type = "password";
        $this->client_id  = "2";
        $this->client_secret = "QBZTwdE3y7Pvqwf5wPp5FQeCp9MZmiMWhG6QXlJO";
        $this->api_username = "homeinsurance-report@jubileekenya.com";
        $this->api_password = "GX=N2`B9[Vm&f27*";
        $this->api_endpoint = "https://digitalapps.jubileekenya.com";
     //   $this->api_endpoint = "http://127.0.0.1:8000";
    }

    public function createToken()
    {
        //TODO:
        //call api,
        //add exception if api fails
        //return exception message
        //save token if came back successfully
        //return only access token

        try {
            $client = new Client();
            $response = $client->request('POST', $this->api_endpoint."/oauth/token", [
                'form_params' => [
                    'grant_type' => $this->grant_type,
                    'client_id' => $this->client_id,
                    'client_secret' => $this->client_secret,
                    'username' => $this->api_username,
                    'password' => $this->api_password,
                ],
                [
                    'http_errors' => false
                ],
                [
                    'Accept-Encoding' => 'gzip,deflate',
                    'Content-Type' => 'application/json'
                ],
                'verify' => false,
            ]);
            $jsontokendata =  $response->getBody()->getContents();
            $tokendata = (Object)json_decode($jsontokendata);
            if (isset($tokendata->token_type)) {
                $token = new Token();
                $token->token_type = $tokendata->token_type;
                $token->expires_in = $tokendata->expires_in;
                $token->access_token = $tokendata->access_token;
                $token->refresh_token = $tokendata->refresh_token;
                $calculated_token_expiry_time = Carbon::now()->addSeconds(3600)->toDateTimeString();
                $token->token_expiry_time = $calculated_token_expiry_time;


                // $tokendata = [$tokendata->token_type, $tokendata->expires_in, $token->access_token, $token->refresh_token, $calculated_token_expiry_time];
                $token->save();
                $this->token = $token->access_token;
                session(['access_token'=> $token->access_token]);
                return $token->access_token;
            }else{
                return "api error";
            }

        } catch (ClientException $e) {
            echo (Psr7\str($e->getRequest()));
            echo (Psr7\str($e->getResponse()));
            // $lastdb = $this-Model::latest()->first();
            // echo $lastdb;
            $client = new Client();
            $response = $client->request('POST', $this->api_endpoint."/oauth/token", [
                'form_params' => [
                    'grant_type' => $this->grant_type,
                    'client_id' => $this->client_id,
                    'client_secret' => $this->client_secret,
                    'username' => $this->api_username,
                    'password' => $this->api_password,
                ],
                [
                    'http_errors' => false
                ],
                [
                    'Accept-Encoding' => 'gzip,deflate',
                    'Content-Type' => 'application/json'
                ],
                'verify' => false,
            ]);
            $jsontokendata =  $response->getBody()->getContents();
            $tokendata = (Object)json_decode($jsontokendata);
            if (isset($tokendata->token_type)) {
                $token = new Token();
                $token->token_type = $tokendata->token_type;
                $token->expires_in = $tokendata->expires_in;
                $token->access_token = $tokendata->access_token;
                $token->refresh_token = $tokendata->refresh_token;
                $calculated_token_expiry_time = Carbon::now()->addSeconds(3600)->toDateTimeString();
                $token->token_expiry_time = $calculated_token_expiry_time;


                // $tokendata = [$tokendata->token_type, $tokendata->expires_in, $token->access_token, $token->refresh_token, $calculated_token_expiry_time];
                $token->save();
                $this->token = $token->access_token;
                session(['access_token'=> $token->access_token]);
                return $token->access_token;
            }else{
                return "api error";
            }

        }
        // return $this->token;
    }

    function refreshToken($oldaccesstoken)
    {
        // TODO:
        // get new token from davids api
        // store it to database and delete the old one
        // if refresh refuses call createToken()
        //
        return $new_token;
    }
    public function getToken()
    {
        if (null !== session('access_token')) {
            // $access_token = session('access_token');
            $oldaccesstoken = session('access_token');
            $tokenDetails = Token::where('access_token', $oldaccesstoken)->first();
            if($tokenDetails == null){
                $access_token = $this->createToken();
                return $access_token;
            }else{
                $tokenTime = Carbon::createFromFormat('Y-m-d H:i:s',$tokenDetails->token_expiry_time);
                $timeNow=Carbon::now();
                // echo "TokenTime: ".$tokenTime." <br> Timenow".$timeNow;
                // dd ($tokenTime->greaterThan($timeNow));
                if($tokenTime->greaterThan($timeNow)){
                    return $oldaccesstoken;
                }else{
                    $access_token = $this->createToken();
                    return $access_token;
                }
            }

            // return $access_token;
        }else{
            $access_token = $this->createToken();
            return $access_token;
        }

    }
    public function getData($data, $url, $form_method)
    {
        // dd($data);
        try {
            $client = new Client();
            $access_token = $this->createToken();
            Log::info('--url endpoint -----'.$this->api_endpoint);
            $response = $client->request($form_method, $this->api_endpoint.$url, [
                'form_params' => $data,
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer '.$access_token,
                    "Content-Type"=> "application/x-www-form-urlencoded"
                ],
                'verify'=> false,
            ]);
            // dd($response);
            return $response;
        } catch (ClientException $e) {
            //Log::info('--url endpoint -----'.$this->api_endpoint);
            $response = $e->getResponse();
            return $response;
        } catch ( Exception $e ) {
            //Log::info('--url endpoint -----'.$this->api_endpoint);
            $response = $e->getResponse();
            return $response;
        }

    }

}
