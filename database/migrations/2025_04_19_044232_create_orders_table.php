<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // User relation (nullable for guest orders)
            $table->foreignId('user_id')->nullable()
                  ->constrained()
                  ->onDelete('set null');

            // Customer information (for guests)
            $table->string('customer_name', 100)->nullable();
            $table->string('phone', 20);
            $table->text('address');

            // Order details
            $table->decimal('total_price', 10, 2);
            $table->string('payment_method')->default('cash');
            $table->dateTime('preferred_delivery_time')->nullable();
            $table->text('special_instructions')->nullable();

            // Status
            $table->string('status')->default('pending');

            $table->timestamps();

            // Indexes
            $table->index('status');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
