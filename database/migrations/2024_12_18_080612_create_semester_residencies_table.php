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
        Schema::create('semester_residencies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('semester_id');
            $table->string('room_number');
            $table->string('block_name');
            $table->date('check_in_date');
            $table->date('check_out_date')->nullable();
            $table->enum('payment_status', ['pending', 'partial', 'paid'])->default('pending');
            $table->decimal('amount_paid', 10, 2)->default(0);
            $table->date('payment_date')->nullable();
            $table->text('damages')->nullable();
            $table->decimal('damage_charges', 10, 2)->default(0);
            $table->enum('status', ['active', 'checked_out', 'terminated'])->default('active');
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
        Schema::dropIfExists('semester_residencies');
    }
};
