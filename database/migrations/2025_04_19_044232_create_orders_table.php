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
            $table->foreignId('dish_id')->constrained()->onDelete('cascade'); // Dishes सँग सम्बन्ध
            $table->integer('quantity')->unsigned()->default(1);
            $table->string('customer_name', 100);
            $table->string('phone', 15);
            $table->text('address');
            $table->text('special_instructions')->nullable();
            $table->enum('status', ['pending', 'processing', 'completed'])->default('pending');
            $table->timestamps();

            // अनुकूलण सूचकांक (Optional)
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // फरिन की हटाउने
            $table->dropForeign(['dish_id']);
            // सूचकांक हटाउने
            $table->dropIndex(['status']);
            $table->dropIndex(['created_at']);
        });

        Schema::dropIfExists('orders');
    }
};
