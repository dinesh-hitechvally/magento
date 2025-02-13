<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    private $tableName = 'orders';

    /**
     * Run the migrations.
     */
    public function up(): void
    {

        if (!Schema::connection(config('magento.connection'))->hasTable($this->tableName)) {
        
            Schema::connection(config('magento.connection'))->create($this->tableName, function (Blueprint $table) {

                $table->id('orderID'); // Auto-incrementing ID
                $table->integer('setupID');
                $table->integer('entity_id');
                $table->integer('applied_rule_ids')->nullable();
                $table->string('base_currency_code');
                $table->decimal('base_discount_amount');
                $table->decimal('base_discount_invoiced')->nullable();
                $table->decimal('base_grand_total'); // Last name
                $table->decimal('base_discount_tax_compensation_amount');
                $table->decimal('base_discount_tax_compensation_invoiced')->nullable();
                $table->decimal('base_shipping_amount');
                $table->decimal('base_shipping_discount_amount');
                $table->decimal('base_shipping_discount_tax_compensation_amnt');
                $table->decimal('base_shipping_incl_tax');
                $table->decimal('base_shipping_invoiced')->nullable();
                $table->decimal('base_shipping_tax_amount');
                $table->decimal('base_subtotal');
                $table->decimal('base_subtotal_incl_tax');
                $table->decimal('base_subtotal_invoiced')->nullable();
                $table->decimal('base_tax_amount');
                $table->decimal('base_tax_invoiced')->nullable();
                $table->decimal('base_total_due');
                $table->decimal('base_total_invoiced')->nullable();
                $table->decimal('base_total_invoiced_cost')->nullable();
                $table->decimal('base_total_paid')->nullable();
                $table->decimal('base_to_global_rate');
                $table->decimal('base_to_order_rate');
                $table->integer('billing_address_id');
                $table->dateTime('m_created_at');

                $table->dateTime('customer_dob')->nullable();
                $table->string('customer_email');
                $table->string('customer_firstname');
                $table->tinyInteger('customer_gender')->nullable();
                $table->integer('customer_group_id');
                $table->bigInteger('customer_id')->nullable();
                $table->tinyInteger('customer_is_guest');
                $table->string('customer_lastname');
                $table->tinyInteger('customer_note_notify');

                $table->decimal('discount_amount');
                $table->decimal('discount_invoiced')->nullable();
                $table->string('global_currency_code');
                $table->decimal('grand_total');
                $table->decimal('discount_tax_compensation_amount');
                $table->decimal('discount_tax_compensation_invoiced')->nullable();
                $table->string('increment_id');
                $table->tinyInteger('is_virtual');
                $table->string('order_currency_code');
                $table->string('protect_code');
                $table->bigInteger('quote_id');

                $table->decimal('shipping_amount');
                $table->text('shipping_description');
                $table->decimal('shipping_discount_amount');
                $table->decimal('shipping_discount_tax_compensation_amount');
                $table->decimal('shipping_incl_tax');
                $table->decimal('shipping_invoiced')->nullable();
                $table->decimal('shipping_tax_amount');

                $table->string('state');
                $table->string('status');
                $table->string('store_currency_code');
                $table->decimal('store_id');
                $table->string('store_name');
                $table->decimal('store_to_base_rate');
                $table->decimal('store_to_order_rate');
                $table->decimal('subtotal');
                $table->decimal('subtotal_incl_tax');
                $table->decimal('subtotal_invoiced')->nullable();
                $table->decimal('tax_amount');
                $table->decimal('tax_invoiced')->nullable();
                $table->decimal('total_due');
                $table->decimal('total_invoiced')->nullable();
                $table->integer('total_item_count');
                $table->decimal('total_paid')->nullable();
                $table->integer('total_qty_ordered');
                $table->dateTime('m_updated_at');
                $table->decimal('weight');
                
                $table->timestamps(); // Created at and updated at

                $table->index('setupID');

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
