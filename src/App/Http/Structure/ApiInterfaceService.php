<?php

namespace Dinesh\Magento\App\Http\Structure;

// Define the interface
interface ApiInterfaceService
{
    
    // Define method signatures that implementing classes should implement
    public static function getInstance();
    public function setEndPoint($setupID);
    public function getEndPoint($errorCode);
    public function getAccessToken( $setupID );
    public function saveRequest($method, $requestUrl, $statusCode, $headers);
    public function savePagination($endPoint, $response, $setupID);
    public function request($method, $data, $headers, $endPoint, $type = "form", $cursorSiteID = false);

    /*
     * Create these functions to child class
     */

    public function getAll($setupID);
    public function create($setupID, $data);
    public function get($setupID, $id);
    public function update($setupID, $id, $data);
    public function delete($setupID, $id);

}
