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
        Schema::create('module_assessment_weights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained()->onDelete('cascade');
            $table->enum('assessment_type', ['assignment', 'test', 'exam', 'practical', 'theory']);
            $table->decimal('weight', 5, 2); // e.g., 10.00 for 10%
            $table->timestamps();
            
            // Ensure each type only has one weight per module
            $table->unique(['module_id', 'assessment_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('module_assessment_weights');
    }
};
