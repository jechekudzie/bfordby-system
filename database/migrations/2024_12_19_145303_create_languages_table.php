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
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->string('name');
            $table->enum('proficiency_level', ['beginner', 'intermediate', 'advanced', 'native'])->default('beginner');
            $table->boolean('is_native')->default(false);
            $table->enum('speaking', ['poor', 'fair', 'good', 'excellent'])->nullable();
            $table->enum('writing', ['poor', 'fair', 'good', 'excellent'])->nullable();
            $table->enum('reading', ['poor', 'fair', 'good', 'excellent'])->nullable();
            $table->enum('listening', ['poor', 'fair', 'good', 'excellent'])->nullable();
            $table->timestamps();
            $table->softDeletes();

    
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('languages');
    }
};
