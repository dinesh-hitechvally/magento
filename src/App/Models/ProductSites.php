<?php

namespace Dinesh\Magento\App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSites extends Model
{
    //
    protected $table = '';
    protected $connection = '';
    protected $primaryKey = 'siteProductID';
    protected $fillable = [
        'company_id',
        'product_id',
        'id',
        'name',
        'unit_price',
        'unit_price_ex_tax',
        'cost_price',
        'cost_price_ex_tax',
        'is_inventory',
        'taxes',
        'cost_taxes',
        'availability_is_available',
        'availability_amount',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        // Set the table name dynamically from the config
        $this->table = config('magento.models.magento.MAGENTO_PRODUCT_SITES');
        $this->connection = config('magento.connection', 'mysql');
    }

    /**
     * Define the inverse of the one-to-many relationship with Products.
     */
    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id', 'id');
    }
}
