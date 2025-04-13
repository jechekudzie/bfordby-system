<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sick_notes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->date('issue_date');
            $table->date('valid_from');
            $table->date('valid_until');
            $table->string('diagnosis')->nullable();
            $table->text('notes')->nullable();
            $table->string('issuing_doctor')->nullable();
            $table->string('medical_facility')->nullable();
            $table->string('document_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sick_notes');
    }
}; 