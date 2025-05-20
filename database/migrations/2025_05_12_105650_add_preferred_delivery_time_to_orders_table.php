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
    if (!Schema::hasColumn('orders', 'preferred_delivery_time')) {
        Schema::table('orders', function (Blueprint $table) {
            $table->dateTime('preferred_delivery_time')->nullable()->after('status');
        });
    }
}

public function down(): void
{
    if (Schema::hasColumn('orders', 'preferred_delivery_time')) {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('preferred_delivery_time');
        });
    }
}

};
