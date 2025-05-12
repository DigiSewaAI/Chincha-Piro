<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('status_histories', function (Blueprint $table) {
            $table->id();
            $table->string('status'); // जस्तै: 'pending', 'completed'
            $table->foreignId('order_id')->constrained()->onDelete('cascade'); // Order मा सम्बन्ध
            $table->foreignId('changed_by')->constrained('users')->onDelete('cascade'); // User मा सम्बन्ध
            $table->text('notes')->nullable(); // अतिरिक्त टिप्पणी
            $table->timestamps();

            // अनुक्रमणीयता सुधार (Index थप्नुहोस्)
            $table->index(['order_id', 'changed_by', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('status_histories');
    }
};
