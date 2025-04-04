<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Assessment;
use App\Models\Semester;
use App\Models\Student;
use App\Models\AssessmentAllocation;
use App\Models\AssessmentAllocationSubmission;
use Carbon\Carbon;

class TestSubmissionSeeder extends Seeder
{
    /**
     * Create test submissions with various statuses for demonstration purposes.
     */
    public function run(): void
    {
        $this->command->info('Creating test submissions with various statuses...');
        
        // Get all semesters (trimesters)
        $semesters = Semester::all();
        if ($semesters->isEmpty()) {
            $this->command->error('No semesters found! Please run SemesterSeeder first.');
            return;
        }
        
        // Get a student to assign submissions to
        $student = Student::first();
        if (!$student) {
            $this->command->error('No students found! Please run StudentSeeder first.');
            return;
        }
        
        $submissionCount = 0;
        
        // Process each semester (trimester)
        foreach ($semesters as $semester) {
            $this->command->info("Processing semester: {$semester->name}");
            
            // Get all allocations for this semester
            $allocations = AssessmentAllocation::where('semester_id', $semester->id)->get();
            
            if ($allocations->isEmpty()) {
                $this->command->warn("No allocations found for semester {$semester->name}");
                continue;
            }
            
            // Create different submission statuses for each allocation
            foreach ($allocations as $index => $allocation) {
                $this->command->info("  - Processing allocation for assessment: {$allocation->assessment->name}");
                
                // Different statuses based on index
                // 1. Create a submitted submission
                if ($index % 3 === 0) {
                    $this->createSubmission(
                        $allocation, 
                        $student, 
                        'submitted', 
                        Carbon::now()->subDays(rand(1, 7))
                    );
                    $submissionCount++;
                }
                
                // 2. Create a graded submission
                else if ($index % 3 === 1) {
                    $submission = $this->createSubmission(
                        $allocation, 
                        $student, 
                        'graded', 
                        Carbon::now()->subDays(rand(8, 14))
                    );
                    
                    // Add grade and feedback
                    $submission->grade = rand(65, 95);
                    $submission->graded_at = Carbon::now()->subDays(rand(1, 5));
                    $submission->feedback = [
                        'general' => "Good work on this assignment. You have shown good understanding of the concepts.",
                        'strengths' => "Strong analysis and clear presentation.",
                        'areas_for_improvement' => "Could improve on referencing and deeper analysis in some sections."
                    ];
                    $submission->save();
                    
                    $submissionCount++;
                }
                
                // 3. Create an in-progress submission
                else if ($index % 3 === 2) {
                    $this->createSubmission(
                        $allocation, 
                        $student, 
                        'in_progress', 
                        null
                    );
                    $submissionCount++;
                }
            }
        }
        
        $this->command->info("Created {$submissionCount} test submissions with different statuses successfully!");
    }
    
    /**
     * Create a submission with the specified status
     */
    private function createSubmission($allocation, $student, $status, $submittedAt)
    {
        // Check if submission already exists
        $existingSubmission = AssessmentAllocationSubmission::where('assessment_allocation_id', $allocation->id)
            ->where('student_id', $student->id)
            ->first();
        
        if ($existingSubmission) {
            $this->command->info("    - Submission already exists, updating");
            $existingSubmission->status = $status;
            $existingSubmission->submitted_at = $submittedAt;
            $existingSubmission->save();
            return $existingSubmission;
        }
        
        // Determine submission content based on type
        $content = null;
        $answers = null;
        $filePath = null;
        
        if ($allocation->submission_type === 'text' || $allocation->submission_type === 'online') {
            $content = "This is a sample submission for {$allocation->assessment->name}. " . 
                       "The student has demonstrated understanding of the concepts covered in this assessment.";
            
            // For online quizzes, generate answers
            if ($allocation->submission_type === 'online') {
                $questions = $allocation->questions;
                $answers = [];
                
                if ($questions->isNotEmpty()) {
                    foreach ($questions as $question) {
                        // For multiple choice, select an option
                        if ($question->question_type === 'multiple_choice') {
                            $options = $question->options;
                            if ($options->isNotEmpty()) {
                                $selectedOption = $options->random()->id;
                                $answers[$question->id] = $selectedOption;
                            }
                        } else {
                            // For text questions
                            $answers[$question->id] = "Answer to question: {$question->content}";
                        }
                    }
                }
            }
        } else if ($allocation->submission_type === 'file' || $allocation->submission_type === 'upload') {
            $filePath = "submissions/{$student->id}/{$allocation->id}/sample-submission.pdf";
        }
        
        // Create the submission
        $submission = AssessmentAllocationSubmission::create([
            'assessment_allocation_id' => $allocation->id,
            'student_id' => $student->id,
            'content' => $content,
            'answers' => $answers,
            'file_path' => $filePath,
            'start_time' => $status !== 'none' ? Carbon::now()->subDays(rand(10, 20)) : null,
            'submitted_at' => $submittedAt,
            'status' => $status
        ]);
        
        return $submission;
    }
}
