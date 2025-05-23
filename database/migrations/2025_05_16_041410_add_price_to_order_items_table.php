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
        if (!Schema::hasColumn('order_items', 'unit_price')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->decimal('unit_price', 10, 2)->after('dish_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('order_items', 'unit_price')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->dropColumn('unit_price');
            });
        }
    }
};
