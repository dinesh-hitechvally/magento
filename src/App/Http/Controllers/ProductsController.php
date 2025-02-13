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
    private $products;

    public function __construct(){

        $this->products = new ProductService();
        
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
        $products = $this->products->getAll($setupID);

        if(!isset($products['total_count'])){
            return response($products);
        }
        foreach($products['items'] as $product){

            $result = $this->products->saveRow($setupID, $product);
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

        $product = $this->products->get($setupID, $sku);
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

        $product = $this->products->get( $setupID, $sku);
        if (!isset($product['id'])) {
            return response($product);
        }

        $result = $this->products->saveRow($setupID, $product);
        $updatedProducts[] = $result;

        return response()->json([
            'message' => 'Products updated or created successfully.',
            'data' => $updatedProducts
        ],
            200
        );
    }

}
