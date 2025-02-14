<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    private $tableName = 'pagination';

    /**
     * Run the migrations.
     */
    public function up(): void
    {

        if (!Schema::connection(config('magento.connection'))->hasTable($this->tableName)) {
        
            Schema::connection(config('magento.connection'))->create($this->tableName, function (Blueprint $table) {

                $table->id('pageID');
                $table->integer('setupID');
                $table->string('endpoint');
                $table->string('page');

                /*
                 * Timestamps
                 */
                $table->timestamps(); // Created at and updated at

                /*
                 * Index
                 */
                $table->index('setupID');
                $table->index('endpoint');

                /*
                 * Foreign Keys
                 */
                /*
                $table->foreign('setupID')
                    ->references('setupID') // Reference to the 'setupID' column in the 'setup' table
                    ->on('setup') // The related table
                    ->onDelete('cascade'); // Action on deletion (optional, can be changed)
                */
                
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
