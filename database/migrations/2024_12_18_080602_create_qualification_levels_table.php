<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('qualification_levels', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('code', 10)->unique();
            $table->string('description')->nullable();
            $table->integer('level_order')->unique(); // For sorting qualifications by level
            $table->timestamps();
        });

        // Insert default qualification levels
        DB::table('qualification_levels')->insert([
            [
                'name' => 'Secondary School',
                'code' => 'SECOND',
                'description' => 'Secondary School Education',
                'level_order' => 1
            ],
            [
                'name' => 'High School',
                'code' => 'HIGH',
                'description' => 'High School Education',
                'level_order' => 2
            ],
            [
                'name' => 'Certificate',
                'code' => 'CERT',
                'description' => 'Certificate Level',
                'level_order' => 3
            ],
            [
                'name' => 'Diploma',
                'code' => 'DIP',
                'description' => 'Diploma Level',
                'level_order' => 4
            ],
            [
                'name' => 'Advanced Diploma',
                'code' => 'ADIP',
                'description' => 'Advanced Diploma Level',
                'level_order' => 5
            ],
            [
                'name' => "Bachelor's Degree",
                'code' => 'BACH',
                'description' => 'Bachelor Degree Level',
                'level_order' => 6
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qualification_levels');
    }
};
