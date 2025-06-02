<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Drop foreign key if it exists
            if (Schema::hasColumn('order_items', 'dish_id')) {
                try {
                    $table->dropForeign(['dish_id']);
                } catch (\Exception $e) {
                    // Foreign key may not exist; ignore error
                }

                $table->dropColumn('dish_id');
            }

            // Add menu_id column only if it does not already exist
            if (!Schema::hasColumn('order_items', 'menu_id')) {
                $table->foreignId('menu_id')->constrained()->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            if (Schema::hasColumn('order_items', 'menu_id')) {
                $table->dropForeign(['menu_id']);
                $table->dropColumn('menu_id');
            }

            $table->foreignId('dish_id')->constrained()->onDelete('cascade');
        });
    }
};


