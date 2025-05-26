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
    Schema::create('gallery_items', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('description')->nullable();
        $table->enum('type', ['photo', 'video', 'external_video']);
        $table->string('image_path')->nullable(); // photo type
        $table->string('video_url')->nullable();  // video or external
        $table->boolean('is_featured')->default(false);
        $table->boolean('status')->default(true);
        $table->timestamps();
    });
}

};
