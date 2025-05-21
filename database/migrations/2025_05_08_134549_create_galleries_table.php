<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
      Schema::create('galleries', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->string('category');
    $table->text('description')->nullable();
    $table->enum('type', ['photo', 'video', 'local_video'])->default('photo');
    $table->string('image_path');
    $table->boolean('is_active')->default(true);  // यहाँ 'status' को सट्टामा 'is_active' प्रयोग गर्नुहोस्
    $table->boolean('featured')->default(false);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('galleries');
    }
};
