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

        Schema::create('assessment_allocation_questions', function (Blueprint $table) {
            $table->id(); 
            $table->unsignedBigInteger('assessment_allocation_id');
            $table->text('question_text')->nullable();
            $table->enum('question_type', ['text', 'multiple_choice']);
            $table->integer('weight')->default(1);
            $table->integer('order')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_allocation_questions');
    }
};
