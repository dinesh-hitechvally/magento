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

    protected $guarded = []; 

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        // Set the table name dynamically from the config
        $this->table = config('magento.models.magento.MAGENTO_CUSTOMERS');
        $this->connection = config('magento.connection', 'mysql');
    }
    
    
}
