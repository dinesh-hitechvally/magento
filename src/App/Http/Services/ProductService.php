<?php

namespace Dinesh\Magento\App\Http\Services;

use Dinesh\Magento\App\Models\Products;
use Dinesh\Magento\App\Models\Pagination;

use Dinesh\Magento\App\Http\Services\Magento;

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

    public function saveRow($setupID, $product){

        $dbVal = [
            'setupID' => $setupID,
            'id' => $product['id'],
            'sku' => $product['sku'],
            'name' => $product['name'],
            'attribute_set_id' => $product['attribute_set_id'],
            'price' => $product['price'] ?? null,
            'status' => $product['status'],
            'visibility' => $product['visibility'],
            'type_id' => $product['type_id'],
            'm_created_at' => $product['created_at'],
            'm_updated_at' => $product['updated_at'],
            'extension_attributes' => json_encode($product['extension_attributes']),
            'product_links' => json_encode($product['product_links']),
            'options' => json_encode($product['options']),
            'media_gallery_entries' => json_encode($product['media_gallery_entries']),
            'tier_prices' => json_encode($product['tier_prices']),
            'custom_attributes' => json_encode($product['custom_attributes']),
        ];

        HookFilterService::applyFilters('product_data_before_save', $dbVal, $this);

        $where = [
            'setupID' => $setupID,
            'id' => $product['id'],
        ];

        $result = Products::updateOrCreate($where, $dbVal);

        return $result;
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
