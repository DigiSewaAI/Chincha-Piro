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
        Schema::table('order_items', function (Blueprint $table) {
            // Check if the 'menu_id' column does not already exist
            if (!Schema::hasColumn('order_items', 'menu_id')) {
                $table->foreignId('menu_id')->constrained()->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Drop the foreign key first if it exists
            if (Schema::hasColumn('order_items', 'menu_id')) {
                $table->dropForeign(['menu_id']);
                $table->dropColumn('menu_id');
            }
        });
    }
};
