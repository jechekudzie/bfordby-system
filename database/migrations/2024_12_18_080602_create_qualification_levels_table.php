<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\QualificationLevel;

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
            $table->string('description')->nullable();
            $table->string('slug')->unique();
            $table->timestamps();
            $table->softDeletes();
        });

        // Create qualification levels using the model
        $qualificationLevels = [
            [
                'name' => 'Certificate',
                'description' => 'Certificate level qualification'
            ],
            [
                'name' => 'Diploma',
                'description' => 'Diploma level qualification'
            ],
            [
                'name' => 'Advanced Diploma',
                'description' => 'Advanced Diploma level qualification'
            ],
            [
                'name' => "Bachelor's Degree",
                'description' => 'Undergraduate degree level qualification'
            ],
            [
                'name' => "Master's Degree",
                'description' => 'Postgraduate degree level qualification'
            ],
            [
                'name' => 'Doctorate',
                'description' => 'Doctoral level qualification'
            ],
            [
                'name' => 'Professional Certification',
                'description' => 'Industry-specific professional certification'
            ]
        ];

        foreach ($qualificationLevels as $level) {
            QualificationLevel::create($level);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qualification_levels');
    }
};
