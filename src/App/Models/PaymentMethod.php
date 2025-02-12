<?php

namespace Dinesh\Magento\App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    // Specify the table name if it doesn't follow Laravel's convention
    protected $table = '';

    protected $connection = '';

    // If your primary key is not the default 'id', specify it
    protected $primaryKey = 'paymentID';

      // Specify the attributes that are mass assignable
    protected $fillable = [
        'number',
        'order_id',
        'company_id',
        'site_id',
        'method_id',
        'method_name',
        'amount',
        'tip',
        'ls_created_at',
        'ref',
        'credit_card_surcharge',
    ];

    public function __construct(array $attributes = []){

        parent::__construct($attributes);
        // Set the table name dynamically from the config
        $this->table = config('magento.models.magento.MAGENTO_PAYMENT_METHOD');
        $this->connection = config('magento.connection', 'mysql');
    }

}
