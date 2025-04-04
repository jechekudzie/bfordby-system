<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Assessment;
use App\Models\Semester;
use App\Models\EnrollmentCode;
use App\Models\Student;
use App\Models\AssessmentAllocation;
use App\Models\AssessmentAllocationQuestion;
use App\Models\AssessmentAllocationQuestionOption;
use App\Models\AssessmentAllocationSubmission;
use App\Models\Group;
use App\Models\GroupStudent;
use Carbon\Carbon;

class AssessmentAllocationSubmissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get required data
        $students = Student::all();
        if ($students->isEmpty()) {
            $this->command->error('No students found! Please run StudentSeeder first.');
            return;
        }

        $assessments = Assessment::take(10)->get();
        if ($assessments->isEmpty()) {
            $this->command->error('No assessments found! Please run AssessmentSeeder first.');
            return;
        }

        $semester = Semester::where('status', 'active')->first() ?? Semester::first();
        if (!$semester) {
            $this->command->error('No semesters found! Please run SemesterSeeder first.');
            return;
        }

        $enrollmentCode = EnrollmentCode::first();
        if (!$enrollmentCode) {
            $this->command->error('No enrollment codes found! Please run EnrollmentCodeSeeder first.');
            return;
        }

        $this->command->info('Creating assessment allocations and student submissions...');
        
        // Create various types of assessment allocations
        foreach ($assessments as $index => $assessment) {
            // Create an assessment allocation
            $isEssay = $index % 3 === 0;
            $isQuiz = $index % 3 === 1;
            $isGroupWork = $index % 3 === 2;
            
            $submissionType = $isEssay ? 'online' : ($isQuiz ? 'online' : 'upload');
            $isTimed = $isQuiz;
            
            $dueDate = Carbon::now()->addDays(rand(5, 30));
            
            $allocation = AssessmentAllocation::create([
                'assessment_id' => $assessment->id,
                'enrollment_code_id' => $enrollmentCode->id,
                'semester_id' => $semester->id,
                'status' => 'open',
                'due_date' => $dueDate,
                'content' => "Instructions for {$assessment->name}. Please follow the guidelines and submit your work before the due date.",
                'submission_type' => $submissionType,
                'is_timed' => $isTimed,
                'duration_minutes' => $isTimed ? rand(30, 120) : null,
            ]);
            
            // For quiz type, create questions and options
            if ($isQuiz) {
                $this->createQuizQuestions($allocation);
            }
            
            // For group work, create a group
            $group = null;
            if ($isGroupWork) {
                $group = Group::create([
                    'assessment_allocation_id' => $allocation->id,
                    'name' => 'Group ' . ($index + 1),
                ]);
                
                // Assign all students to the group
                foreach ($students as $student) {
                    GroupStudent::create([
                        'group_id' => $group->id,
                        'student_id' => $student->id,
                    ]);
                }
            }
            
            // Create student submissions
            $this->createStudentSubmissions($allocation, $students, $isQuiz, $isGroupWork, $group);
        }
        
        $this->command->info('Created assessment allocations and submissions successfully!');
    }
    
    /**
     * Create quiz questions for an assessment allocation
     */
    private function createQuizQuestions($allocation)
    {
        // Create multiple choice questions
        for ($i = 1; $i <= 5; $i++) {
            $question = AssessmentAllocationQuestion::create([
                'assessment_allocation_id' => $allocation->id,
                'question_text' => "Quiz Question #{$i} for {$allocation->assessment->name}?",
                'question_type' => 'multiple_choice',
                'order' => $i,
                'weight' => 5,
            ]);
            
            // Create options for each question
            $correctOption = rand(1, 4);
            for ($j = 1; $j <= 4; $j++) {
                AssessmentAllocationQuestionOption::create([
                    'assessment_allocation_question_id' => $question->id,
                    'option_text' => "Option {$j} for Question {$i}",
                    'is_correct' => $j === $correctOption,
                ]);
            }
        }
        
        // Create a short answer question
        AssessmentAllocationQuestion::create([
            'assessment_allocation_id' => $allocation->id,
            'question_text' => "Short answer question: Explain the key concepts of {$allocation->assessment->name}.",
            'question_type' => 'text',
            'order' => 6,
            'weight' => 10,
        ]);
        
        // Create an essay question
        AssessmentAllocationQuestion::create([
            'assessment_allocation_id' => $allocation->id,
            'question_text' => "Essay question: Analyze the importance of {$allocation->assessment->name} in your field of study.",
            'question_type' => 'text',
            'order' => 7,
            'weight' => 20,
        ]);
    }
    
    /**
     * Create student submissions for an assessment allocation
     */
    private function createStudentSubmissions($allocation, $students, $isQuiz, $isGroupWork, $group = null)
    {
        // For group work, create one submission for the whole group
        if ($isGroupWork && $group) {
            $this->createSubmission($allocation, $students->first(), $isQuiz, $group->id);
            return;
        }
        
        // For individual work, create a submission for each student
        foreach ($students as $student) {
            $this->createSubmission($allocation, $student, $isQuiz);
        }
    }
    
    /**
     * Create a single submission with appropriate data
     */
    private function createSubmission($allocation, $student, $isQuiz, $groupId = null)
    {
        $startTime = Carbon::now()->subDays(rand(1, 3));
        $submittedAt = $startTime->copy()->addMinutes(rand(30, 120));
        
        $answers = null;
        $content = null;
        $filePath = null;
        
        // Generate answers based on submission type
        if ($isQuiz) {
            $answers = $this->generateQuizAnswers($allocation);
        } elseif ($allocation->submission_type === 'online') {
            $content = "This is a sample online submission for {$allocation->assessment->name} by {$student->first_name} {$student->last_name}.\n\n";
            $content .= "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque vestibulum, ligula vitae fermentum lobortis, felis nisl consequat justo, nec pellentesque urna diam non dui. Donec non tellus at mauris ultrices tristique. Maecenas vitae erat sit amet urna fermentum lobortis.\n\n";
            $content .= "Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Mauris at scelerisque arcu. Praesent faucibus, arcu at volutpat ultricies, mauris mauris tempus justo, vel sagittis dui nibh sit amet nisl. Nullam dignissim justo at arcu lacinia suscipit.\n\n";
            $content .= "In conclusion, the findings suggest that further research is needed in this area.";
        } else if ($allocation->submission_type === 'upload' || $allocation->submission_type === 'group') {
            $filePath = "uploads/submissions/sample_submission_{$allocation->id}_{$student->id}.pdf";
        }
        
        // Create the submission
        $submission = AssessmentAllocationSubmission::create([
            'assessment_allocation_id' => $allocation->id,
            'student_id' => $student->id,
            'group_id' => $groupId,
            'content' => $content,
            'answers' => $answers,
            'file_path' => $filePath,
            'start_time' => $startTime,
            'submitted_at' => $submittedAt,
            'status' => 'submitted',
        ]);
        
        // Generate and apply grades (70-100% chance of being graded)
        if (rand(1, 10) > 3) {
            $this->gradeSubmission($submission);
        }
    }
    
    /**
     * Generate random answers for quiz questions
     */
    private function generateQuizAnswers($allocation)
    {
        $answers = [];
        $questions = $allocation->questions;
        
        foreach ($questions as $question) {
            if ($question->question_type === 'multiple_choice') {
                // For multiple choice, select one of the options (50% chance of correct answer)
                $options = $question->options;
                $correctOption = $options->where('is_correct', true)->first();
                
                if (rand(0, 1) === 1 && $correctOption) {
                    $answers[$question->id] = [$correctOption->id];
                } else {
                    $wrongOption = $options->where('is_correct', false)->shuffle()->first();
                    if ($wrongOption) {
                        $answers[$question->id] = [$wrongOption->id];
                    }
                }
            } elseif ($question->question_type === 'text') {
                $answers[$question->id] = "This is a sample text answer for question {$question->id}. It discusses the main points and provides an analysis of the topic.";
            }
        }
        
        return $answers;
    }
    
    /**
     * Grade a submission with random but realistic grades
     */
    private function gradeSubmission($submission)
    {
        $allocation = $submission->assessmentAllocation;
        $questions = $allocation->questions;
        $grades = [];
        $feedback = [];
        $totalPoints = 0;
        $earnedPoints = 0;
        
        // Grade each answer
        if ($submission->answers) {
            foreach ($questions as $question) {
                $totalPoints += $question->weight;
                
                if (isset($submission->answers[$question->id])) {
                    $pointsEarned = 0;
                    
                    if ($question->question_type === 'multiple_choice') {
                        $selectedOption = $submission->answers[$question->id][0] ?? null;
                        $correctOption = $question->options->where('is_correct', true)->first();
                        
                        if ($selectedOption && $correctOption && $selectedOption == $correctOption->id) {
                            $pointsEarned = $question->weight;
                            $feedback[$question->id] = "Correct answer!";
                        } else {
                            $pointsEarned = 0;
                            $feedback[$question->id] = "Incorrect. The correct answer was: " . $correctOption->option_text;
                        }
                    } elseif ($question->question_type === 'text') {
                        // Random score between 60% and 95% of points
                        $pointsEarned = round($question->weight * rand(60, 95) / 100, 1);
                        $feedback[$question->id] = "Good attempt. Your answer demonstrates understanding but could be expanded with more examples and analysis.";
                    }
                    
                    $grades[$question->id] = $pointsEarned;
                    $earnedPoints += $pointsEarned;
                }
            }
        } elseif ($submission->content || $submission->file_path) {
            // For essays or file uploads, assign a grade between 60% and 95%
            $totalPoints = 100;
            $earnedPoints = rand(60, 95);
            $feedback['general'] = "Good work overall. Your submission demonstrates understanding of the key concepts. Some areas could be strengthened with more evidence or examples.";
        }
        
        // Calculate final grade as percentage
        $finalGrade = $totalPoints > 0 ? ($earnedPoints / $totalPoints) * 100 : 0;
        
        // Update the submission with grades
        $submission->update([
            'grades' => $grades,
            'feedback' => $feedback,
            'grade' => round($finalGrade, 2),
            'graded_at' => Carbon::now()->subHours(rand(1, 48)),
            'status' => 'graded'
        ]);
    }
}
