<?php

namespace Dinesh\Magento\App\Models;

use Illuminate\Database\Eloquent\Model;

class Cursors extends Model
{
    //
    protected $table = '';
    protected $connection = '';

    protected $primaryKey = 'cursorID';

    protected $fillable = [
        'siteID',
        'cursor_type',
        'cursor_url',
        'created_at',
        'updated_at',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        // Set the table name dynamically from the config
        $this->table = config('magento.models.magento.MAGENTO_CURSORS');
        $this->connection = config('magento.connection', 'mysql');
    }
}
