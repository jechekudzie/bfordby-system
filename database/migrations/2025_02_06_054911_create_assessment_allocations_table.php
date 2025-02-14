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
            $table->unsignedBigInteger('enrollment_code_id');
            $table->unsignedBigInteger('semester_id');
            $table->date('due_date')->nullable();
            $table->enum('status', ['pending', 'open', 'closed'])->default('pending');
            $table->text('content')->nullable();
            $table->string('file_path')->nullable();
            $table->enum('submission_type', ['online', 'upload', 'in-class', 'group']);
            $table->boolean('is_timed')->default(false);
            $table->integer('duration_minutes')->nullable();
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
