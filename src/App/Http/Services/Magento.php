<?php

namespace Dinesh\Magento\App\Http\Services;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Dinesh\Magento\App\Models\Cursors;
use Dinesh\Magento\App\Models\Requests;
use Dinesh\Magento\App\Models\AccessTokens;
use Dinesh\Magento\App\Models\RefreshTokens;
use Dinesh\Magento\App\Models\Websites;

class Magento
{

    private $endPoint;

    // Private constructor to prevent direct object creation
    function __construct()
    {

    }

    public function setEndPoint($siteID){
        $endPoint = Websites::where('siteID', $siteID)->pluck('url')->first();
        $this->endPoint = $endPoint;
    }

    // Generate access token using refresh token
    public function getAccessToken( $siteID )
    {

        if(!$siteID){
            dd('Unable to get access token without siteID.');
        }

        $accessRow = AccessTokens::where('siteID', $siteID )->orderBy('siteID', 'desc')->first();

        if (isset($accessRow->access_token)) {
            return $accessRow->access_token;
        }

        $siteRow = Websites::where( 'siteID', $siteID )->first();
        if(!$siteRow){
            dd('Please configure magento website first before proceeding.');
        }
        
        $this->setEndPoint($siteID);

        $method = 'POST';
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
        $data = [
            'username' => $siteRow->user,
            'password' => $siteRow->password,
        ];

        $endPoint = "/rest/V1/integration/admin/token"; // Updated endpoint if necessary
        
        $response = $this->request($method, $data, $headers, $endPoint, 'body');

        if (isset($response['message'])) {
            dd($response['message']);
        }

        // Example data to insert
        $dbVal = [
            'siteID' => $siteID,
            'access_token' => $response,
        ];

        // Create a new AccessTokens instance and save it
        AccessTokens::create($dbVal);

        return $response;

    }

    public function saveRequest($method, $requestUrl, $statusCode, $headers)
    {

        $pattern = '/\/([^\/]+)\.json/';
        preg_match($pattern, $requestUrl, $matches);

        // Check if a match is found
        $jsonName = (isset($matches[1])) ? $matches[1] : '';

        $reset = $headers['X-Ratelimit-Reset'][0] ?? null;

        $data = [
            'method' => $method,
            'url' => $requestUrl,
            'name' => $jsonName,
            'code' => $statusCode,
            'rate_limit' => $headers['X-Ratelimit-Limit'][0] ?? null,
            'rate_limit_remaining' => $headers['X-Ratelimit-Remaining'][0] ?? null,
            'rate_limit_reset' => $reset,
            'rate_limit_resetdate' => ($reset!=null) ? Carbon::createFromTimestamp($reset)->format('Y-m-d H:i:s') : null,
        ];

        Requests::create($data);

    }

    public function saveCursor($cursorUrl, $companyID){

        if(!$cursorUrl){
            return;
        }

        if (!$companyID) {
            return;
        }

        $pattern = '/\/([^\/]+)\.json/';
        preg_match($pattern, $cursorUrl, $matches);

        // Check if a match is found
        $cursor_type = (isset($matches[1])) ? $matches[1] : '';

        $data = [
            'cursor_url' => $cursorUrl,
            'cursor_type' => $cursor_type,
            'company_id' => $companyID
        ];

        Cursors::create($data);
        
    }

    // General method to send requests to the API
    public function request($method, $data, $headers, $endPoint, $type="form", $cursorSiteID = false )
    {

        $requestUrl = $this->endPoint.$endPoint;

        $client = new Client();

        $options = [
            'headers' => $headers
        ];
        switch ($type) {
            case "form":
                $options['form_params'] = $data;
                break;
            case "body":
                $options['body'] = json_encode($data);
                break;
            default:
                $options['form_params'] = $data;
                break;
        }

        try {
            
            $response = $client->request($method, $requestUrl, $options );

            $responseArr = json_decode($response->getBody(), true);

            $statusCode = $response->getStatusCode();

            $this->saveRequest($method, $requestUrl, $statusCode, $response->getHeaders());

            if(isset($responseArr['error'])){
                return $responseArr;
            }

            // Return the response as a decoded JSON array
            return $responseArr;

        } catch (\Exception $e) {
            // Handle error
            $errorDetails =  [
                'error' => $e->getMessage(),
                'exception' => [
                    'message' => $e->getMessage(),
                    'code' => $e->getCode(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                ],
            ];
            
            return $errorDetails;

        }
    }

    public function isValidKountaSignature($request, $companyID)
    {

        // Retrieve the signature from the header
        $signature = $request->header('x-kounta-signature') ?? null;

        if($signature==null){
            return false;
        }else{
            return true;
        }

        // Get the raw request content (payload)
        $payload = $request->getContent();

        // Retrieve the secret key based on the company ID
        $secretKey = RefreshTokens::where('company_id', $companyID)->pluck('client_secret')->first();

        // Step 1: Create the HMAC hash of the payload using the secret key
        $calculatedSignature = hash_hmac('sha256', $payload, $secretKey);

        // Step 2: Securely compare the calculated signature and the one from the header
        if (hash_equals($calculatedSignature, $signature)) {
            return true; // Signature is valid
        }

        return false; // Signature is invalid

    }

}
