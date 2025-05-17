<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateGalleriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('galleries', function (Blueprint $table) {
            if (!Schema::hasColumn('galleries', 'description')) {
                $table->text('description')->nullable();
            }

            if (!Schema::hasColumn('galleries', 'category')) {
                $table->string('category', 255)->default('general');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('galleries', function (Blueprint $table) {
            if (Schema::hasColumn('galleries', 'description')) {
                $table->dropColumn('description');
            }

            if (Schema::hasColumn('galleries', 'category')) {
                $table->dropColumn('category');
            }
        });
    }
}
