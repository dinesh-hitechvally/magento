<?php

namespace Dinesh\Magento\App\Models;

use Illuminate\Database\Eloquent\Model;

class Companies extends Model
{
    //
    protected $table = '';
    protected $connection = '';
    protected $primaryKey = 'companyID';
    protected $fillable = [
        'id',
        'name',
        'shipping_address',
        'postal_address_id',
        'postal_address_lines',
        'postal_address_city',
        'postal_address_zone',
        'postal_address_postal_code',
        'postal_address_country',
        'addresses_count',
        'business_number',
        'contact_staff_member_id',
        'contact_staff_member_first_name',
        'contact_staff_member_last_name',
        'contact_staff_member_email',
        'tax_inclusive_prices',
        'image',
        'website',
        'currency',
        'timezone_name',
        'timezone_offset',
        'sites_count',
        'sites_updated_at',
        'registers_count',
        'registers_limit',
        'registers_updated_at',
        'ls_created_at',
        'ls_updated_at',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        // Set the table name dynamically from the config
        $this->table = config('magento.models.magento.MAGENTO_COMPANIES');
        $this->connection = config('magento.connection', 'mysql');
    }
}
