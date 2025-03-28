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
        Schema::create('assessment_allocation_submissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('assessment_allocation_id');
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('group_id')->nullable();
            $table->text('content')->nullable(); // For text submissions
            $table->json('answers')->nullable(); // For online/timed assessment answers
            $table->string('file_path')->nullable(); // For file uploads
            $table->timestamp('start_time')->nullable(); // For timed assessments
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('graded_at')->nullable();
            $table->decimal('grade', 5, 2)->nullable();
            $table->text('feedback')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'submitted', 'graded'])->default('pending');
            $table->timestamps();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_allocation_submissions');
    }
};
