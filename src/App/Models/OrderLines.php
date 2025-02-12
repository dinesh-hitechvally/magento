<?php

namespace Dinesh\Magento\App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class OrderLines extends Model
{
    //
    protected $table = '';
    protected $connection = '';
    protected $primaryKey = 'lineID';
    protected $fillable = [

        'number',
        'line_id',
        'product_id',
        'company_id',
        'site_id',
        'product_name',
        'product_sku',
        'quantity',
        'price_variation',
        'price_fixed_variation',
        'notes',
        'unit_price',
        'unit_tax',
        'line_total_ex_tax',
        'line_total_tax',
        'from_order_id',
        'from_order_id_str',
        'course_name',
        'course_ordinal',
        'course_status',

    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        // Set the table name dynamically from the config
        $this->table = config('magento.models.magento.MAGENTO_ORDER_LINES');
        $this->connection = config('magento.connection', 'mysql');
    }

    public function newQuery(): Builder
    {
        return parent::newQuery()
            ->leftJoin('magento_product_sites', function ($join) {
                $join->on('magento_product_sites.product_id', '=', 'magento_order_lines.product_id')
                    ->whereColumn('magento_product_sites.id', '=', 'magento_order_lines.site_id');
            })
            ->select([
                'magento_order_lines.*',
                'magento_product_sites.unit_price as item_price',
                DB::raw("(magento_order_lines.unit_price + magento_order_lines.unit_tax) * magento_order_lines.quantity as paid_amount"),
            ]);
    }

    public function productSites()
    {
        return $this->hasMany(ProductSites::class, 'product_id', 'product_id')
            ->whereColumn('magento_product_sites.id', 'magento_order_lines.site_id');
    }
}
