<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // order_items टेबल सिर्जना गर्नुहोस्
        Schema::create('order_items', function (Blueprint $table) {
            $table->id(); // प्राथमिक कुञ्चिका

            // फरेन की सम्बन्ध
            $table->foreignId('order_id')->constrained()->onDelete('cascade'); // order_id लाई orders टेबलसँग जोड्नुहोस्
            $table->foreignId('dish_id')->constrained()->onDelete('restrict'); // dish_id लाई dishes टेबलसँग जोड्नुहोस्

            // मूल्य र मात्रा
            $table->decimal('unit_price', 10, 2); // एकाइ मूल्य (यहाँै थप्नुहोस्)
            $table->unsignedInteger('quantity');  // मात्रा (ऋणात्मक मान अनुमति नदिने)
            $table->decimal('total_price', 10, 2); // कुल मूल्य (यहाँै थप्नुहोस्)

            // विकल्पहरू
            $table->json('options')->nullable()->comment('विशेष विकल्पहरू'); // JSON डाटा प्रकारमा भण्डारण गर्नुहोस्

            // टाइमस्ट्याम्प
            $table->timestamps();

            // इन्डेक्स
            $table->index(['order_id', 'dish_id']); // सामान्य इन्डेक्स
        });

        // options_search कलम थप्नुहोस् (Stored Generated Column)
        Schema::table('order_items', function (Blueprint $table) {
            $table->string('options_search', 255)
                  ->storedAs("options->>\"$.keywords\"") // JSON बाट keywords निकाल्नुहोस्
                  ->after('options'); // options कलम पछि स्थिति

            // Full-text index
            $table->fullText('options_search'); // पूर्ण-पाठ खोजको लागि इन्डेक्स
        });
    }

    public function down(): void
    {
        // order_items टेबल हटाउनुहोस्
        Schema::dropIfExists('order_items');
    }
};
