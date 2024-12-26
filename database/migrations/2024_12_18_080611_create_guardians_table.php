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
        Schema::create('guardians', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('relationship');
            $table->text('contact_details')->nullable();
            $table->string('slug')->unique();
            $table->timestamps();
            $table->softDeletes();
        
            $table->foreign('student_id')->references('id')->on('students');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guardians');
    }
};
