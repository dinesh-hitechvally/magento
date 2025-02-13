<?php

namespace Dinesh\Magento\App\Http\Services;

use Dinesh\Magento\App\Models\Customers;
use Dinesh\Magento\App\Models\Pagination;

use Dinesh\Magento\App\Http\Services\Magento;

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
    public function getAll($setupID)
    {

        $accessToken = $this->getAccessToken($setupID);
        $data = [
            'searchCriteria' => [
                'pageSize' => 10,
            ],
        ];
        $endPoint = "/rest/V1/customers/search";

        $pagination = Pagination::where([
            'setupID' => $setupID,
            'endpoint' => "{$endPoint}",
        ])
        ->orderBy('created_at', 'desc')   // Order by 'created_at' in descending order
        ->pluck('page')
        ->first();

        if ($pagination) {
            $data['searchCriteria']['currentPage'] = $pagination;
        }
        
        $headers = [
            'Authorization' => "Bearer {$accessToken}", // Replace with valid token
            'Accept' => 'application/json',
        ];

        return $this->request('GET', $data, $headers, $endPoint, 'query', $setupID);

    }

    public function saveRow($setupID, $customer)
    {

        $dbVal = [
            'setupID' => $setupID,
            'id' => $customer['id'],
            'group_id' => $customer['group_id'],
            'default_billing' => $customer['default_billing'] ?? null,
            'default_shipping' => $customer['default_shipping'] ?? null,
            'm_created_at' => $customer['created_at'],
            'm_updated_at' => $customer['updated_at'],
            'created_in' => $customer['created_in'],
            'dob' => $customer['dob'] ?? null,
            'email' => $customer['email'],
            'firstname' => $customer['firstname'],
            'lastname' => $customer['lastname'],
            'gender' => $customer['gender'] ?? null,
            'store_id' => $customer['store_id'],
            'website_id' => $customer['website_id'],
            'addresses' => json_encode($customer['addresses']),
            'disable_auto_group_change' => $customer['disable_auto_group_change'],
            'extension_attributes' => json_encode($customer['extension_attributes']),
        ];

        HookFilterService::applyFilters('customer_data_before_save', $dbVal, $this);

        $where = [
            'id' => $customer['id']
        ];
        $result = Customers::updateOrCreate($where, $dbVal);

        return $result;

    }

    public function create( $setupID, $data )
    {
        $accessToken = $this->getAccessToken($setupID);
        $endPoint = "/rest/V1/customers";

        $headers = [
            'Authorization' => "Bearer {$accessToken}", // Replace with valid token
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
        return $this->request('POST', $data, $headers, $endPoint, 'body' );
    }

    public function get($setupID, $customerID)
    {
        $accessToken = $this->getAccessToken($setupID);
        $data = [];
        $endPoint = "/rest/V1/customers/{$customerID}";

        $headers = [
            'Authorization' => "Bearer {$accessToken}", // Replace with valid token
            'Accept' => 'application/json',
        ];
        return $this->request('GET', $data, $headers, $endPoint);
    }

    public function update($setupID, $customerID, $data)
    {
        $accessToken = $this->getAccessToken($setupID);
        $endPoint = "/rest/V1/customers/{$customerID}";

        $headers = [
            'Authorization' => "Bearer {$accessToken}", // Replace with valid token
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];

        return $this->request('PUT', $data, $headers, $endPoint, 'body');
        
    }

    public function delete($setupID, $customerID)
    {
        $accessToken = $this->getAccessToken($setupID);
        $data = [];
        $endPoint = "/rest/V1/customers/{$customerID}";

        $headers = [
            'Authorization' => "Bearer {$accessToken}", // Replace with valid token
            'Accept' => 'application/json',
        ];
        return $this->request('DELETE', $data, $headers, $endPoint);
    }

}
