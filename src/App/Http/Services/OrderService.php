<?php

namespace Dinesh\Magento\App\Http\Services;

use Dinesh\Magento\App\Models\Orders;
use Dinesh\Magento\App\Models\OrderLines;
use Dinesh\Magento\App\Models\Pagination;
use Dinesh\Magento\App\Models\OrderPayment;
use Dinesh\Magento\App\Models\OrderBillingAddress;

use Dinesh\Magento\App\Http\Services\Magento;

class OrderService extends Magento
{


    // Example: Method to get orders (Extend as needed)
    public function getAll($setupID)
    {

        $accessToken = $this->getAccessToken($setupID);
        $data = [
            'searchCriteria' => [
                'pageSize' => 10,
            ],
        ];
        $endPoint = "/rest/V1/orders";

        $pagination = Pagination::where([
            'setupID' => $setupID,
            'endpoint' => "{$endPoint}",
        ])
            ->orderBy('created_at', 'desc')   // Order by 'created_at' in descending order
            ->pluck('page')
            ->first();

        if ($pagination) {
            $data['searchCriteria']['currentPage'] = $pagination;
        }

        $headers = [
            'Authorization' => "Bearer {$accessToken}", // Replace with valid token
            'Accept' => 'application/json',
        ];

        return $this->request('GET', $data, $headers, $endPoint, 'query', $setupID);
    }

    public function saveRow($setupID, $order)
    {

        $dbVal = [
            'setupID' => $setupID,
            'entity_id' => $order['entity_id'],
            'applied_rule_ids' => $order['applied_rule_ids'] ?? null,
            'base_currency_code' => $order['base_currency_code'],
            'base_discount_amount' => $order['base_discount_amount'],
            'base_discount_invoiced' => $order['base_discount_invoiced'] ?? null,
            'base_grand_total' => $order['base_grand_total'],
            'base_discount_tax_compensation_amount' => $order['base_discount_tax_compensation_amount'],
            'base_discount_tax_compensation_invoiced' => $order['base_discount_tax_compensation_invoiced'] ?? null,
            'base_shipping_amount' => $order['base_shipping_amount'],
            'base_shipping_discount_amount' => $order['base_shipping_discount_amount'],
            'base_shipping_discount_tax_compensation_amnt' => $order['base_shipping_discount_tax_compensation_amnt'],
            'base_shipping_incl_tax' => $order['base_shipping_incl_tax'],
            'base_shipping_invoiced' => $order['base_shipping_invoiced'] ?? null,
            'base_shipping_tax_amount' => $order['base_shipping_tax_amount'],
            'base_subtotal' => $order['base_subtotal'],
            'base_subtotal_incl_tax' => $order['base_subtotal_incl_tax'],
            'base_subtotal_invoiced' => $order['base_subtotal_invoiced'] ?? null,
            'base_tax_amount' => $order['base_tax_amount'],
            'base_tax_invoiced' => $order['base_tax_invoiced'] ?? null,
            'base_total_due' => $order['base_total_due'],
            'base_total_invoiced' => $order['base_total_invoiced'] ?? null,
            'base_total_invoiced_cost' => $order['base_total_invoiced_cost'] ?? null,
            'base_total_paid' => $order['base_total_paid'] ?? null,
            'base_to_global_rate' => $order['base_to_global_rate'],
            'base_to_order_rate' => $order['base_to_order_rate'],
            'billing_address_id' => $order['billing_address_id'],
            'm_created_at' => $order['created_at'],

            'customer_dob' => $order['customer_dob'] ?? null,
            'customer_email' => $order['customer_email'],
            'customer_firstname' => $order['customer_firstname'],
            'customer_gender' => $order['customer_gender'] ?? null,
            'customer_group_id' => $order['customer_group_id'],
            'customer_id' => $order['customer_id'] ?? null,
            'customer_is_guest' => $order['customer_is_guest'],
            'customer_lastname' => $order['customer_lastname'],
            'customer_note_notify' => $order['customer_note_notify'],

            'discount_amount' => $order['discount_amount'],
            'discount_invoiced' => $order['discount_invoiced'] ?? null,
            'global_currency_code' => $order['global_currency_code'],
            'grand_total' => $order['grand_total'],
            'discount_tax_compensation_amount' => $order['discount_tax_compensation_amount'],
            'discount_tax_compensation_invoiced' => $order['discount_tax_compensation_invoiced'] ?? null,
            'increment_id' => $order['increment_id'],
            'is_virtual' => $order['is_virtual'],
            'order_currency_code' => $order['order_currency_code'],
            'protect_code' => $order['protect_code'],
            'quote_id' => $order['quote_id'],

            'shipping_amount' => $order['shipping_amount'],
            'shipping_description' => $order['shipping_description'],
            'shipping_discount_amount' => $order['shipping_discount_amount'],
            'shipping_discount_tax_compensation_amount' => $order['shipping_discount_tax_compensation_amount'],
            'shipping_incl_tax' => $order['shipping_incl_tax'],
            'shipping_invoiced' => $order['shipping_invoiced'] ?? null,
            'shipping_tax_amount' => $order['shipping_tax_amount'],

            'state' => $order['state'],
            'status' => $order['status'],
            'store_currency_code' => $order['store_currency_code'],
            'store_id' => $order['store_id'],
            'store_name' => $order['store_name'],
            'store_to_base_rate' => $order['store_to_base_rate'],
            'store_to_order_rate' => $order['store_to_order_rate'],
            'subtotal' => $order['subtotal'],
            'subtotal_incl_tax' => $order['subtotal_incl_tax'],
            'subtotal_invoiced' => $order['subtotal_invoiced'] ?? null,
            'tax_amount' => $order['tax_amount'],
            'tax_invoiced' => $order['tax_invoiced'] ?? null,
            'total_due' => $order['total_due'],
            'total_invoiced' => $order['total_invoiced'] ?? null,
            'total_item_count' => $order['total_item_count'],
            'total_paid' => $order['total_paid'] ?? null,
            'total_qty_ordered' => $order['total_qty_ordered'],
            'm_updated_at' => $order['updated_at'],
            'weight' => $order['weight'],
        ];

        HookFilterService::applyFilters('order_data_before_save', $dbVal, $this);

        $where = [
            'setupID' => $setupID,
            'entity_id' => $order['entity_id']
        ];
        $result = Orders::updateOrCreate($where, $dbVal);

        return $result;
    }

    public function saveLines($setupID, $order)
    {

        $results = [];
        foreach ($order['items'] as $lineItem) {

            $dbVal = [
                'setupID' => $setupID,
                'store_id' => $lineItem['store_id'],
                'order_id' => $lineItem['order_id'],
                'item_id' => $lineItem['item_id'],
                'product_id' => $lineItem['product_id'],
                'sku' => $lineItem['sku'],
                'amount_refunded' => $lineItem['amount_refunded'],
                'applied_rule_ids' => $lineItem['applied_rule_ids'],
                'base_amount_refunded' => $lineItem['base_amount_refunded'],
                'base_discount_amount' => $lineItem['base_discount_amount'],
                'base_discount_invoiced' => $lineItem['base_discount_invoiced'],
                'base_discount_tax_compensation_amount' => $lineItem['base_discount_tax_compensation_amount'],
                'base_discount_tax_compensation_invoiced' => $lineItem['base_discount_tax_compensation_invoiced'],
                'base_original_price' => $lineItem['base_original_price'],
                'base_price' => $lineItem['base_price'],
                'base_price_incl_tax' => $lineItem['base_price_incl_tax'],
                'base_row_invoiced' => $lineItem['base_row_invoiced'],
                'base_row_total' => $lineItem['base_row_total'],
                'base_row_total_incl_tax' => $lineItem['base_row_total_incl_tax'],
                'base_tax_amount' => $lineItem['base_tax_amount'],
                'base_tax_invoiced' => $lineItem['base_tax_invoiced'],
                'm_created_at' => $lineItem['created_at'],
                'discount_amount' => $lineItem['discount_amount'],
                'discount_invoiced' => $lineItem['discount_invoiced'],
                'discount_percent' => $lineItem['discount_percent'],
                'free_shipping' => $lineItem['free_shipping'],
                'discount_tax_compensation_amount' => $lineItem['discount_tax_compensation_amount'],
                'discount_tax_compensation_invoiced' => $lineItem['discount_tax_compensation_invoiced'],
                'is_qty_decimal' => $lineItem['is_qty_decimal'],
                'name' => $lineItem['name'],
                'no_discount' => $lineItem['no_discount'],

                'original_price' => $lineItem['original_price'],
                'price' => $lineItem['price'],
                'price_incl_tax' => $lineItem['price_incl_tax'],
                'product_type' => $lineItem['product_type'],
                'qty_canceled' => $lineItem['qty_canceled'],
                'qty_invoiced' => $lineItem['qty_invoiced'],
                'qty_ordered' => $lineItem['qty_ordered'],
                'qty_refunded' => $lineItem['qty_refunded'],
                'qty_shipped' => $lineItem['qty_shipped'],
                'row_invoiced' => $lineItem['row_invoiced'],
                'row_total' => $lineItem['row_total'],
                'row_total_incl_tax' => $lineItem['row_total_incl_tax'],
                'row_weight' => $lineItem['row_weight'],
                'tax_amount' => $lineItem['tax_amount'],
                'tax_invoiced' => $lineItem['tax_invoiced'],
                'tax_percent' => $lineItem['tax_percent'],
                'm_updated_at' => $lineItem['updated_at'],
                'weight' => $lineItem['weight'],
                'product_option' => json_encode($lineItem['product_option']),
                'extension_attributes' => json_encode($lineItem['extension_attributes']),
            ];

            HookFilterService::applyFilters('order_line_data_before_save', $dbVal, $this);

            $where = [
                'setupID' => $setupID,
                'order_id' => $lineItem['order_id'],
                'store_id' => $lineItem['store_id'],
                'item_id' => $lineItem['item_id'],
                'product_id' => $lineItem['product_id'],
            ];

            $results[] = OrderLines::updateOrCreate($where, $dbVal);
        }

        return $results;
    }

    public function saveBillingAddress($setupID, $order)
    {

        $address = $order['billing_address'];

        $dbVal = [
            'setupID' => $setupID,
            'entity_id' => $address['entity_id'],
            'address_type' => $address['address_type'],
            'city' => $address['city'],
            'country_id' => $address['country_id'],
            'customer_address_id' => $address['customer_address_id'],
            'email' => $address['email'],
            'firstname' => $address['firstname'],
            'lastname' => $address['lastname'],
            'parent_id' => $address['parent_id'],
            'postcode' => $address['postcode'],
            'region' => $address['region'],
            'region_code' => $address['region_code'],
            'region_id' => $address['region_id'],
            'street' => json_encode($address['street']),
            'telephone' => $address['telephone'],
        ];

        HookFilterService::applyFilters('order_billing_address_data_before_save', $dbVal, $this);

        $where = [
            'setupID' => $setupID,
            'entity_id' => $address['entity_id']
        ];

        $result = OrderBillingAddress::updateOrCreate($where, $dbVal);

        return $result;
    }

    public function savePayment($setupID, $order)
    {

        $payment = $order['payment'];

        $dbVal = [
            'setupID' => $setupID,
            'entity_id' => $payment['entity_id'],
            'account_status' => $payment['account_status'],
            'additional_information' => json_encode($payment['additional_information']),
            'amount_ordered' => $payment['amount_ordered'],
            'amount_paid' => $payment['amount_paid'],
            'amount_refunded' => $payment['amount_refunded'],
            'base_amount_ordered' => $payment['base_amount_ordered'],
            'base_amount_paid' => $payment['base_amount_paid'],
            'base_amount_refunded' => $payment['base_amount_refunded'],
            'base_shipping_amount' => $payment['base_shipping_amount'],
            'base_shipping_captured' => $payment['base_shipping_captured'],
            'base_shipping_refunded' => $payment['base_shipping_refunded'],
            'cc_last4' => $payment['cc_last4'],
            'method' => $payment['method'],
            'parent_id' => $payment['parent_id'],
            'shipping_amount' => $payment['shipping_amount'],
            'shipping_captured' => $payment['shipping_captured'],
            'shipping_refunded' => $payment['shipping_refunded'],
        ];

        HookFilterService::applyFilters('order_payment_data_before_save', $dbVal, $this);

        $where = [
            'setupID' => $setupID,
            'entity_id' => $payment['entity_id']
        ];

        $result = OrderBillingAddress::updateOrCreate($where, $dbVal);

        return $result;
    }

    public function create($setupID, $data)
    {
        $accessToken = $this->getAccessToken($setupID);
        $endPoint = "/rest/V1/orders";

        $headers = [
            'Authorization' => "Bearer {$accessToken}", // Replace with valid token
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
        return $this->request('POST', $data, $headers, $endPoint, 'body');
    }

    public function get($setupID, $orderID)
    {
        $accessToken = $this->getAccessToken($setupID);
        $data = [];
        $endPoint = "/rest/V1/orders/{$orderID}";

        $headers = [
            'Authorization' => "Bearer {$accessToken}", // Replace with valid token
            'Accept' => 'application/json',
        ];
        return $this->request('GET', $data, $headers, $endPoint);
    }

    public function update($setupID, $orderID, $data)
    {
        $accessToken = $this->getAccessToken($setupID);
        $endPoint = "/rest/V1/orders/{$orderID}";

        $headers = [
            'Authorization' => "Bearer {$accessToken}", // Replace with valid token
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];

        return $this->request('PUT', $data, $headers, $endPoint, 'body');
    }

    public function delete($setupID, $orderID)
    {
        $accessToken = $this->getAccessToken($setupID);
        $data = [];
        $endPoint = "/rest/V1/orders/{$orderID}";

        $headers = [
            'Authorization' => "Bearer {$accessToken}", // Replace with valid token
            'Accept' => 'application/json',
        ];
        return $this->request('DELETE', $data, $headers, $endPoint);
    }
}
