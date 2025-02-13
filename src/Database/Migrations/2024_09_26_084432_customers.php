<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    private $tableName = 'customers';
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        if (!Schema::connection(config('magento.connection'))->hasTable($this->tableName)) {

            Schema::connection(config('magento.connection'))->create($this->tableName, function (Blueprint $table) {
                $table->id('customerID'); // Auto-incrementing ID
                $table->bigInteger('setupID');
                $table->integer('id');
                $table->integer('group_id');
                $table->integer('default_billing')->nullable();
                $table->integer('default_shipping')->nullable();
                $table->dateTime('m_created_at'); // Email
                $table->dateTime('m_updated_at'); // Primary email address
                $table->string('created_in'); // Primary address
                $table->date('dob')->nullable();
                $table->string('email'); // State
                $table->string('firstname'); // Postal code
                $table->string('lastname'); // Country
                $table->integer('gender'); // Primary address
                $table->integer('store_id'); // City
                $table->integer('website_id'); // State
                $table->json('addresses'); // Postal code
                $table->integer('disable_auto_group_change'); // Country
                $table->json('extension_attributes'); // Phone number

                $table->timestamps(); // Created at and updated at

                $table->index('id');
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
