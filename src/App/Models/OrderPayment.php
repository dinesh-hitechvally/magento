<?php

namespace Dinesh\Magento\App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderPayment extends Model
{

    protected $table = '';
    protected $connection = '';

    protected $primaryKey = 'paymentID';
    protected $guarded = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        // Set the table name dynamically from the config
        $this->table = config('magento.models.magento.MAGENTO_ORDER_PAYMENT');
        $this->connection = config('magento.connection', 'mysql');
    }

}
