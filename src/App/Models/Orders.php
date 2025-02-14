<?php

namespace Dinesh\Magento\App\Models;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{

    protected $table = '';
    protected $connection = '';

    protected $primaryKey = 'orderID';
    protected $guarded = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('magento.models.magento.MAGENTO_ORDERS');
        $this->connection = config('magento.connection', 'mysql');

    }

    public function customer()
    {
        return $this->hasOne(Customers::class, 'id', 'customer_id');
    }

    public function lines()
    {
        return $this->hasMany(OrderLines::class, 'order_id', 'entity_id');
    }

    public function payment(){
        return $this->hasOne(OrderPayment::class, 'entity_id', 'entity_id');
    }

    public function billingAddress()
    {
        return $this->hasOne(OrderBillingAddress::class, 'entity_id', 'entity_id');
    }

}
