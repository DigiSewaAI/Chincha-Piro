<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixGalleryStructure extends Migration
{
    public function up()
    {
        // galleries टेबलको लागि
        if (Schema::hasTable('galleries')) {
            Schema::table('galleries', function (Blueprint $table) {
                if (!Schema::hasColumn('galleries', 'image_path')) {
                    $table->string('image_path')->nullable()->after('id');
                }
                if (Schema::hasColumn('galleries', 'file_path')) {
                    $table->dropColumn('file_path');
                }
            });
        }

        // gallery_items टेबलको लागि
        if (!Schema::hasTable('gallery_items')) {
            Schema::create('gallery_items', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('description')->nullable();
                $table->enum('type', ['photo', 'video', 'external_video']);
                $table->string('image_path')->nullable();
                $table->string('video_url')->nullable();
                $table->boolean('is_featured')->default(false);
                $table->boolean('status')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('gallery_items');
    }
}
