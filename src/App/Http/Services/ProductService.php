<?php

namespace Dinesh\Magento\App\Http\Services;

use Dinesh\Magento\App\Http\Services\Magento;
use Dinesh\Magento\App\Models\Cursors;

class ProductService extends Magento
{

    private static $instance = null;

    // Method to get the single instance of the class (Singleton Pattern)
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new ProductService();
        }

        return self::$instance;
    }

    // Example: Method to get products (Extend as needed)
    public function getAll($siteID)
    {

        $accessToken = $this->getAccessToken($siteID);
       
        $data = [];
        $nextPageUrl = Cursors::where('siteID', $siteID)
            ->where('cursor_type', 'products')
            ->orderBy('created_at', 'desc')   // Order by 'created_at' in descending site
            ->pluck('cursor_url')
            ->first();
        $endPoint = "/rest/V1/products?searchCriteria[pageSize]=10";
        $headers = [
            'Authorization' => "Bearer {$accessToken}", // Replace with valid token
            'Accept' => 'application/json',
        ];
        if ($nextPageUrl) {
            $headers['X-Next-Page'] = $nextPageUrl;
        }
        $this->setEndPoint($siteID);
        return $this->request('GET', $data, $headers, $endPoint, 'form', $siteID);
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
        $this->setEndPoint($siteID);
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
