<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('galleries', function (Blueprint $table) {
        if (!Schema::hasColumn('galleries', 'type')) {
            $table->string('type')->default('image');
        }

        if (!Schema::hasColumn('galleries', 'description')) {
            $table->text('description')->nullable();
        }

        // 'category' पहिल्यै छ भने दोहोर्याउनु पर्दैन
    });
}



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('galleries', function (Blueprint $table) {
            //
        });
    }
};
