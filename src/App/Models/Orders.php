<?php

namespace Dinesh\Magento\App\Models;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use \Modules\SynccareCustomer\Models\PointTransaction;
use Retailcare\SyncBridge\Http\Services\Filter\Traits\Filterable;
use Retailcare\SyncBridge\Http\Services\Filter\Traits\Sortable;

class Orders extends Model
{
    use Filterable;
    use Sortable;

    protected $table = '';
    protected $connection = '';

    protected $primaryKey = 'orderID';
    protected $fillable = [
        'id',
        'id_str',
        'company_id',
        'status',
        'guests',
        'notes',
        'site_id',
        'register_id',
        'staff_member_id',
        'total',
        'total_tax',
        'paid',
        'tips',
        'deleted',
        'order_type',
        'customer_id',
        'customer_first_name',
        'customer_last_name',
        'customer_email',
        'customer_image',
        'options',
        'price_variation',
        'price_fixed_variation',
        'callback_uri',
        'lock',
        'staff_member_id',
        'group_id',
        'placed_at',
        'fulfil_at',
        'ls_created_at',
        'ls_updated_at',
        'detail_pending',
        'isRedeemedTransection',
        'created_at',
        'updated_at',

    ];

    protected $filterable = [
        'status' => 'status',
        'site_id' => 'site_id',
        'customer_id' => 'customer_id',
        'ls_created_at' => 'magento_orders.ls_created_at',
    ];
    protected $sortable = [

        'id' => 'id',
        'order_type' => 'order_type',
        'status' => 'status',
        'total' => 'total',
        'site_id' => 'site_id',
        'staff_member_id' => 'staff_member_id',
        'customer_id' => 'customer_id',
        'ls_created_at' => 'magento_orders.ls_created_at',

    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        // Set the table name dynamically from the config
        $this->table = config('magento.models.magento.MAGENTO_ORDERS');
        $this->connection = config('magento.connection', 'mysql');
    }

    public function customer()
    {
        return $this->hasOne(Customers::class, 'id', 'customer_id');
    }

    public function lines()
    {
        return $this->hasMany(OrderLines::class, 'from_order_id', 'id')
            ->where('isDeleted', 0);
    }


    public function site()
    {
        return $this->hasOne(Sites::class, 'id', 'site_id');
    }

    public function staff()
    {
        return $this->hasOne(Staffs::class, 'id', 'staff_member_id');
    }

    public function payments()
    {
        return $this->hasMany(PaymentMethod::class, 'order_id', 'id');
    }

    public function pointTransections()
    {
        return $this->hasMany(PointTransaction::class, 'reference_id', 'id');
    }

    public function scopeFilterByDate($query, $filters)
    {
        if (isset($filters['ls_created_at']) && isset($filters['ls_created_at']['$between'])) {
            $dates = $filters['ls_created_at']['$between'];
            if (count($dates) == 2) {
                return $query->whereBetween('ls_created_at', [$dates[0], $dates[1]]);
            }
        }

        return $query;
    }

    public function getReportsAttribute()
    {

        // Retrieve all point transactions related to this order
        $lines = $this->lines;

        // Initialize the variables for the report
        $gainPoint = PointTransaction::where([
            ['reference_id', '=', $this->id],
            ['point', '>', 0]
        ])->sum('point');

        $redeemedPoint = PointTransaction::where([
            ['reference_id', '=', $this->id],
            ['point', '<', 0]
        ])->sum('point');

        $productRedeemptions = [];
        $text = "- Discounted during point redemptions.";
        foreach ($lines as $line) {

            if (strpos($line->notes, $text) !== false) {
                $productRedeemptions[] = $line->product_name;
            }
        }

        if ($this->customer_id > 0) {
            $current_point = getCustomerPoint($this->customer->synccare_internal_id);
        } else {
            $current_point = 0;
        }

        $orderDiscount = 0;
        if ($this->price_fixed_variation < 0) {
            $orderDiscount = $this->price_fixed_variation * (-1); //Remove negetive sign
        }

        $paidAmount = $this->total;
        $gainPoint = floor($gainPoint);
        $redeemedPoint = floor($redeemedPoint);
        $current_point = floor($current_point);

        $reportData = [
            'total_amount' => $paidAmount + $orderDiscount,
            'order_discount' => "{$orderDiscount}",
            'paid_amount' => "{$paidAmount}",
            'gain_point' => "{$gainPoint}",
            'redeemed_point' => "{$redeemedPoint}",
            'current_point' => "{$current_point}",
            'product_redeemptions' => array_unique($productRedeemptions),
        ];

        return $reportData;
    }
}
