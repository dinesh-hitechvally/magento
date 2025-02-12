<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    private $tableName = 'webhooks';
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::connection(config('magento.connection'))->hasTable($this->tableName) ){

            Schema::connection(config('magento.connection'))->create($this->tableName, function (Blueprint $table) {
                $table->id('webhookID'); // Auto-incrementing ID
                $table->bigInteger('id');
                $table->integer('company_id');
                $table->string('topic');
                $table->string('address');
                $table->dateTime('ls_created_at')->nullable();
                $table->dateTime('ls_updated_at')->nullable();
                $table->boolean('isActive')->default(1);
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
