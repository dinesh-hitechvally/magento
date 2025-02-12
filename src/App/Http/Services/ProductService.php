<?php

namespace Dinesh\Magento\App\Http\Services;

use Dinesh\Magento\App\Http\Services\Magento;
use Dinesh\Magento\App\Models\Pagination;

class ProductService extends Magento
{
    

    // Example: Method to get products (Extend as needed)
    public function getAll($siteID)
    {

        $accessToken = $this->getAccessToken($siteID);

        $data = [
            'searchCriteria' => [
                'pageSize' => 10,
            ],
        ];

        $endPoint = "/rest/V1/products";  

        $pagination = Pagination::where('siteID', $siteID)
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

        return $this->request('GET', $data, $headers, $endPoint, 'query', $siteID );

    }

    public function get($siteID, $sku)
    {
        $accessToken = $this->getAccessToken($siteID);
        $data = [];
        $endPoint = "/rest/V1/products/{$sku}";

        $headers = [
            'Authorization' => "Bearer {$accessToken}", // Replace with valid token
            'Accept' => 'application/json',
        ];
        return $this->request('GET', $data, $headers, $endPoint);
    }

    public function update($siteID, $productID, $data)
    {
        $accessToken = $this->getAccessToken($siteID);
        $endPoint = "/companies/{$siteID}/products/{$siteID}.json";

        $headers = [
            'Authorization' => "Bearer {$accessToken}", // Replace with valid token
            'Accept' => 'application/json',
        ];
        return $this->request('PUT', $data, $headers, $endPoint, 'body');
    }
}
