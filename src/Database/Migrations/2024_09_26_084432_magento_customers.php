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
                $table->integer('id')->nullable();
                $table->integer('company_id');
                $table->string('first_name')->nullable(); // First name
                $table->string('last_name')->nullable(); // Last name
                $table->string('email')->nullable(); // Email
                $table->string('primary_email_address')->nullable(); // Primary email address
                $table->text('primary_address')->nullable(); // Primary address
                $table->string('primary_city')->nullable(); // City
                $table->string('primary_state')->nullable(); // State
                $table->string('primary_postal_code')->nullable(); // Postal code
                $table->string('primary_country')->nullable(); // Country
                $table->text('shipping_address')->nullable(); // Primary address
                $table->string('shipping_city')->nullable(); // City
                $table->string('shipping_state')->nullable(); // State
                $table->string('shipping_postal_code')->nullable(); // Postal code
                $table->string('shipping_country')->nullable(); // Country
                $table->string('phone')->nullable(); // Phone number
                $table->json('tags')->nullable(); // Tags (as JSON)
                $table->string('reference_id')->nullable(); // Reference ID
                $table->string('image')->nullable(); // Image URL
                $table->boolean('accepts_marketing')->default(false); // Accepts marketing
                $table->dateTime('ls_created_at')->nullable(); // Assuming it's a timestamp
                $table->dateTime('ls_updated_at')->nullable(); // Assuming it's a timestamp

                $table->date('dob')->nullable();
                $table->tinyInteger('isDeleted')->default(0);

                $table->tinyInteger('lightSpeedPending')->default(0);
                $table->tinyInteger('lightSpeedPendingTags')->default(0);

                $table->boolean('detail_pending')->default(1);
                $table->boolean('synccare_status')->nullable();
                $table->boolean('synccare_internal_id')->nullable();
                $table->string('synccare_message')->nullable();
                $table->string('litecard_member_id', 20)->nullable();

                $table->tinyInteger('liteCardPending')->default(1);
                $table->tinyInteger('liteCardPointPending')->default(0);
                $table->dateTime('lightCardPointPendingUpdateDate')->nullable();

                $table->timestamps(); // Created at and updated at

                $table->index('id');
                $table->index('company_id');
                $table->index('created_at');
                $table->index('updated_at');
                $table->index('liteCardPending');
                $table->index('liteCardPointPending');
                $table->index('lightSpeedPending');
                $table->index('lightSpeedPendingTags');
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
