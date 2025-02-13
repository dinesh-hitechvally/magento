<?php

namespace Dinesh\Magento\App\Models;

use Illuminate\Database\Eloquent\Model;

class Requests extends Model
{
    
    protected $table = '';
    protected $connection = '';

    protected $primaryKey = 'requestID';
    protected $guarded = []; 

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        // Set the table name dynamically from the config
        $this->table = config('magento.models.magento.MAGENTO_REQUESTS');
        $this->connection = config('magento.connection', 'mysql');
    }
}
