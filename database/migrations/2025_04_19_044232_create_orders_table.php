<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // User सम्बन्ध
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->text('delivery_address')->nullable();
            $table->text('special_instructions')->nullable();
            $table->timestamp('preferred_delivery_time')->nullable();
            $table->string('payment_method')->default('cash');
            $table->enum('status', ['pending', 'confirmed', 'delivered', 'cancelled'])->default('pending');
            $table->decimal('total_price', 10, 2)->default(0);
            $table->boolean('is_paid')->default(false);
            $table->boolean('is_public')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
