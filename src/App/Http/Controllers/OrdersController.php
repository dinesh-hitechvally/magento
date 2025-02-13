<?php

namespace Dinesh\Magento\App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use Dinesh\Magento\App\Http\Services\OrderService;

class OrdersController extends Controller
{
    private $order;

    public function __construct()
    {

        $this->order = new OrderService();
    }

    public function getSetupID()
    {
        $setupID = 1;
        return $setupID;
    }

    public function index(Request $request)
    {

        $setupID = $this->getSetupID();

        $updatedOrders = [];
        $orders = $this->order->getAll($setupID);

        if (!isset($orders['total_count'])) {
            return response($orders);
        }
        foreach ($orders['items'] as $order) {

            $result = $this->order->saveRow($setupID, $order);
            $updatedOrders[] = $result;

        }

        return response()->json([
            'message' => 'Orders updated or created successfully.',
            'data' => $updatedOrders
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

        $order = $this->order->get($setupID, $sku);
        return response($order);
    }

    public function get(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'setupID' => 'required|integer',
                'entityID' => 'required|integer',
            ]
        );
        if ($validator->fails()) {
            $error = $validator->errors();
            $error = [
                'success' => false,
                'message' => 'setupID & entityID is required field.',
                'data' => $validator->errors(),
            ];
            return response()->json($error, 400);
        }

        $setupID = $request->setupID;
        $entityID = $request->entityID;

        $order = $this->order->get($setupID, $entityID);
        if (!isset($order['entity_id'])) {
            return response($order);
        }

        $result = $this->order->saveRow($setupID, $order);
        $updatedOrders[] = $result;

        return response()->json(
            [
                'message' => 'Orders updated or created successfully.',
                'data' => $updatedOrders
            ],
            200
        );
    }
}
