<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AssessmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = \App\Models\Module::all();
        $semester = \App\Models\Semester::first();
        $enrollmentCode = \App\Models\EnrollmentCode::first();

        if (!$semester || !$enrollmentCode) {
            throw new \Exception('Please ensure Semester and EnrollmentCode seeders have been run first.');
        }

        // Assessment types and their descriptions
        $assessmentTypes = [
            'Coursework' => [
                'name' => 'Assignment 1',
                'description' => 'First coursework assignment focusing on core concepts',
                'submission_type' => 'upload'
            ],
            'Test' => [
                'name' => 'Mid-Term Test',
                'description' => 'Mid-term assessment covering theoretical aspects',
                'submission_type' => 'online'
            ],
            'Practical' => [
                'name' => 'Practical Assessment',
                'description' => 'Hands-on practical assessment of skills',
                'submission_type' => 'upload'
            ],
            'Theory' => [
                'name' => 'Theory Examination',
                'description' => 'Final theory examination',
                'submission_type' => 'online'
            ]
        ];

        foreach ($modules as $module) {
            // Create one assessment of each type for the module
            foreach ($assessmentTypes as $type => $details) {
                $assessment = \App\Models\Assessment::create([
                    'module_id' => $module->id,
                    'name' => $module->name . ' - ' . $details['name'],
                    'description' => $details['description'],
                    'type' => $type,
                    'max_score' => 100,
                    'status' => 'published',
                    'slug' => \Str::slug($module->name . '-' . $details['name']) . '-' . \Str::random(8)
                ]);

                // Create assessment allocation
                $allocation = \App\Models\AssessmentAllocation::create([
                    'assessment_id' => $assessment->id,
                    'enrollment_code_id' => $enrollmentCode->id,
                    'semester_id' => $semester->id,
                    'status' => 'open',
                    'due_date' => now()->addDays(30),
                    'submission_type' => $details['submission_type'],
                    'is_timed' => false
                ]);

                // If it's an online assessment, create some questions
                if ($details['submission_type'] === 'online') {
                    for ($i = 1; $i <= 5; $i++) {
                        $question = \App\Models\AssessmentAllocationQuestion::create([
                            'assessment_allocation_id' => $allocation->id,
                            'question_text' => "Question {$i} for {$assessment->name}",
                            'question_type' => $i <= 3 ? 'multiple_choice' : 'text',
                            'weight' => 20 // 5 questions, 20 points each = 100 total
                        ]);

                        // Create options for multiple choice questions
                        if ($question->question_type === 'multiple_choice') {
                            for ($j = 1; $j <= 4; $j++) {
                                \App\Models\AssessmentAllocationQuestionOption::create([
                                    'assessment_allocation_question_id' => $question->id,
                                    'option_text' => "Option {$j} for Question {$i}",
                                    'is_correct' => $j === 1 // First option is correct
                                ]);
                            }
                        }
                    }
                }
            }
        }
    }
}
