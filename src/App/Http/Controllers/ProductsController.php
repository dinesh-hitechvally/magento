<?php

namespace Dinesh\Magento\App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use Dinesh\Magento\App\Models\Products;

use Dinesh\Magento\App\Http\Services\ProductService;
use Dinesh\Magento\App\Http\Services\HookFilterService;

class ProductsController extends Controller
{
    private $productService;

    public function __construct(){

        $this->productService = new ProductService();
        
    }

    public function getSetupID()
    {
        $setupID = 1;
        return $setupID;

    }

    public function index(Request $request)
    {

        $setupID = $this->getSetupID();
        
        $updatedProducts = [];
        $products = $this->productService->getAll($setupID);

        if(!isset($products['total_count'])){
            return response($products);
        }
        foreach($products['items'] as $product){
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
                'tier_prices' => json_encode($product['tier_prices'] ),
                'custom_attributes' => json_encode($product['custom_attributes'] ),
            ];

            HookFilterService::applyFilters('product_list_loop_data_before_save', $dbVal, $this);

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
                'setupID' => 'required|integer',
                'sku' => 'required|string',
            ]
        );
        if ($validator->fails()) {
            $error = $validator->errors();
            $error = [
                'success' => false,
                'message' => 'setupID & sku is required field.',
                'data' => $validator->errors(),
            ];
            return response()->json($error, 400);
        }

        $setupID = $request->setupID;
        $sku = $request->sku;

        $product = $this->productService->get($setupID, $sku);
        return response($product);

    }

    public function get(Request $request )
    {

        $validator = Validator::make(
            $request->all(),
            [
                'setupID' => 'required|integer',
                'sku' => 'required|string',
            ]
        );
        if ($validator->fails()) {
            $error = $validator->errors();
            $error = [
                'success' => false,
                'message' => 'setupID & sku is required field.',
                'data' => $validator->errors(),
            ];
            return response()->json($error, 400);
        }

        $setupID = $request->setupID;
        $sku = $request->sku;

        $product = $this->productService->get( $setupID, $sku);
        if (isset($product['error'])) {
            return response($product);
        }
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

        $where = [
            'id' => $product['id']
        ];

        HookFilterService::applyFilters('product_get_data_before_save', $dbVal, $this);

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
