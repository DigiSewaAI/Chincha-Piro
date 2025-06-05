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
        Schema::table('menus', function (Blueprint $table) {
            // Stock फिल्ड थप्दा unsigned गर्नुहोस् (न्यूनतम मान 0 हुने)
            $table->integer('stock')->default(0)->unsigned()->after('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('menus', function (Blueprint $table) {
            // Stock फिल्ड हटाउनुहोस्
            $table->dropColumn('stock');
        });
    }
};
