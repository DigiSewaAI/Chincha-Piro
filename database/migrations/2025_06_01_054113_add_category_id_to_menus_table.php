<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCategoryIdToMenusTable extends Migration
{
    public function up()
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('id')->constrained('categories');
        });
    }

    public function down()
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->dropForeignIdFor('category_id');
        });
    }
}
