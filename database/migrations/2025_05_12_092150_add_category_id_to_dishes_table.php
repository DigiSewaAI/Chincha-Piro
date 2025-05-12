<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1) Add category_id as nullable first
        Schema::table('dishes', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->nullable();
        });

        // 2) Ensure at least one category exists
        DB::table('categories')->insert([
            'name'        => 'Uncategorized',
            'description' => '',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        // 3) Update all existing dishes to point to that category
        $defaultCategoryId = DB::table('categories')
                              ->where('name', 'Uncategorized')
                              ->value('id');
        DB::table('dishes')->update(['category_id' => $defaultCategoryId]);

        // 4) Modify column to not nullable and add foreign key constraint
        Schema::table('dishes', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->nullable(false)->change();
            $table->foreign('category_id')
                  ->references('id')->on('categories')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dishes', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });
    }
};
