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
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->date('date_of_birth')->nullable();
            $table->unsignedBigInteger('gender_id');
            $table->date('enrollment_date')->nullable();
            $table->enum('status', ['active', 'graduated', 'inactive', 'withdrawn']);
            $table->string('slug')->unique();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('gender_id')->references('id')->on('genders');
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
