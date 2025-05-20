<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // 'unit_price' कलमको उपस्थिति जाँच गर्नुहोस्
            if (Schema::hasColumn('order_items', 'unit_price')) {
                // 'unit_price' पछि 'total' कलम थप्नुहोस्
                $table->decimal('total', 10, 2)
                      ->default(0.00)
                      ->after('unit_price');
            } else {
                // यदि 'unit_price' छैन भने Exception थ्रो गर्नुहोस्
                throw new \Exception("Column 'unit_price' does not exist in 'order_items' table.");
            }
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // 'total' कलम हटाउनुहोस् (यदि अवस्थित भएमा)
            if (Schema::hasColumn('order_items', 'total')) {
                $table->dropColumn('total');
            }
        });
    }
};
