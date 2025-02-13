<?php

namespace Dinesh\Magento\App\Http\Services;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Dinesh\Magento\App\Models\Setup;
use Dinesh\Magento\App\Models\Requests;
use Dinesh\Magento\App\Models\Pagination;
use Dinesh\Magento\App\Models\AccessTokens;

use Dinesh\Magento\App\Http\Structure\ApiInterfaceService;

abstract class Magento implements ApiInterfaceService
{

    protected $endPoint = null;
    private static $instance = null;

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    public function setEndPoint($setupID)
    {
        $endPoint = Setup::where('setupID', $setupID)->pluck('url')->first();
        $this->endPoint = $endPoint;
    }

    public function getEndPoint($setupID)
    {
        $endPoint = Setup::where('setupID', $setupID)->pluck('url')->first();
        $this->endPoint = $endPoint;
    }

    // Generate access token using refresh token
    public function getAccessToken( $setupID )
    {

        if(!$setupID){
            dd('Unable to get access token without setupID.');
        }

        $this->setEndPoint($setupID);

        $accessRow = AccessTokens::where('setupID', $setupID )
            ->where('expire_at', '>=', Carbon::now())
            ->orderBy('setupID', 'desc')
            ->first();

        if (isset($accessRow->access_token)) {
            return $accessRow->access_token;
        }

        $siteRow = Setup::where( 'setupID', $setupID )->first();
        if(!$siteRow){
            dd('Please configure magento website first before proceeding.');
        }
        
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
            'setupID' => $setupID,
            'access_token' => $response,
            'expire_at' => Carbon::now()->addHours(23)
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

    public function savePagination( $endPoint, $response, $setupID){

        if(!$endPoint){
            return;
        }

        if (!$setupID) {
            return;
        }

        $search_criteria = $response['search_criteria'] ?? [];
        $current_page = $search_criteria['current_page'] ?? 1;
        $page = $current_page+1;

        $data = [
            'setupID' => $setupID,
            'endpoint' => $endPoint,
            'page' => $page,
        ];

        Pagination::create($data);
        
    }

    // General method to send requests to the API
    public function request($method, $data, $headers, $endPoint, $type="form", $cursorSiteID = false )
    {

        $requestUrl = $this->endPoint.$endPoint;

        $client = new Client();

        $options = [
            'headers' => $headers
        ];
        $apiUrl = $requestUrl;
        switch ($type) {
            case "form":
                $options['form_params'] = $data;
                break;
            case "body":
                $options['body'] = json_encode($data);
                break;
            case "query":
                $options['query'] = $data;
                break;
            default:
                $options['form_params'] = $data;
                break;
        }

        try {
            
            $response = $client->request($method, $apiUrl, $options );

            $responseArr = json_decode($response->getBody(), true);

            $statusCode = $response->getStatusCode();

            $this->saveRequest($method, $requestUrl, $statusCode, $response->getHeaders());

            if(isset($responseArr['error'])){
                return $responseArr;
            }

            $this->savePagination( $endPoint, $responseArr, $cursorSiteID);

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

    abstract public function getAll($setupID);

    abstract public function create($setupID, $data);

    abstract public function get($setupID, $id);

    abstract public function update($setupID, $id, $data);

    abstract public function delete($setupID, $id);

}
