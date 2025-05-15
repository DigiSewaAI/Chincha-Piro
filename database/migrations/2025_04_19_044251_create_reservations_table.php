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

            // Foreign key to users table with index
            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade')
                  ->comment('ग्राहकको पहिचान');

            // Reservation time with index
            $table->dateTime('reservation_time')
                  ->index()
                  ->comment('रिजर्भेसनको मिति र समय');

            // Number of guests with constraints
            $table->integer('guests')
                  ->default(2)
                  ->comment('व्यक्ति संख्या');

            // Contact number with validation
            $table->string('contact_number', 15)
                  ->comment('ग्राहकको सम्पर्क नम्बर');

            // Special request (nullable)
            $table->text('special_request')
                  ->nullable()
                  ->comment('विशेष अनुरोध');

            // Status with enum constraints
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])
                  ->default('pending')
                  ->comment('रिजर्भेसनको स्थिति');

            // Cancellation reason (nullable)
            $table->text('cancellation_reason')
                  ->nullable()
                  ->comment('रद्द गर्ने कारण');

            // Soft delete support
            $table->softDeletes()
                  ->comment('रिजर्भेसनको सफट डिलीट समय');

            // Timestamps
            $table->timestamps();

            // Add full-text index for search capability
            $table->fullText(['contact_number', 'special_request'], 'reservation_search_index');
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
