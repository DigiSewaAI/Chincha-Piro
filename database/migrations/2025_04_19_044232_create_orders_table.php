<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // User सम्बन्ध (गेस्ट अर्डरका लागि nullable)
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->onDelete('set null');

            // ग्राहक जानकारी
            $table->string('customer_name', 100);
            $table->string('phone', 20);
            $table->text('address');

            // अर्डर विवरण
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->string('payment_method')->default('cash');
            $table->string('payment_status')->default('unpaid');
            $table->dateTime('preferred_delivery_time');

            // स्टेटस प्रबन्धन
            $table->enum('status', [
                'pending',
                'confirmed',
                'preparing',
                'ready_for_delivery',
                'delivered',
                'cancelled'
            ])->default('pending');

            $table->text('special_instructions')->nullable();
            $table->timestamps();

            // इन्डेक्स
            $table->index(['status', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
