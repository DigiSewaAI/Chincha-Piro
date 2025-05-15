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
        Schema::table('reservations', function (Blueprint $table) {
            // इन्डेक्सको अस्तित्व जाँच गर्नुहोस्
            if (!Schema::hasIndex('reservations', 'reservation_search_index')) {
                // फुल-टेक्स्ट इन्डेक्स थप्नुहोस्
                $table->fullText(['contact_number', 'special_request'], 'reservation_search_index');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            // इन्डेक्स अस्तित्वमा छ भने मात्र हटाउनुहोस्
            if (Schema::hasIndex('reservations', 'reservation_search_index')) {
                $table->dropFullText('reservation_search_index');
            }
        });
    }
};
