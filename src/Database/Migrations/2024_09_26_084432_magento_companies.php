<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    private $tableName = 'companies';
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        if (!Schema::connection(config('magento.connection'))->hasTable($this->tableName)) {

            Schema::connection(config('magento.connection'))->create($this->tableName, function (Blueprint $table) {
                
                $table->id('companyID'); // Auto-incrementing ID
                $table->integer('id'); // Auto-incrementing ID
                $table->text('name');
                $table->text('shipping_address')->nullable(); // Shipping address

                $table->integer('postal_address_id');
                $table->json('postal_address_lines');
                $table->string('postal_address_city');
                $table->string('postal_address_zone');
                $table->integer('postal_address_postal_code');
                $table->string('postal_address_country');

                $table->integer('addresses_count');
                $table->integer('addresses_updated_at')->nullable();

                $table->string('business_number');

                $table->integer('contact_staff_member_id');
                $table->string('contact_staff_member_first_name'); // First name
                $table->string('contact_staff_member_last_name'); // Last name
                $table->string('contact_staff_member_email'); //Email

                $table->boolean('tax_inclusive_prices');

                $table->string('image');
                $table->string('website');
                $table->string('currency');

                $table->string('timezone_name');
                $table->string('timezone_offset');

                $table->string('sites_count');
                $table->dateTime('sites_updated_at')->nullable();

                $table->string('registers_count');
                $table->string('registers_limit');
                $table->dateTime('registers_updated_at')->nullable();
                
                $table->dateTime('ls_created_at')->nullable(); // Assuming it's a timestamp
                $table->dateTime('ls_updated_at')->nullable(); // Assuming it's a timestamp

                $table->timestamps(); // Created at and updated at

                $table->index('id');

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
