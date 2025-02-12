<?php

namespace Dinesh\Magento\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Dinesh\Magento\App\Http\Services\Magento;
use Dinesh\Magento\App\Models\Logs;
use Dinesh\Magento\App\Models\Pagination;

class CustomerService extends Magento{

    private static $instance = null;

    // Method to get the single instance of the class (Singleton Pattern)
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new CustomerService();
        }

        return self::$instance;
    }

    // Example: Method to get customers (Extend as needed)
    public function getAll($siteID)
    {

        $accessToken = $this->getAccessToken($siteID);
        $data = [
            'searchCriteria' => [
                'pageSize' => 10,
            ],
        ];
        $nextPageUrl = Cursors::where([
            'siteID' => $siteID,
            'endpoint' => 'customers',
        ])
        ->orderBy('created_at', 'desc')   // Order by 'created_at' in descending order
        ->pluck('endpoint')
        ->first();

        $endPoint = "/rest/V1/customers/search";
        $headers = [
            'Authorization' => "Bearer {$accessToken}", // Replace with valid token
            'Accept' => 'application/json',
        ];

        if($nextPageUrl){
            $headers['X-Next-Page'] = $nextPageUrl;
        }

        return $this->request('GET', $data, $headers, $endPoint, 'form', $companyID);

    }

    public function search($companyID, $query)
    {
        $accessToken = $this->getAccessToken($companyID);
        $data = [];
        $queryParams = http_build_query($query);
        $endPoint = "/companies/{$companyID}/customers.json?{$queryParams}";
        $headers = [
            'Authorization' => "Bearer {$accessToken}", // Replace with valid token
            'Accept' => 'application/json',
        ];
        return $this->request('GET', $data, $headers, $endPoint);
    }

    public function create( $companyID, $data )
    {
        $accessToken = $this->getAccessToken($companyID);
        $endPoint = "/companies/{$companyID}/customers.json";

        $headers = [
            'Authorization' => "Bearer {$accessToken}", // Replace with valid token
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
        return $this->request('POST', $data, $headers, $endPoint, 'body' );
    }

    public function get($companyID, $customerID)
    {
        $accessToken = $this->getAccessToken($companyID);
        $data = [];
        $endPoint = "/companies/{$companyID}/customers/{$customerID}.json";

        $headers = [
            'Authorization' => "Bearer {$accessToken}", // Replace with valid token
            'Accept' => 'application/json',
        ];
        return $this->request('GET', $data, $headers, $endPoint);
    }

    public function update($companyID, $customerID, $data)
    {
        $accessToken = $this->getAccessToken($companyID);
        $endPoint = "/companies/{$companyID}/customers/{$customerID}.json";

        $headers = [
            'Authorization' => "Bearer {$accessToken}", // Replace with valid token
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];

        return $this->request('PUT', $data, $headers, $endPoint, 'body');
        
    }

    public function createNewCustomer( $companyID, $customer )
    {

        // Define validation rules
        $rules = [
            'first_name' => 'required|string|max:255',
        ];

        // Validate the $customer array
        $validator = Validator::make($customer, $rules);

        if ($validator->fails()) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors'  => $validator->errors(),
                ],
                400
            );
        }

        $data = [
            'first_name' => $customer['first_name']
        ];

        $tags = isset($customer['tags']) ? $customer['tags'] : [];
        if (isset($customer['litecard_member_id'])) {
            $memberTag = "litecard-" . $customer['litecard_member_id'];
            if (!in_array($memberTag, $tags)) {
                $tags[] = $memberTag;
            }
        }

        if (isset($customer['dob']) && $customer['dob']!=null && $customer['dob'] != '') {
            //$dob = Carbon::createFromFormat('d/m/Y', $customer['dob'])->format('Y-m-d');
            $dob = $customer['dob'];
            if (!in_array($dob, $tags)) {
                $tags[] = $dob;
            }
        }

        $data['tags'] = $tags;
        
        if (isset($customer['last_name']) && strlen($customer['last_name']) > 0 ) {
            $data['last_name'] = $customer['last_name'];
        }

        if (isset($customer['email']) && strlen($customer['email']) > 0) {
            $data['email'] = $customer['email'];
        }

        if (isset($customer['phone']) && strlen($customer['phone']) > 0 ) {
            $data['phone'] = $customer['phone'];
        }

        if (isset($customer['reference_id']) && strlen($customer['reference_id']) > 0) {
            $data['reference_id'] = $customer['reference_id'];
        }

        if(isset($customer['primary_postal_code']) && $customer['primary_postal_code'] != null){
            $data['primary_address']['postal_code'] = $customer['primary_postal_code'];
        }

        if (isset($customer['accepts_marketing'])) {
            $data['accepts_marketing'] = $customer['accepts_marketing'] ? true : false;
        }

        $result = $this->create($companyID, $data);

        if (isset($result['error'])) {
            return response()->json($result, 200);
        }
        
        if($result==null){     
            $response = [
                'success' => true,
                'message' => 'Successfully created a new customers.',
                'data' => $result,
            ];   
        }else{
            $response = [
                'success' => false,
                'message' => 'Failed create a new customers.',
                'data' => $result,
            ];
        }

        return $response;

    }

}
