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
        Schema::create('academic_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->string('institution_name');
            $table->unsignedBigInteger('qualification_level_id');
            $table->string('program_name');
            $table->date('start_date');
            $table->date('completion_date');
            $table->string('grade_achieved')->nullable();
            $table->string('certificate_number')->nullable();
            $table->string('certificate_path')->nullable();
            $table->string('transcript_path')->nullable();
            $table->enum('status', ['completed', 'in_progress', 'incomplete', 'verified'])->default('completed');
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('academic_histories');
    }
};
