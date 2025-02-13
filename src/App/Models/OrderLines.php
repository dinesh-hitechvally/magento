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
    protected $guarded = []; 

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
