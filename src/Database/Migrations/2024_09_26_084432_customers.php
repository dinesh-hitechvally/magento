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
                
                $table->id('customerID'); 
                $table->bigInteger('setupID');
                $table->integer('id');
                $table->integer('group_id');
                $table->integer('default_billing')->nullable();
                $table->integer('default_shipping')->nullable();
                $table->dateTime('m_created_at'); 
                $table->dateTime('m_updated_at'); 
                $table->string('created_in'); 
                $table->date('dob')->nullable();
                $table->string('email'); 
                $table->string('firstname');
                $table->string('lastname'); 
                $table->integer('gender')->nullable();
                $table->integer('store_id'); 
                $table->integer('website_id'); 
                $table->json('addresses'); 
                $table->integer('disable_auto_group_change'); 
                $table->json('extension_attributes'); 

                /*
                 * Timestamps
                 */
                $table->timestamps();

                /*
                 * Index
                 */
                $table->index('setupID');
                $table->index('id');

                /*
                 * Foreign Keys
                 */
                $table->foreign('setupID')
                    ->references('setupID') // Reference to the 'setupID' column in the 'setup' table
                    ->on('setup') // The related table
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
