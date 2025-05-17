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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            // Order र Dishes सँगको सम्बन्ध
            $table->foreignId('order_id')
                  ->constrained()
                  ->onDelete('cascade'); // Order हटाइएको भए आइटम पनि हटाउनुहोस्

            $table->foreignId('dish_id')
                  ->constrained()
                  ->onDelete('cascade'); // Dish हटाइएको भए आइटम पनि हटाउनुहोस्

            // आइटम विवरण
            $table->integer('quantity')->unsigned()->default(1);
            $table->decimal('unit_price', 8, 2); // प्रति इकाइ मूल्य
            $table->decimal('total_price', 10, 2); // quantity × unit_price
            $table->text('note')->nullable(); // थप निर्देशन

            $table->timestamps();

            // प्रदर्शन सुधारका लागि इन्डेक्स
            $table->index('order_id');
            $table->index('dish_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
