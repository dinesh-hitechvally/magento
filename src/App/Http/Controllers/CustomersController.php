<?php

namespace Dinesh\Magento\App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use Dinesh\Magento\App\Http\Services\CustomerService;

class CustomersController extends Controller
{
    private $customers;

    public function __construct()
    {        
        $this->customers =  CustomerService::getInstance();

    }

    public function getSetupID()
    {
        $setupID = 1;

        return $setupID;

    }

    public function index(Request $request)
    {

        $setupID = $this->getSetupID();

        $updatedCustomers = [];
        $customers = $this->customers->getAll($setupID);
        if (!isset($customers['total_count'])) {
            return response($customers);
        }
        
        foreach ($customers['items'] as $customer) {
           
            $result = $this->customers->saveRow($setupID, $customer);

            $updatedCustomers[] = $result;
        }

        return response()->json([
            'message' => 'Customers updated or created successfully.',
            'data' => $updatedCustomers
        ], 200);

    }

    public function live(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'setupID' => 'required|integer',
                'customerID' => 'required|integer',
            ]
        );
        if ($validator->fails()) {
            $error = $validator->errors();
            $error = [
                'success' => false,
                'message' => 'setupID & customerID is required field.',
                'data' => $validator->errors(),
            ];
            return response()->json($error, 400);
        }

        $setupID = $request->setupID;
        $customerID = $request->customerID;

        $customer = $this->customers->get($setupID, $customerID);

        return response($customer);

    }

    public function get(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'topic' => 'nullable', //For webhook
                'setupID' => 'required|integer',
                'customerID' => 'required|integer',
            ]
        );
        if ($validator->fails()) {
            $error = $validator->errors();
            $error = [
                'success' => false,
                'message' => 'setupID & customerID is required field.',
                'data' => $validator->errors(),
            ];
            return response()->json($error, 400);
        }

        $customerID = $request->customerID;
        $setupID = $request->setupID;

        $customer = $this->customers->get($setupID, $customerID);
        if (!isset($customer['id'])) {
            return response($customer);
        }
        $result = $this->customers->saveRow($setupID, $customer);
        $updatedCustomers[] = $result;
        return response()->json(
            [
                'message' => 'Customers updated or created successfully.',
                'data' => $updatedCustomers
            ],
            200
        );

    }

}
