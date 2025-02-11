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
        Schema::create('assessment_allocations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('assessment_id');
            $table->unsignedBigInteger('enrollment_code_id'); // Instead of `enrollment_id`
            $table->unsignedBigInteger('semester_id');
            $table->enum('status', ['pending', 'submitted', 'graded'])->default('pending');
            $table->date('due_date')->nullable();
            $table->text('content')->nullable(); // For text-based assessments
            $table->string('file_path')->nullable(); // For file uploads
            $table->timestamps();
    
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_allocations');
    }
};
