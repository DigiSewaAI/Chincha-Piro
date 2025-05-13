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
            $table->string('title');         // फोटोको शीर्षक (जस्तै: "हाम्रा पकवानहरू")
            $table->string('category');      // श्रेणी (जस्तै: "पकवान", "रेस्टुरेन्ट")
            $table->string('image_path');    // इमेजको पाथ (जस्तै: "gallery/featured-1.jpg")
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
