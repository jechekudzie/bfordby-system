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
        Schema::create('student_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('semester_id');
            $table->decimal('amount_invoiced', 10, 2);
            $table->decimal('amount_paid', 10, 2);
            $table->decimal('amount_balance', 10, 2);
            $table->date('payment_date');
            $table->date('due_date');
            $table->string('payment_method'); // cash, bank transfer, check, etc.
            $table->string('reference_number')->nullable();
            $table->string('description');
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
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
        Schema::dropIfExists('student_payments');
    }
}; 