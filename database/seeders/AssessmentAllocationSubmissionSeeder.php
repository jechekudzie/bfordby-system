<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Assessment;
use App\Models\Semester;
use App\Models\EnrollmentCode;
use App\Models\Student;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\StudyMode;
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

        $courses = Course::all();
        if ($courses->isEmpty()) {
            $this->command->error('No courses found! Please run CourseSeeder first.');
            return;
        }
        
        $assessments = Assessment::all();
        if ($assessments->isEmpty()) {
            $this->command->error('No assessments found! Please run AssessmentSeeder first.');
            return;
        }

        // Get all semesters, or at least the first 3 trimesters
        $semesters = Semester::take(3)->get();
        if ($semesters->isEmpty()) {
            $this->command->error('No semesters found! Please run SemesterSeeder first.');
            return;
        }

        $enrollmentCode = EnrollmentCode::first();
        if (!$enrollmentCode) {
            $this->command->error('No enrollment codes found! Please run EnrollmentCodeSeeder first.');
            return;
        }
        
        $studyMode = StudyMode::first();
        if (!$studyMode) {
            $this->command->error('No study modes found! Please run StudyModesSeeder first.');
            return;
        }

        // Step 1: Ensure students are enrolled in courses
        $this->command->info('Creating course enrollments for students...');
        $enrollmentCount = 0;
        
        // Enroll each student in all available courses
        foreach ($students as $student) {
            foreach ($courses as $course) {
                // Check if enrollment exists
                $existingEnrollment = Enrollment::where('student_id', $student->id)
                    ->where('course_id', $course->id)
                    ->first();
                
                if (!$existingEnrollment) {
                    Enrollment::create([
                        'student_id' => $student->id,
                        'course_id' => $course->id,
                        'enrollment_code_id' => $enrollmentCode->id,
                        'study_mode_id' => $studyMode->id,
                        'enrollment_date' => now()->subMonths(rand(1, 6)),
                        'status' => 'active',
                    ]);
                    $enrollmentCount++;
                }
            }
        }
        $this->command->info("Created {$enrollmentCount} student course enrollments successfully!");

        // Step 2: Create assessment allocations and submissions for each semester
        $this->command->info('Creating assessment allocations and student submissions for all semesters...');
        $submissionCount = 0;
        $allocationCount = 0;
        
        // Process each course and create allocations for all modules and semesters
        foreach ($courses as $course) {
            $this->command->info("Processing course: {$course->name}");
            
            // Get all subjects for this course
            $subjects = $course->subjects;
            if ($subjects->isEmpty()) {
                $this->command->warn("No subjects found for course {$course->name}");
                continue;
            }
            
            // Process each subject
            foreach ($subjects as $subject) {
                $this->command->info("  - Processing subject: {$subject->name}");
                
                // Get all modules for this subject
                $modules = $subject->modules;
                if ($modules->isEmpty()) {
                    $this->command->warn("    No modules found for subject {$subject->name}");
                    continue;
                }
                
                // Process each module
                foreach ($modules as $module) {
                    $this->command->info("    - Processing module: {$module->name}");
                    
                    // Get all assessments for this module
                    $moduleAssessments = $module->assessments;
                    if ($moduleAssessments->isEmpty()) {
                        $this->command->warn("      No assessments found for module {$module->name}");
                        continue;
                    }
                    
                    // Create allocations for each assessment in each semester
                    foreach ($moduleAssessments as $index => $assessment) {
                        foreach ($semesters as $semesterIndex => $semester) {
                            $this->command->info("      - Creating allocation for assessment: {$assessment->name} in {$semester->name}");
                            
                            // Determine submission type based on index
                            $isEssay = $index % 3 === 0;
                            $isQuiz = $index % 3 === 1;
                            $isGroupWork = $index % 3 === 2;
                            
                            $submissionType = $isEssay ? 'online' : ($isQuiz ? 'online' : 'upload');
                            $isTimed = $isQuiz;
                            
                            // Create different due dates for different semesters
                            $dueDate = Carbon::now()->addDays(rand(5, 30))->addMonths($semesterIndex * 4);
                            
                            // Check if allocation already exists
                            $existingAllocation = AssessmentAllocation::where('assessment_id', $assessment->id)
                                ->where('semester_id', $semester->id)
                                ->first();
                                
                            if ($existingAllocation) {
                                $this->command->info("      - Allocation already exists, skipping");
                                continue;
                            }
                            
                            $allocation = AssessmentAllocation::create([
                                'assessment_id' => $assessment->id,
                                'enrollment_code_id' => $enrollmentCode->id,
                                'semester_id' => $semester->id,
                                'status' => 'open',
                                'due_date' => $dueDate,
                                'content' => "Instructions for {$assessment->name} ({$semester->name}). Please follow the guidelines and submit your work before the due date.",
                                'submission_type' => $submissionType,
                                'is_timed' => $isTimed,
                                'duration_minutes' => $isTimed ? rand(30, 120) : null,
                            ]);
                            
                            $allocationCount++;
                            
                            // For quiz type, create questions and options
                            if ($isQuiz) {
                                $this->createQuizQuestions($allocation);
                            }
                            
                            // Get students enrolled in this course
                            $enrolledStudents = Enrollment::where('course_id', $course->id)
                                ->where('status', 'active')
                                ->get()
                                ->map(function ($enrollment) {
                                    return Student::find($enrollment->student_id);
                                })
                                ->filter(); // Remove nulls
                            
                            if ($enrolledStudents->isEmpty()) {
                                $this->command->warn("      No students enrolled in this course - skipping submissions");
                                continue;
                            }
                            
                            // For group work, create a group
                            $group = null;
                            if ($isGroupWork) {
                                $group = Group::create([
                                    'assessment_allocation_id' => $allocation->id,
                                    'name' => 'Group ' . ($index + 1) . ' - ' . $semester->name,
                                ]);
                                
                                // Assign enrolled students to the group
                                foreach ($enrolledStudents as $student) {
                                    GroupStudent::create([
                                        'group_id' => $group->id,
                                        'student_id' => $student->id,
                                    ]);
                                }
                            }
                            
                            // Create student submissions
                            if ($isGroupWork && $group) {
                                // Group submissions count as 1 submission for the whole group
                                $submissionCount++;
                            } else {
                                // For individual assignments, count one per student
                                $submissionCount += $enrolledStudents->count();
                            }
                            
                            $this->createStudentSubmissions($allocation, $enrolledStudents, $isQuiz, $isGroupWork, $group);
                        }
                    }
                }
            }
        }
        
        $this->command->info("Created {$allocationCount} assessment allocations and {$submissionCount} graded submissions across all semesters successfully!");
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
        
        // Always grade each submission (removed random chance)
        $this->gradeSubmission($submission);
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
                // Always select the correct answer
                $options = $question->options;
                $correctOption = $options->where('is_correct', true)->first();
                
                if ($correctOption) {
                    $answers[$question->id] = [$correctOption->id];
                } else {
                    // Fallback if there's no correct option defined
                    $anyOption = $options->first();
                    if ($anyOption) {
                        $answers[$question->id] = [$anyOption->id];
                    }
                }
            } elseif ($question->question_type === 'text') {
                $answers[$question->id] = "This is a sample text answer for question {$question->id}. It discusses all the key points in detail and provides a thorough analysis of the topic with references to course materials.";
            }
        }
        
        return $answers;
    }
    
    /**
     * Grade a submission with high scores
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
                        // Higher scores: 80% to 100% of points
                        $pointsEarned = round($question->weight * rand(80, 100) / 100, 1);
                        $feedback[$question->id] = "Excellent answer. Your response demonstrates comprehensive understanding of the topic.";
                    }
                    
                    $grades[$question->id] = $pointsEarned;
                    $earnedPoints += $pointsEarned;
                }
            }
        } elseif ($submission->content || $submission->file_path) {
            // For essays or file uploads, assign a grade between 80% and 100%
            $totalPoints = 100;
            $earnedPoints = rand(80, 100);
            $feedback['general'] = "Excellent work! Your submission demonstrates a thorough understanding of the key concepts with strong supporting evidence and clear examples.";
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
