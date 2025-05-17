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
            $table->string('title');           // आइटमको शीर्षक (उदाहरण: "हाम्रा पकवानहरू")
            $table->string('category');        // श्रेणी (उदाहरण: "पकवान", "रेस्टुरेन्ट")
            $table->enum('type', ['photo', 'video']); // फोटो वा भिडियो
            $table->text('file_path');         // फाइल पाथ (इमेज: "gallery/food-1.jpg", भिडियो: "https://youtu.be/... ")
            $table->text('description')->nullable();  // वैकल्पिक विवरण
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
