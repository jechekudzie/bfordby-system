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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('title_id')->nullable();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('student_number')->unique()->nullable();
            $table->date('date_of_birth')->nullable();
            $table->unsignedBigInteger('gender_id')->nullable();
            $table->date('enrollment_date')->nullable();
            $table->enum('status', ['pending', 'active', 'graduated', 'inactive', 'withdrawn']);
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
        Schema::dropIfExists('students');
    }
};
