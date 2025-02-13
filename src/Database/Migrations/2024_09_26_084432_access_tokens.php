<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    private $tableName = 'access_tokens';

    /**
     * Run the migrations.
     */
    public function up(): void
    {

        if (!Schema::connection(config('magento.connection'))->hasTable($this->tableName)) {

            Schema::connection(config('magento.connection'))->create($this->tableName, function (Blueprint $table) {

                $table->id('tokenID'); // Auto-incrementing ID
                $table->integer('setupID'); // Auto-incrementing ID
                $table->string('access_token');
                $table->dateTime('expire_at'); //Expire at

                /*
                 * Timestamps
                 */
                $table->timestamps();

                /*
                 * Index
                 */
                $table->index('setupID');

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
