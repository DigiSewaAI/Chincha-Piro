<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImageColumnToDishes extends Migration
{
    public function up(): void
    {
        Schema::table('dishes', function (Blueprint $table) {
            if (!Schema::hasColumn('dishes', 'image')) {
                $table->string('image')->nullable()->after('price');
            }
        });
    }

    public function down(): void
    {
        Schema::table('dishes', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }
}
