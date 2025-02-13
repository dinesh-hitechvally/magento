<?php

namespace Dinesh\Magento\App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use Dinesh\Magento\App\Models\Customers;

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

        $companyID = $this->getSetupID();

        $updatedCustomers = [];
        $customers = $this->customers->getAll($companyID);
        if (isset($customers['error'])) {
            return response($customers);
        }
        foreach ($customers as $customer) {

            $dbVal = [
                'id' => $customer['id'],
                'company_id' => $companyID,
                'first_name' => $customer['first_name'],
                'last_name' => $customer['last_name'],
                'email' => $customer['email'],
                'primary_email_address' => $customer['primary_email_address'],
                'primary_address' => $customer['primary_address']['address'],
                'primary_city' => $customer['primary_address']['city'],
                'primary_state' => $customer['primary_address']['state'],
                'primary_postal_code' => $customer['primary_address']['postal_code'],
                'primary_country' => $customer['primary_address']['country'],
                'phone' => $customer['phone'],
                'tags' => json_encode($customer['tags']),
                'reference_id' => $customer['reference_id'],
                'image' => $customer['image'],
                'accepts_marketing' => $customer['accepts_marketing'],
                'detail_pending' => 1,
            ];

            $where = [
                'id' => $customer['id']
            ];
            $result = Customers::updateOrCreate($where, $dbVal);

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
                'companyID' => 'required|integer',
                'customerID' => 'required|integer',
            ]
        );
        if ($validator->fails()) {
            $error = $validator->errors();
            $error = [
                'success' => false,
                'message' => 'companyID & customerID is required field.',
                'data' => $validator->errors(),
            ];
            return response()->json($error, 400);
        }

        $companyID = $request->companyID;
        $customerID = $request->customerID;

        $customer = $this->customers->get($companyID, $customerID);

        return response($customer);

    }

    public function search(Request $request)
    {

        $companyID = $this->getSetupID();
        
        $updatedCustomers = [];
        $query = $request->all();
        $customer = $this->customers->search($companyID, $query);
        if (isset($customer['error'])) {
            return response($customer);
        }
        $customers = [$customer];
        foreach ($customers as $customer) {
            $dbVal = [
                'id' => $customer['id'],
                'company_id' => $companyID,
                'first_name' => $customer['first_name'],
                'last_name' => $customer['last_name'],
                'email' => $customer['email'],
                'primary_email_address' => $customer['primary_email_address'],
                'primary_address' => $customer['primary_address']['address'],
                'primary_city' => $customer['primary_address']['city'],
                'primary_state' => $customer['primary_address']['state'],
                'primary_postal_code' => $customer['primary_address']['postal_code'],
                'primary_country' => $customer['primary_address']['country'],
                'phone' => $customer['phone'],
                'tags' => json_encode($customer['tags']),
                'reference_id' => $customer['reference_id'],
                'image' => $customer['image'],
                'accepts_marketing' => $customer['accepts_marketing'],
                'detail_pending' => 1,
            ];

            $where = [
                'id' => $customer['id']
            ];
            $result = Customers::updateOrCreate($where, $dbVal);
            $updatedCustomers[] = $result;
        }

        return response()->json([
            'message' => 'Customers updated or created successfully.',
            'data' => $updatedCustomers
        ], 200);
    }

    

    public function getCustomerDetail(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'topic' => 'nullable', //For webhook
                'companyID' => 'required|integer',
                'customerID' => 'required|integer',
            ]
        );
        if ($validator->fails()) {
            $error = $validator->errors();
            $error = [
                'success' => false,
                'message' => 'companyID & customerID is required field.',
                'data' => $validator->errors(),
            ];
            return response()->json($error, 400);
        }

        $customerID = $request->customerID;
        $companyID = $request->companyID;

        $customer = $this->customers->get($companyID, $customerID);
        if (isset($customer['error'])) {
            return response($customer);
        }

        $dbVal = [
            'id' => $customer['id'],
            'company_id' => $companyID,
            'first_name' => $customer['first_name'],
            'last_name' => $customer['last_name'],
            'email' => $customer['email'],
            'primary_email_address' => $customer['primary_email_address'],
            'primary_address' => $customer['primary_address']['address'],
            'primary_city' => $customer['primary_address']['city'],
            'primary_state' => $customer['primary_address']['state'],
            'primary_postal_code' => $customer['primary_address']['postal_code'],
            'primary_country' => $customer['primary_address']['country'],
            'phone' => $customer['phone'],
            'tags' => json_encode($customer['tags']),
            'reference_id' => $customer['reference_id'],
            'image' => $customer['image'],
            'accepts_marketing' => $customer['accepts_marketing'],
            'ls_created_at' => Carbon::parse($customer['created_at'])->format('Y-m-d H:i:s'),
            'ls_updated_at' => Carbon::parse($customer['updated_at'])->format('Y-m-d H:i:s'),
            'detail_pending' => 0,
            
        ];


        $where = [
            'id' => $customer['id']
        ];


        $result = Customers::updateOrCreate($where, $dbVal);

        $updatedCustomers[] = $result;
        return response()->json(
            [
                'message' => 'Customers updated or created successfully.',
                'data' => $updatedCustomers
            ],
            200
        );

    }

    public function createCustomerDetail(Request $request)
    {
        dd($request); // Create customer if needed from customers service not from controller
        $companyID = $this->getSetupID();
        $where = [
            'company_id' => $companyID,
            'lightSpeedPending' => 1,
            'isLoyalty' => 1,
        ];
        $customer = Customers::where($where)
            ->whereNull('id')
            ->orderBy('created_at', 'ASC')
            ->first();
        if (!$customer) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'No pending customers found to create.',
                ],
                400
            );
        }
        $tags = json_decode($customer->tags, true) ?? [];
        if ($customer->litecard_member_id) {
            $litecardTag = "litecard-" . $customer->litecard_member_id;
            if (!in_array($litecardTag, $tags)) {
                $tags[] = $litecardTag;
            }
        }

        $data = [
            'first_name' => $customer->first_name
        ];
        if ($customer->last_name != null) {
            $data['last_name'] = $customer->last_name;
        }

        if ($customer->last_name != null) {
            $data['last_name'] = $customer->last_name;
        }

        if ($customer->email != null) {
            $data['email'] = $customer->email;
        }

        if ($customer->phone != null) {
            $data['phone'] = $customer->phone;
        }

        if ($customer->image != null) {
            $data['image'] = $customer->image;
        }

        if ($customer->accepts_marketing != null) {
            $data['accepts_marketing'] = $customer->accepts_marketing;
        }

        if ($customer->reference_id != null) {
            $data['reference_id'] = $customer->reference_id;
        }

        $response = $this->customers->create($companyID, $data);

        if (isset($response['error'])) {
            return response()->json($response, 200);
        }
        $customer->id = $response['id'];
        $customer->detail_pending = 1;
        $customer->save();

        return response()->json( $response, 200 );

    }

    public function updateCustomerDetail( Request $request ){

        $validator = Validator::make(
            $request->all(),
            [
                'customerID' => 'required|integer',
                'companyID' => 'required|integer',
            ]
        );
        if ($validator->fails()) {
            $error = $validator->errors();
            $error = [
                'success' => false,
                'message' => 'companyID & customerID are required fields.',
                'data' => $validator->errors(),
            ];
            return response()->json($error, 400);
        }

        $companyID = $request->companyID;
        $customerID = $request->customerID;

        $where = [
            'id' => $customerID,
            'company_id' => $companyID,
            'lightSpeedPending' => 1,
            'isLoyalty' => 1,
        ];

        $customer = Customers::where( $where )
            ->whereNotNull('id')
            ->first();
        if (!$customer) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'No pending customer found to update.',
                ],
                400
            );
        }

        $tags = json_decode($customer->tags, true) ?? [];
        if ($customer->litecard_member_id) {
            $litecardTag = "litecard-" . $customer->litecard_member_id;
            if (!in_array($litecardTag, $tags)) {
                $tags[] = $litecardTag;
            }
        }

        $data = [
            'first_name' => $customer->first_name,
            'last_name' => $customer->last_name,
            'email' => $customer->email,
            'phone' => $customer->phone,
            'image' => $customer->image,
            'accepts_marketing' => $customer->accepts_marketing,
            'reference_id' => $customer->reference_id,
            'tags' => $tags,
            'primary_address' => [
                'address' => $customer->primary_address,
                'city' => $customer->primary_city,
                'state' => $customer->primary_state,
                'postal_code' => $customer->primary_postal_code,
                'country' => $customer->primary_country,
            ],
            'shipping_address' => [
                'address' => $customer->shipping_address,
                'city' => $customer->shipping_city,
                'state' => $customer->shipping_state,
                'postal_code' => $customer->shipping_postal_code,
                'country' => $customer->shipping_country,
            ]
        ];

        $response = $this->customers->update($companyID, $customerID, $data);

        if (isset($response['error'])) {
            return response()->json($response, 200);
        }
        
        $customer->detail_pending = 1;
        $customer->save();

        return response()->json($response, 200);

    }

    public function getCustomers()
    {
        $customers = Customers::where('detail_pending', 1) // Change 'find' to 'where'
            ->whereNotNull('id')
            ->orderBy('updated_at', 'ASC')
            ->limit(10) // Limit the results to 10
            ->select('customerID', 'id', 'company_id', 'updated_at', 'isLoyalty') // Specify the columns you want
            ->get();

        $updatedCustomers = [];

        if ($customers->isEmpty()) {
            return response()->json(
                [
                    'message' => 'No customers were found to create or update.',
                    'data' => $updatedCustomers
                ],
                200
            );
        }
        foreach ($customers as $cust) {

            $customerID = $cust->id;
            $companyID = $cust->company_id;
            $isLoyalty = $cust->isLoyalty;

            $customer = $this->customers->get($companyID, $customerID);

            if (isset($customer['error'])) {

                $customer['customerID'] = $customerID;
                $customer['company_id'] = $companyID;

                $updatedCustomers[] = $customer;

                continue;
            }

            $dbVal = [
                'id' => $customer['id'],
                'company_id' => $companyID,
                'first_name' => $customer['first_name'],
                'last_name' => $customer['last_name'],
                'email' => $customer['email'],
                'primary_email_address' => $customer['primary_email_address'],
                'primary_address' => $customer['primary_address']['address'],
                'primary_city' => $customer['primary_address']['city'],
                'primary_state' => $customer['primary_address']['state'],
                'primary_postal_code' => $customer['primary_address']['postal_code'],
                'primary_country' => $customer['primary_address']['country'],
                'phone' => $customer['phone'],
                'tags' => json_encode($customer['tags']),
                'reference_id' => $customer['reference_id'],
                'image' => $customer['image'],
                'accepts_marketing' => $customer['accepts_marketing'],
                'ls_created_at' => Carbon::parse($customer['created_at'])->format('Y-m-d H:i:s'),
                'ls_updated_at' => Carbon::parse($customer['updated_at'])->format('Y-m-d H:i:s'),
                'detail_pending' => 0,
            ];

            $where = [
                'id' => $customer['id']
            ];
            $result = Customers::updateOrCreate($where, $dbVal);

            $updatedCustomers[] = $result;
            
        }

        return response()->json(
            [
                'message' => 'Customers updated or created successfully.',
                'data' => $updatedCustomers
            ],
            200
        );
    }

    public function updateTags()
    {

        $debug = request()->query('debug') ?? 0;
        
        $companyID = $this->getSetupID();
        $where = [
            'lightSpeedPendingTags' => 1,
            'isLoyalty' => 1,
            'company_id' => $companyID,
        ];
        
        $customers = Customers::where($where)->limit(10)->get();
        if($debug==1){
            dd($customers);
        }
        if ($customers->isEmpty()) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'All customers tags are already updated for this company.',
                ],
                200
            );
        }

        foreach($customers as $customer){

            $tags = json_decode($customer->tags, true) ?? [];
            if ($customer->litecard_member_id) {
                $litecardTag = "litecard-" . $customer->litecard_member_id;
                if (!in_array($litecardTag, $tags)) {
                    $tags[] = $litecardTag;
                }
            }
            $data = [
                'tags' => $tags,
                'reference_id' => $customer->reference_id,
            ];
            $response = $this->customers->update($companyID, $customer->id, $data);
            if (!isset($response['error'])) {
                $customer->lightSpeedPendingTags = 0;
                $customer->save();
            }
        }

        return response()->json($response, 200);

    }
}
