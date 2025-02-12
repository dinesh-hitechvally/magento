<?php

namespace Dinesh\Magento\App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Retailcare\SyncBridge\Http\Services\Filter\Traits\Filterable;
use Retailcare\SyncBridge\Http\Services\Filter\Traits\Sortable;

class Staffs extends Model
{

    use Filterable;
    use Sortable;

    protected $table = '';
    protected $connection = '';

    protected $primaryKey = 'staffID';

    protected $fillable = [
        'id',
        'company_id',
        'first_name',
        'last_name',
        'is_admin',
        'primary_email_address',
        'email_addresses',
        'phone',
        'mobile',
        'fax',
        'shipping_address',
        'postal_address_id',
        'postal_address_lines',
        'postal_address_city',
        'postal_address_zone',
        'postal_address_code',
        'postal_address_country',
        'addresses_count',
        'addresses_updated_at',
        'sites_count',
        'sites_updated_at',
        'permissions',
        'image',
        'tags',
        'code',
        'ls_created_at',
        'ls_updated_at',
        'detail_pending'
    ];


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        // Set the table name dynamically from the config
        $this->table = config('magento.models.magento.MAGENTO_STAFFS');
        $this->connection = config('magento.connection', 'mysql');
    }
}
