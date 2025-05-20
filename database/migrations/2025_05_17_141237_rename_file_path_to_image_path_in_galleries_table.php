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
        $table->renameColumn('file_path', 'image_path');
    });
}

public function down()
{
    Schema::table('galleries', function (Blueprint $table) {
        $table->renameColumn('image_path', 'file_path');
    });
}

};
