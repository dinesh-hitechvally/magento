<?php

namespace Dinesh\Magento\App\Models;

use Illuminate\Database\Eloquent\Model;

class Webhooks extends Model
{
    //
    protected $table = '';
    protected $connection = '';
    protected $primaryKey = 'webhookID';
    protected $fillable = [
        'id',
        'company_id',
        'topic',
        'address',
        'ls_created_at',
        'ls_updated_at',
        'isActive',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        // Set the table name dynamically from the config
        $this->table = config('magento.models.magento.MAGENTO_WEBHOOKS');
        $this->connection = config('magento.connection', 'mysql');
    }
}
