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

}
