<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeletedAtToMenusTable extends Migration
{
    public function up()
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->softDeletes(); // यो रेखा थप्नुहोस्
        });
    }

    public function down()
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->dropColumn('deleted_at'); // यो रेखा थप्नुहोस्
        });
    }
}
