<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    private $tableName = 'requests';
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::connection(config('magento.connection'))->hasTable($this->tableName) ){

            Schema::connection(config('magento.connection'))->create($this->tableName, function (Blueprint $table) {
                
                $table->id('requestID'); // Auto-incrementing ID
                $table->string('method');
                $table->string('url');
                $table->string('name');
                $table->integer('code');

                /*
                 * Timestamps
                 */
                $table->timestamps(); // Created at and updated at

                
            });

        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists($this->tableName);
    }
    
};
