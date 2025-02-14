<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    private $tableName = 'order_billing_address';
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::connection(config('magento.connection'))->hasTable($this->tableName) ){

            Schema::connection(config('magento.connection'))->create($this->tableName, function (Blueprint $table) {
                
                $table->id('addressID'); // Auto-incrementing ID
                $table->integer('setupID');
                $table->integer('entity_id');
                $table->string('address_type');
                $table->string('city');
                $table->string('country_id');
                $table->integer('customer_address_id');
                $table->string('email');
                $table->string('firstname');
                $table->string('lastname');
                $table->integer('parent_id');
                $table->string('postcode');
                $table->string('region');
                $table->string('region_code');
                $table->integer('region_id');
                $table->json('street');
                $table->string('telephone');

                /*
                 * Timestamps
                 */
                $table->timestamps(); // Created at and updated at

                /*
                 * Index
                 */
                $table->index('setupID');
                $table->index('entity_id');
                $table->index('region_id');
                
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
