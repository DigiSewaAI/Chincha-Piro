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
        // 'orders' टेबलमा 'total_price' कलम थप्नुहोस् (यदि अवस्थित छैन भने)
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'total_price')) {
                $table->decimal('total_price', 10, 2)->default(0.00)->after('user_id');
            }
        });

        // 'orders' टेबलबाट 'total' कलम हटाउनुहोस् (यदि अवस्थित छ भने)
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'total')) {
                $table->dropColumn('total');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 'total' कलम फेरि थप्नुहोस् (rollback मा)
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'total')) {
                $table->decimal('total', 10, 2)->default(0.00)->after('user_id');
            }
        });

        // 'total_price' कलम हटाउनुहोस् (rollback मा)
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'total_price')) {
                $table->dropColumn('total_price');
            }
        });
    }
};
