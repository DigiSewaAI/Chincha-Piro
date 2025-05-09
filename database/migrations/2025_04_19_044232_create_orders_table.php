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

            // User ID (Foreign Key)
            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade'); // User हटाएमा सँगै Order पनि हटाउनुहोस्

            // Dish ID (Existing Foreign Key)
            $table->foreignId('dish_id')
                  ->constrained()
                  ->onDelete('cascade');

            // Order Details
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('total_price', 10, 2)->nullable();
            $table->string('customer_name', 100)->nullable();
            $table->string('phone', 20)->nullable();
            $table->text('address')->nullable();
            $table->text('special_instructions')->nullable();

            // Status & Timestamps
            $table->enum('status', ['pending', 'processing', 'completed'])->default('pending');
            $table->timestamps();

            // Indexes
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
