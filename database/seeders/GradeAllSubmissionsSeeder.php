<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\AssessmentAllocation;
use App\Models\AssessmentAllocationSubmission;
use Carbon\Carbon;

class GradeAllSubmissionsSeeder extends Seeder
{
    /**
     * Ensure all assessment allocations have graded submissions
     */
    public function run(): void
    {
        $this->command->info('Ensuring all assessment allocations have graded submissions...');
        
        // Get all students
        $students = Student::all();
        if ($students->isEmpty()) {
            $this->command->error('No students found! Please run StudentSeeder first.');
            return;
        }
        
        // Get all allocations
        $allocations = AssessmentAllocation::with('assessment', 'semester')->get();
        if ($allocations->isEmpty()) {
            $this->command->error('No assessment allocations found!');
            return;
        }
        
        $gradedCount = 0;
        $createdCount = 0;
        
        // Process each student
        foreach ($students as $student) {
            $this->command->info("Processing student: {$student->first_name} {$student->last_name}");
            
            // Process each allocation
            foreach ($allocations as $allocation) {
                $assessmentName = $allocation->assessment->name;
                $semesterName = $allocation->semester->name;
                
                $this->command->info("  - Processing allocation: {$assessmentName} in {$semesterName}");
                
                // Check if submission exists
                $submission = AssessmentAllocationSubmission::where('assessment_allocation_id', $allocation->id)
                    ->where('student_id', $student->id)
                    ->first();
                
                if ($submission) {
                    $this->command->info("    - Submission exists, updating to graded status");
                    
                    // Update the submission to graded status with a good grade
                    $submission->status = 'graded';
                    $submission->grade = rand(70, 95); // Good grades between 70-95
                    $submission->submitted_at = Carbon::now()->subDays(rand(10, 20));
                    $submission->graded_at = Carbon::now()->subDays(rand(1, 9));
                    $submission->feedback = [
                        'general' => "Excellent work on this assessment. You've demonstrated a strong understanding of the subject.",
                        'strengths' => "Clear presentation, good analysis, and strong application of concepts.",
                        'areas_for_improvement' => "Minor improvements could be made in the analysis section."
                    ];
                    $submission->save();
                    
                    $gradedCount++;
                } else {
                    $this->command->info("    - Creating new graded submission");
                    
                    // Determine submission content based on type
                    $content = null;
                    $answers = null;
                    $filePath = null;
                    
                    if ($allocation->submission_type === 'text' || $allocation->submission_type === 'online') {
                        $content = "This is a high-quality submission for {$assessmentName}. " . 
                                "The student has demonstrated excellent understanding of all concepts.";
                        
                        // For online quizzes, generate answers
                        if ($allocation->submission_type === 'online') {
                            $questions = $allocation->questions;
                            $answers = [];
                            
                            if ($questions && $questions->isNotEmpty()) {
                                foreach ($questions as $question) {
                                    // For multiple choice, select an option
                                    if ($question->question_type === 'multiple_choice') {
                                        $options = $question->options;
                                        if ($options && $options->isNotEmpty()) {
                                            $selectedOption = $options->random()->id;
                                            $answers[$question->id] = $selectedOption;
                                        }
                                    } else {
                                        // For text questions
                                        $answers[$question->id] = "Comprehensive answer to question: {$question->content}";
                                    }
                                }
                            }
                        }
                    } else if ($allocation->submission_type === 'file' || $allocation->submission_type === 'upload') {
                        $filePath = "submissions/{$student->id}/{$allocation->id}/high-quality-submission.pdf";
                    }
                    
                    // Create a graded submission with a good grade
                    $submittedAt = Carbon::now()->subDays(rand(10, 20));
                    $gradedAt = Carbon::now()->subDays(rand(1, 9));
                    
                    // Create the submission
                    AssessmentAllocationSubmission::create([
                        'assessment_allocation_id' => $allocation->id,
                        'student_id' => $student->id,
                        'content' => $content,
                        'answers' => $answers,
                        'file_path' => $filePath,
                        'start_time' => $submittedAt->copy()->subHours(rand(1, 5)),
                        'submitted_at' => $submittedAt,
                        'status' => 'graded',
                        'grade' => rand(70, 95), // Good grades between 70-95
                        'graded_at' => $gradedAt,
                        'feedback' => [
                            'general' => "Excellent work on this assessment. You've demonstrated a strong understanding of the subject.",
                            'strengths' => "Clear presentation, good analysis, and strong application of concepts.",
                            'areas_for_improvement' => "Minor improvements could be made in the analysis section."
                        ]
                    ]);
                    
                    $createdCount++;
                }
            }
        }
        
        $this->command->info("Updated {$gradedCount} existing submissions and created {$createdCount} new graded submissions!");
    }
} 