<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            // Foreign Keys
            $table->foreignId('order_id')
                  ->constrained()
                  ->onDelete('cascade');

            $table->foreignId('menu_id')
                  ->constrained('menus')
                  ->onDelete('cascade');

            // Item details
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_price', 10, 2);

            // JSON options
            $table->json('options')->nullable()->comment('विशेष विकल्पहरू');

            // ✅ Generated column (without `after`)
            $table->string('options_search', 255)
                  ->storedAs("json_unquote(json_extract(`options`, '$.keywords'))");

            $table->fullText('options_search');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
