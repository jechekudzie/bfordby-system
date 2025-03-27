<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module;
use App\Models\AssessmentContributionType;
use App\Models\ModuleAssessmentStructure;

class ModuleAssessmentStructureSeeder extends Seeder
{
    public function run(): void
    {
        // Get all modules
        $modules = Module::all();
        
        // Get contribution types
        $coursework = AssessmentContributionType::where('name', 'Coursework')->first();
        $practical = AssessmentContributionType::where('name', 'Practical')->first();
        $test = AssessmentContributionType::where('name', 'Test')->first();
        $theory = AssessmentContributionType::where('name', 'Theory')->first();

        // Assessment type weights from Excel
        $assessmentWeights = [
            ['type' => $coursework->id, 'weight' => 25],
            ['type' => $practical->id, 'weight' => 15],
            ['type' => $test->id, 'weight' => 10],
            ['type' => $theory->id, 'weight' => 50],
        ];

        // Trimester weights from Excel
        $trimesterWeights = [
            1 => 30,
            2 => 35,
            3 => 35
        ];

        foreach ($modules as $module) {
            // Add assessment type weights
            foreach ($assessmentWeights as $weight) {
                ModuleAssessmentStructure::create([
                    'module_id' => $module->id,
                    'assessment_contribution_type_id' => $weight['type'],
                    'weight' => $weight['weight'],
                    'is_trimester_weight' => false,
                    'trimester' => null
                ]);
            }

            // Add trimester weights
            foreach ($trimesterWeights as $trimester => $weight) {
                ModuleAssessmentStructure::create([
                    'module_id' => $module->id,
                    'assessment_contribution_type_id' => $coursework->id, // Using coursework as default type for trimester weights
                    'weight' => $weight,
                    'is_trimester_weight' => true,
                    'trimester' => $trimester
                ]);
            }
        }
    }
}
