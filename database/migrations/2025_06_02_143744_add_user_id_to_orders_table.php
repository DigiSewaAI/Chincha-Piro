<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // ✅ Check to prevent duplicate column error
            if (!Schema::hasColumn('orders', 'user_id')) {
                $table->foreignId('user_id')
                      ->after('id')
                      ->constrained()
                      ->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // ✅ Drop foreign key safely if column exists
            if (Schema::hasColumn('orders', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
        });
    }
};
