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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();

            // Foreign key to users table
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Reservation time
            $table->dateTime('reservation_time');

            // Number of guests (default: 2)
            $table->integer('guests')->default(2);

            // Contact number
            $table->string('contact_number');

            // Special request (nullable)
            $table->text('special_request')->nullable();

            // Status: pending, confirmed, cancelled
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');

            // Timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
