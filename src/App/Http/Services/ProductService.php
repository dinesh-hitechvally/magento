<?php

namespace Dinesh\Magento\App\Http\Services;

use Dinesh\Magento\App\Http\Services\Magento;
use Dinesh\Magento\App\Models\Pagination;

class ProductService extends Magento
{
    

    // Example: Method to get products (Extend as needed)
    public function getAll($setupID)
    {

        $accessToken = $this->getAccessToken($setupID);

        $data = [
            'searchCriteria' => [
                'pageSize' => 10,
            ],
        ];

        $endPoint = "/rest/V1/products";  

        $pagination = Pagination::where('setupID', $setupID)
            ->where('endpoint', $endPoint)
            ->orderBy('created_at', 'desc')   // Order by 'created_at' in descending site
            ->pluck('page')
            ->first();
        
        $headers = [
            'Authorization' => "Bearer {$accessToken}", // Replace with valid token
            'Accept' => 'application/json',
        ];
        if ($pagination) {
            $data['searchCriteria']['currentPage'] = $pagination;
        }

        return $this->request('GET', $data, $headers, $endPoint, 'query', $setupID );

    }

    public function create($setupID, $data)
    {
        $accessToken = $this->getAccessToken($setupID);
        $endPoint = "/rest/V1/products";

        $headers = [
            'Authorization' => "Bearer {$accessToken}", // Replace with valid token
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
        return $this->request('POST', $data, $headers, $endPoint, 'body');
    }

    public function get($setupID, $sku)
    {
        $accessToken = $this->getAccessToken($setupID);
        $data = [];
        $endPoint = "/rest/V1/products/{$sku}";

        $headers = [
            'Authorization' => "Bearer {$accessToken}", // Replace with valid token
            'Accept' => 'application/json',
        ];
        return $this->request('GET', $data, $headers, $endPoint);
    }

    public function update($setupID, $productID, $data)
    {
        $accessToken = $this->getAccessToken($setupID);
        
        $endPoint = "/rest/V1/products/{$productID}";

        $headers = [
            'Authorization' => "Bearer {$accessToken}", // Replace with valid token
            'Accept' => 'application/json',
        ];
        return $this->request('PUT', $data, $headers, $endPoint, 'body');
    }

    public function delete($setupID, $sku)
    {
        $accessToken = $this->getAccessToken($setupID);
        $data = [];
        $endPoint = "/rest/V1/products/{$sku}";

        $headers = [
            'Authorization' => "Bearer {$accessToken}", // Replace with valid token
            'Accept' => 'application/json',
        ];
        return $this->request('DELETE', $data, $headers, $endPoint);
    }

}
