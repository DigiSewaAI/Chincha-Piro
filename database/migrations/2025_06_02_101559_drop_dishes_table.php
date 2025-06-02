<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::dropIfExists('dishes');
    }

    public function down(): void
    {
        Schema::create('dishes', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }
};
