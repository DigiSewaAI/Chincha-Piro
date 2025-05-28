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
        $table->renameColumn('media_path', 'video_url');
    });
}

public function down()
{
    Schema::table('galleries', function (Blueprint $table) {
        $table->renameColumn('video_url', 'media_path');
    });
}
};
