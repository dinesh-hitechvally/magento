<?php

namespace Dinesh\Magento\App\Models;

use Illuminate\Database\Eloquent\Model;

class Setup extends Model
{
    //
    protected $table = '';
    protected $connection = '';
    protected $primaryKey = 'setupID';
    protected $guarded = []; // Allow mass assignment for all attributes

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        // Set the table name dynamically from the config
        $this->table = config('magento.models.magento.MAGENTO_SETUP');
        $this->connection = config('magento.connection', 'mysql');
    }
}

