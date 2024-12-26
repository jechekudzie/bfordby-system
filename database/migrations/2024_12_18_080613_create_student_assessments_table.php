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
        Schema::create('student_assessments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('assessment_id');
            $table->decimal('score', 5, 2)->nullable();
            $table->datetime('submitted_date')->nullable();
            $table->datetime('graded_date')->nullable();
            $table->text('remarks')->nullable();
            $table->enum('status', ['pending', 'submitted', 'late', 'graded', 'missed'])->default('pending');
            $table->string('slug')->unique();
            $table->timestamps();
            $table->softDeletes();

        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_assessments');
    }
}; 