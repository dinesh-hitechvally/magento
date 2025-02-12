<?php

namespace Dinesh\Magento\App\Models;

use Illuminate\Database\Eloquent\Model;

class Websites extends Model
{
    //
    protected $table = '';
    protected $connection = '';
    protected $primaryKey = 'siteID';
    protected $guarded = []; // Allow mass assignment for all attributes

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        // Set the table name dynamically from the config
        $this->table = config('magento.models.magento.MAGENTO_WEBSITES');
        $this->connection = config('magento.connection', 'mysql');
    }
}

