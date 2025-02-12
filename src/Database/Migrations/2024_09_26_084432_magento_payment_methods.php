<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    private $tableName = 'payment_methods';
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::connection(config('magento.connection'))->hasTable($this->tableName) ){

            Schema::connection(config('magento.connection'))->create($this->tableName, function (Blueprint $table) {
                $table->id('paymentID'); // Auto-incrementing ID
                $table->integer('number');
                $table->integer('method_id');
                $table->string('method_name');
                $table->decimal('amount', 4);
                $table->decimal('tip', 4);
                $table->dateTime('ls_created_at');
                $table->json('ref')->nullable();
                $table->decimal('credit_card_surcharge');
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
