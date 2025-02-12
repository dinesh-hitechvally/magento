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

                $table->id('lineID'); // Auto-incrementing ID

                /*
                 * Lightspeed Line Items Values
                 */
                $table->integer('number');
                $table->string('line_id'); // First name
                $table->bigInteger('product_id');
                $table->bigInteger('company_id');
                $table->integer('site_id');
                $table->string('product_name');
                $table->string('product_sku')->nullable();
                $table->integer('quantity');
                $table->decimal('price_variation', 10, 4);
                $table->decimal('price_fixed_variation', 10, 4);
                $table->string('notes');
                $table->double('unit_price');
                $table->double('unit_tax');
                $table->double('line_total_ex_tax');
                $table->double('line_total_tax');
                $table->bigInteger('from_order_id');
                $table->string('from_order_id_str');
                $table->string('course_name')->nullable();
                $table->integer('course_ordinal')->nullable();
                $table->string('course_status')->nullable();

                /*
                 * Timestamps
                 */
                $table->timestamps(); // Created at and updated at

                /*
                 * Index
                 */
                $table->index('line_id');
                $table->index('product_id');
                $table->index('product_sku');
                $table->index('from_order_id');

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
