<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessment_contribution_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Create assessment contribution structure
        Schema::create('module_assessment_structures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('assessment_contribution_type_id');
            $table->foreign('assessment_contribution_type_id', 'fk_assessment_contribution')
                  ->references('id')
                  ->on('assessment_contribution_types')
                  ->onDelete('cascade');
            $table->decimal('weight', 5, 2); // e.g., 25.00 for 25%
            $table->boolean('is_trimester_weight')->default(false); // To differentiate trimester weights
            $table->integer('trimester')->nullable(); // 1, 2, or 3
            $table->timestamps();
            
            // Ensure unique combination
            $table->unique(['module_id', 'assessment_contribution_type_id', 'trimester'], 'unique_module_assessment_structure');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('module_assessment_structures');
        Schema::dropIfExists('assessment_contribution_types');
    }
};
