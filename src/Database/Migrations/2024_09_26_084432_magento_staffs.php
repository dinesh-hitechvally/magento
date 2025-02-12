<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    private $tableName = 'staffs';
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        if (!Schema::connection(config('magento.connection'))->hasTable($this->tableName)) {

            Schema::connection(config('magento.connection'))->create($this->tableName, function (Blueprint $table) {
                $table->id('staffID'); // Auto-incrementing ID
                $table->integer('id')->nullable();
                $table->integer('company_id');
                $table->string('first_name')->nullable(); // First name
                $table->string('last_name')->nullable(); // Last name
                $table->boolean('is_admin')->nullable(); // Last name
                $table->string('primary_email_address')->nullable(); // Email
                $table->json('email_addresses')->nullable(); // Primary email address
                $table->text('phone')->nullable(); // Primary address
                $table->string('mobile')->nullable(); // City
                $table->string('fax')->nullable(); // State
                $table->string('shipping_address')->nullable(); // Postal code
                $table->string('postal_address_id')->nullable(); // Country
                $table->text('postal_address_lines')->nullable(); // Primary address
                $table->string('postal_address_city')->nullable(); // City
                $table->string('postal_address_zone')->nullable(); // State
                $table->string('postal_address_code')->nullable(); // Postal code
                $table->string('postal_address_country')->nullable(); // Country
                $table->string('addresses_count')->nullable(); // Phone number
                $table->dateTime('addresses_updated_at')->nullable(); // Tags (as JSON)
                $table->string('sites_count')->nullable(); // Reference ID
                $table->dateTime('sites_updated_at')->nullable(); // Image URL
                $table->json('permissions')->nullable(); // Accepts marketing
                $table->string('image')->nullable(); // Assuming it's a timestamp
                $table->json('tags')->nullable(); // Assuming it's a timestamp
                $table->string('code')->nullable();
                $table->dateTime('ls_created_at')->nullable();
                $table->string('ls_updated_at')->nullable();

                $table->boolean('detail_pending')->default(1);

                $table->timestamps(); // Created at and updated at

                $table->index('id');
                $table->index('company_id');
                $table->index('detail_pending');
                
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
