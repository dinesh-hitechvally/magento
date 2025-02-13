<?php

namespace Dinesh\Magento\App\Models;

use Illuminate\Database\Eloquent\Model;

class Logs extends Model
{
    //
    protected $table = '';
    protected $connection = '';

    protected $primaryKey = 'logID';

    // Specify fillable attributes if needed
    protected $guarded = []; 
    
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        // Set the table name dynamically from the config
        $this->table = config('magento.models.magento.MAGENTO_LOGS');
        $this->connection = config('magento.connection', 'mysql');
    }
}
