<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    private $tableName = 'order_payment';
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::connection(config('magento.connection'))->hasTable($this->tableName) ){

            Schema::connection(config('magento.connection'))->create($this->tableName, function (Blueprint $table) {
                
                $table->id('paymentID');
                $table->integer('setupID');
                $table->integer('entity_id');
                $table->string('account_status')->nullable();
                $table->json('additional_information');
                $table->decimal('amount_ordered', 20, 4);
                $table->decimal('amount_paid', 20, 4);
                $table->decimal('amount_refunded', 20, 4);
                $table->decimal('base_amount_ordered', 20, 4);
                $table->decimal('base_amount_paid', 20, 4);
                $table->decimal('base_amount_refunded', 20, 4);
                $table->decimal('base_shipping_amount', 20, 4);
                $table->decimal('base_shipping_captured', 20, 4);
                $table->decimal('base_shipping_refunded', 20, 4);
                $table->string('cc_last4')->nullable();
                $table->string('method');
                $table->integer('parent_id');
                $table->decimal('shipping_amount', 20, 4);
                $table->integer('shipping_captured');
                $table->integer('shipping_refunded');

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
