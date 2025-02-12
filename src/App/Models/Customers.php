<?php

namespace Dinesh\Magento\App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Retailcare\SyncBridge\Http\Services\Filter\Traits\Filterable;
use Retailcare\SyncBridge\Http\Services\Filter\Traits\Sortable;

class Customers extends Model
{
    
    use Filterable;
    use Sortable;

    protected $table = '';
    protected $connection = '';

    protected $primaryKey = 'customerID';

    protected $fillable = [
        'id',
        'company_id',
        'first_name',
        'last_name',
        'email',
        'primary_email_address',
        'primary_address',
        'primary_city',
        'primary_state',
        'primary_postal_code',
        'primary_country',
        'phone',
        'tags',
        'reference_id',
        'image',
        'accepts_marketing',
        'ls_created_at',
        'ls_updated_at',

        'dob',
        'isDeleted',

        'lightSpeedPending',
        'lightSpeedPendingTags',

        'litecard_member_id',
        'liteCardPending',
        'liteCardPointPending',
        'lightCardPointPendingUpdateDate',
        
        'detail_pending',
        'synccare_status',
        'synccare_internal_id',
        'synccare_message',
        
        'created_at',
        'updated_at',

        'isLoyalty',
        'klaviyoListProfileCreatePending',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        // Set the table name dynamically from the config
        $this->table = config('magento.models.magento.MAGENTO_CUSTOMERS');
        $this->connection = config('magento.connection', 'mysql');
    }
    
    
}
