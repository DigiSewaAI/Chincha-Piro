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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();

            // For logged-in users
            $table->unsignedBigInteger('user_id')->nullable()->comment('User ID if logged in');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // For guest users
            $table->string('session_id')->nullable()->index()->comment('Session ID for guest users');

            $table->timestamps();

            // Optional: Prevent duplicate cart entries
            $table->unique(['user_id', 'session_id'], 'unique_cart_user_session');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
