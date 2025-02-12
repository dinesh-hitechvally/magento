<?php

namespace Dinesh\Magento\App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessTokens extends Model
{
    //
    protected $table = '';
    protected $connection = '';
    protected $primaryKey = 'tokenID';
    protected $fillable = [
        'siteID',
        'access_token',
        'created_at',
        'updated_at',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        // Set the table name dynamically from the config
        $this->table = config('magento.models.magento.MAGENTO_ACCESS_TOKENS');
        $this->connection = config('magento.connection', 'mysql');
    }
}
