<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    private $tableName = 'order_lines';

    /**
     * Run the migrations.
     */
    public function up(): void
    {

        if (!Schema::connection(config('magento.connection'))->hasTable($this->tableName)) {
        
            Schema::connection(config('magento.connection'))->create($this->tableName, function (Blueprint $table) {

                $table->id('lineID');
                $table->bigInteger('setupID');
                $table->bigInteger('store_id');
                $table->bigInteger('item_id');
                $table->bigInteger('product_id');
                $table->string('sku');
                $table->decimal('amount_refunded');
                $table->bigInteger('applied_rule_ids');
                $table->decimal('base_amount_refunded');
                $table->decimal('base_discount_amount');
                $table->decimal('base_discount_invoiced');
                $table->decimal('base_discount_tax_compensation_amount');
                $table->decimal('base_discount_tax_compensation_invoiced');
                $table->decimal('base_original_price');
                $table->decimal('base_price');
                $table->decimal('base_price_incl_tax');
                $table->decimal('base_row_invoiced');
                $table->decimal('base_row_total');
                $table->decimal('base_row_total_incl_tax');
                $table->decimal('base_tax_amount');
                $table->decimal('base_tax_invoiced');
                $table->dateTime('m_created_at');
                $table->decimal('discount_amount');
                $table->decimal('discount_invoiced');
                $table->decimal('discount_percent');
                $table->tinyInteger('free_shipping');
                $table->decimal('discount_tax_compensation_amount');
                $table->decimal('discount_tax_compensation_invoiced');
                $table->tinyInteger('is_qty_decimal');
                $table->string('name');
                $table->tinyInteger('no_discount');
                $table->bigInteger('order_id');
                $table->decimal('original_price');
                $table->decimal('price');
                $table->decimal('price_incl_tax');
                $table->string('product_type');
                $table->tinyInteger('qty_canceled');
                $table->tinyInteger('qty_invoiced');
                $table->tinyInteger('qty_ordered');
                $table->tinyInteger('qty_refunded');
                $table->tinyInteger('qty_shipped');
                $table->decimal('row_invoiced');
                $table->decimal('row_total');
                $table->decimal('row_total_incl_tax');
                $table->decimal('row_weight');
                $table->decimal('tax_amount');
                $table->decimal('tax_invoiced');
                $table->decimal('tax_percent');
                $table->dateTime('m_updated_at');
                $table->decimal('weight');
                $table->json('product_option');
                $table->json('extension_attributes');

                /*
                 * Timestamps
                 */
                $table->timestamps(); // Created at and updated at

                /*
                 * Index
                 */
                $table->index('setupID');
                $table->index('order_id');
                $table->index('item_id');
                $table->index('store_id');
                $table->index('product_id');

                /*
                 * Foreign Keys
                 */
                $table->foreign('setupID')
                    ->references('setupID') // Reference to the 'setupID' column in the 'setup' table
                    ->on('setup') // The related table
                    ->onDelete('cascade'); // Action on deletion (optional, can be changed)

                $table->foreign('order_id')
                    ->references('entity_id') // Reference to the 'entity_id' column in the 'orders' table
                    ->on('orders') // The related table
                    ->onDelete('cascade'); // Action on deletion (optional, can be changed)

                $table->foreign('product_id')
                    ->references('id') // Reference to the 'id' column in the 'products' table
                    ->on('products') // The related table
                    ->onDelete('cascade'); // Action on deletion (optional, can be changed)

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
