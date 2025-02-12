<?php

namespace Dinesh\Magento\App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Dinesh\Magento\App\Models\Products;
use Dinesh\Magento\App\Http\Services\ProductService;

class ProductsController extends Controller
{
    private $service;

    public function __construct(){

        $this->service = new ProductService();
        
    }

    public function getSiteID()
    {
        $siteID = 1;
        return $siteID;

    }

    public function index(Request $request)
    {

        $siteID = $this->getSiteID();
        
        $updatedProducts = [];
        $products = $this->service->getAll($siteID);

        if(!isset($products['total_count'])){
            return response($products);
        }
        foreach($products['items'] as $product){
            $dbVal = [
                'siteID' => $siteID,
                'id' => $product['id'],
                'sku' => $product['sku'],
                'name' => $product['name'],
                'attribute_set_id' => $product['attribute_set_id'],
                'price' => $product['price'],
                'status' => $product['status'],
                'visibility' => $product['visibility'],
                'type_id' => $product['type_id'],
                'm_created_at' => $product['created_at'],
                'm_updated_at' => $product['updated_at'],
                'extension_attributes' => json_encode($product['extension_attributes']),
                'product_links' => json_encode($product['product_links']),
                'options' => json_encode($product['options']),
                'media_gallery_entries' => json_encode($product['media_gallery_entries']),
                'tier_prices' => json_encode($product['tier_prices'] ),
                'custom_attributes' => json_encode($product['custom_attributes'] ),
            ];

            $where = [
                'id' => $product['id']
            ];
            $result = Products::updateOrCreate($where, $dbVal);
            $updatedProducts[] = $result;
        }

        return response()->json([
            'message' => 'Products updated or created successfully.',
            'data' => $updatedProducts
        ], 200);
        
    }

    public function live(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'siteID' => 'required|integer',
                'sku' => 'required|string',
            ]
        );
        if ($validator->fails()) {
            $error = $validator->errors();
            $error = [
                'success' => false,
                'message' => 'siteID & sku is required field.',
                'data' => $validator->errors(),
            ];
            return response()->json($error, 400);
        }

        $siteID = $request->siteID;
        $sku = $request->sku;

        $product = $this->service->get($siteID, $sku);
        return response($product);

    }

    public function getProductDetail(Request $request )
    {

        $validator = Validator::make(
            $request->all(),
            [
                'siteID' => 'required|integer',
                'sku' => 'required|string',
            ]
        );
        if ($validator->fails()) {
            $error = $validator->errors();
            $error = [
                'success' => false,
                'message' => 'siteID & sku is required field.',
                'data' => $validator->errors(),
            ];
            return response()->json($error, 400);
        }

        $siteID = $request->siteID;
        $sku = $request->sku;

        $product = $this->service->get( $siteID, $sku);
        if (isset($product['error'])) {
            return response($product);
        }
        $dbVal = [
            'siteID' => $siteID,
            'id' => $product['id'],
            'sku' => $product['sku'],
            'name' => $product['name'],
            'attribute_set_id' => $product['attribute_set_id'],
            'price' => $product['price'],
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

        $where = [
            'id' => $product['id']
        ];
        $result = Products::updateOrCreate($where, $dbVal);
        $updatedProducts[] = $result;


        return response()->json([
            'message' => 'Products updated or created successfully.',
            'data' => $updatedProducts
        ],
            200
        );
    }

}
