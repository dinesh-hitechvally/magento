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
                $table->bigInteger('id');
                $table->string('id_str');
                $table->integer('company_id');
                $table->string('status');
                $table->string('notes');
                $table->integer('guests')->nullable(); // Last name
                $table->integer('site_id');
                $table->integer('register_id');
                $table->double('total');
                $table->double('total_tax')->nullable();
                $table->double('paid');
                $table->double('tips')->nullable();
                $table->boolean('deleted');
                $table->string('order_type')->nullable();

                $table->integer('customer_id')->nullable();
                $table->string('customer_first_name')->nullable();
                $table->string('customer_last_name')->nullable();
                $table->string('customer_email')->nullable();
                $table->string('customer_image')->nullable();

                $table->json('options')->nullable();
                $table->double('price_variation')->nullable();
                $table->double('price_fixed_variation')->nullable();
                $table->string('callback_uri')->nullable();
                $table->json('lock')->nullable();
                $table->integer('staff_member_id')->nullable();
                $table->string('group_id')->nullable();
                $table->dateTime('placed_at')->nullable();
                $table->dateTime('fulfil_at')->nullable();

                $table->dateTime('ls_created_at')->nullable(); // Assuming it's a timestamp
                $table->dateTime('ls_updated_at')->nullable(); // Assuming it's a timestamp

                $table->tinyInteger('detail_pending')->default(1); // Assuming it's a timestamp
                $table->tinyInteger('isRedeemedTransection')->default(0);
                
                $table->timestamps(); // Created at and updated at

                $table->index('id');
                $table->index('company_id');
                $table->index('status');
                $table->index('site_id');
                $table->index('deleted');
                $table->index('detail_pending');
                $table->index('isRedeemedTransection');

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
