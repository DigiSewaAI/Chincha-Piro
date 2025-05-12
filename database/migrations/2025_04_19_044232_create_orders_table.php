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
            $table->id(); // Primary Key

            // User Relation (Nullable for guest orders)
            $table->foreignId('user_id')->nullable()
                  ->constrained()
                  ->onDelete('set null'); // Guest orders can exist without user

            // Dish Relation
            $table->foreignId('dish_id')
                  ->constrained()
                  ->onDelete('cascade'); // Order deleted when dish is deleted

            // Order Details
            $table->unsignedInteger('quantity')->default(1); // Minimum 1
            $table->decimal('total_price', 10, 2); // Always set, no nullable
            $table->string('customer_name', 100)->nullable(); // For guests
            $table->string('phone', 20); // Required field
            $table->text('address'); // Required field
            $table->text('special_instructions')->nullable(); // Optional

            // Status & Timestamps
            $table->string('status')->default('pending'); // Flexible status handling
            $table->timestamps();

            // Indexes for faster queries
            $table->index('status');
            $table->index('created_at');
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
