<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    private $tableName = 'product_sites';
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::connection(config('magento.connection'))->hasTable($this->tableName) ){

            Schema::connection(config('magento.connection'))->create($this->tableName, function (Blueprint $table) {

                $table->id('siteProductID'); // Auto-incrementing ID
                $table->bigInteger('company_id');
                $table->bigInteger('product_id');
                $table->integer('id');
                $table->text('name');
                $table->decimal('unit_price');
                $table->decimal('unit_price_ex_tax');
                $table->decimal('cost_price');
                $table->decimal('cost_price_ex_tax');
                $table->string('is_inventory');
                $table->json('taxes'); 
                $table->json('cost_taxes');
                $table->string('availability_is_available');
                $table->string('availability_amount')->nullable();
                $table->timestamps(); // Created at and updated at

                $table->index('product_id');
                $table->index('id');
                
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
