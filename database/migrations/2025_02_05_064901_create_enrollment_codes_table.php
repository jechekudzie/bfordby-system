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
        Schema::create('enrollment_codes', function (Blueprint $table) {
            $table->id();
        $table->unsignedBigInteger('course_id');
        $table->unsignedBigInteger('study_mode_id');
        $table->integer('year'); // Year of enrollment
        $table->integer('current_number')->default(0); // Last assigned number
        $table->string('base_code')->unique(); // Example: "GAD" or "GADPT"
        $table->timestamps();

        $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
        $table->foreign('study_mode_id')->references('id')->on('study_modes')->onDelete('cascade');

        $table->unique(['course_id', 'study_mode_id', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollment_codes');
    }
};
