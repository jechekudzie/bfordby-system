<?php

namespace App\Http\Controllers;

use App\Models\AssessmentAllocation;
use App\Models\AssessmentAllocationSubmission;
use App\Models\Student;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AssessmentAllocationSubmissionController extends Controller
{
    /**
     * Display the submission form or current submission.
     */
    public function show(Request $request, AssessmentAllocation $allocation)
    {
        // For demo purposes, get the first student
        $student = Student::first();
        $submission = $this->getOrInitializeSubmission($student, $allocation);

        // Check if this is a group submission
        $group = null;
        if ($allocation->submission_type === 'group') {
            $group = $student->groups()->where('assessment_allocation_id', $allocation->id)->first();
            if (!$group && $allocation->due_date > now()) {
                return redirect()->route('assessment-allocation-groups.index', $allocation)
                    ->with('warning', 'You need to join or create a group first.');
            }
        }

        // Handle submission state and timing
        if ($this->handleSubmissionTiming($submission, $allocation)) {
            return back()->with('error', 'Time limit exceeded. Your answers have been automatically submitted.');
        }

        return view('students.submissions.show', compact('allocation', 'submission', 'group', 'student'));
    }

    /**
     * Store a new submission.
     */
    public function store(Request $request, AssessmentAllocation $allocation)
    {
        try {
            // For demo purposes, get the first student
            $student = Student::first();
            $submission = $this->getOrInitializeSubmission($student, $allocation);
            
            // Validate submission timing and state
            $this->validateSubmissionState($submission, $allocation);
            
            // Check if this is a group submission
            $isGroupSubmission = $request->has('group_submission') && $request->group_submission == 1;
            
            // Handle group submission
            if ($allocation->submission_type === 'group' || $isGroupSubmission) {
                $group = $student->groups()->where('assessment_allocation_id', $allocation->id)->firstOrFail();
                $submission->group_id = $group->id;
            }

            // Validate and store submission content
            $this->validateAndStoreContent($request, $submission, $allocation);

            // Update submission status
            $submission->status = 'submitted';
            $submission->submitted_at = now();
            $submission->save();
            
            // If this is a group submission, create submissions for all group members
            if (($allocation->submission_type === 'group' || $isGroupSubmission) && $submission->group_id) {
                $group = Group::with('students')->find($submission->group_id);
                if ($group) {
                    foreach ($group->students as $groupMember) {
                        // Skip the submitting student as they already have a submission
                        if ($groupMember->id === $student->id) {
                            continue;
                        }
                        
                        // Create a copy of the submission for this group member
                        $memberSubmission = new AssessmentAllocationSubmission();
                        $memberSubmission->assessment_allocation_id = $allocation->id;
                        $memberSubmission->student_id = $groupMember->id;
                        $memberSubmission->group_id = $group->id;
                        $memberSubmission->status = 'submitted';
                        $memberSubmission->submitted_at = now();
                        
                        // Copy submission content
                        if ($submission->file_path) {
                            $memberSubmission->file_path = $submission->file_path;
                        }
                        if ($submission->answers) {
                            $memberSubmission->answers = $submission->answers;
                        }
                        if ($submission->content) {
                            $memberSubmission->content = $submission->content;
                        }
                        
                        $memberSubmission->save();
                    }
                }
            }

            // First, find the enrollment code from the allocation
            $enrollmentCode = $allocation->enrollmentCode;

            // Then find the enrollment associated with this student and enrollment code
            if ($enrollmentCode) {
                $enrollment = $student->enrollments()
                    ->where('enrollment_code_id', $enrollmentCode->id)
                    ->first();
            } else {
                // Fallback - get the first enrollment
                $enrollment = $student->enrollments()->first();
            }

            return redirect()->route('students.enrollments.show', [
                'student' => $student,
                'enrollment' => $enrollment ?? $student->enrollments()->first()
            ])->with('success', 'Assessment submitted successfully.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to submit assessment: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Update an existing submission.
     */
    public function update(Request $request, AssessmentAllocation $allocation)
    {
        try {
            // For demo purposes, get the first student
            $student = Student::first();
            $submission = AssessmentAllocationSubmission::where([
                'assessment_allocation_id' => $allocation->id,
                'student_id' => $student->id
            ])->firstOrFail();

            if (!$this->canUpdateSubmission($submission, $allocation)) {
                throw ValidationException::withMessages(['submission' => 'This submission can no longer be updated.']);
            }

            return $this->store($request, $allocation);
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update submission: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Download a submission file.
     */
    public function download(AssessmentAllocation $allocation)
    {
        // For demo purposes, get the first student
        $student = Student::first();
        $submission = AssessmentAllocationSubmission::where([
            'assessment_allocation_id' => $allocation->id,
            'student_id' => $student->id
        ])->firstOrFail();

        if (!$submission->file_path || !Storage::exists($submission->file_path)) {
            abort(404, 'Submission file not found.');
        }

        $filename = basename($submission->file_path);
        $headers = [
            'Content-Type' => Storage::mimeType($submission->file_path),
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
        ];

        return Storage::response($submission->file_path, $filename, $headers);
    }

    /**
     * Start a timed assessment.
     */
    public function startTimed(Request $request, AssessmentAllocation $allocation)
    {
        if (!$allocation->is_timed) {
            abort(400, 'This is not a timed assessment.');
        }

        // For demo purposes, get the first student
        $student = Student::first();
        $submission = $this->getOrInitializeSubmission($student, $allocation);

        if ($submission->start_time) {
            return back()->with('error', 'Assessment already started.');
        }

        $submission->start_time = now();
        $submission->status = 'in_progress';
        $submission->save();

        return redirect()->route('students.submissions.create', $allocation);
    }

    /**
     * Delete a submission.
     */
    public function destroy(AssessmentAllocation $allocation)
    {
        // For demo purposes, get the first student
        $student = Student::first();
        $submission = AssessmentAllocationSubmission::where([
            'assessment_allocation_id' => $allocation->id,
            'student_id' => $student->id
        ])->firstOrFail();

        if ($submission->status === 'graded' || ($allocation->due_date && now() > $allocation->due_date)) {
            abort(403, 'Cannot delete this submission.');
        }

        if ($submission->file_path) {
            Storage::delete($submission->file_path);
        }

        $submission->delete();

        return redirect()->route('students.submissions.show', $allocation)
            ->with('success', 'Submission deleted successfully.');
    }

    /**
     * Display the submission form for creating a new submission.
     */
    public function create(Request $request, AssessmentAllocation $allocation)
    {
        // For demo purposes, get the first student
        $student = Student::first();
        
        // Check if there's an existing submission
        $submission = $this->getOrInitializeSubmission($student, $allocation);

        // Check if this is a group submission
        $group = null;
        $isGroupSubmission = $request->has('group') && $request->group == 1;
        
        if ($allocation->submission_type === 'group' || $isGroupSubmission) {
            $group = $student->groups()->where('assessment_allocation_id', $allocation->id)->first();
            if (!$group && $allocation->due_date > now()) {
                return redirect()->route('assessment-allocation-groups.index', $allocation)
                    ->with('warning', 'You need to join or create a group first.');
            }
        }

        // For timed assessments that haven't started, show the warning page
        if ($allocation->is_timed && !$submission->start_time) {
            return view('students.submissions.create', compact('allocation', 'submission', 'group', 'student', 'isGroupSubmission'));
        }

        // Determine which view to use based on submission type
        $view = match($allocation->submission_type) {
            'upload' => 'students.submissions.types.upload',
            'online' => 'students.submissions.types.online',
            'in-class' => 'students.submissions.types.in-class',
            'group' => 'students.submissions.types.' . ($allocation->questions->isNotEmpty() ? 'online' : 'upload'),
            default => 'students.submissions.types.online'
        };

        return view($view, compact('allocation', 'submission', 'group', 'student', 'isGroupSubmission'));
    }

    /**
     * Display the submitted answers for review.
     */
    public function viewAnswers(AssessmentAllocation $allocation)
    {
        // For demo purposes, get the first student
        $student = Student::first();
        
        // Get the submission
        $submission = AssessmentAllocationSubmission::where([
            'assessment_allocation_id' => $allocation->id,
            'student_id' => $student->id
        ])->firstOrFail();
        
        // Check if this is a group submission
        $group = null;
        if ($allocation->submission_type === 'group') {
            $group = $student->groups()->where('assessment_allocation_id', $allocation->id)->first();
        }
        
        return view('students.submissions.view-answers', compact('allocation', 'submission', 'group', 'student'));
    }

    /**
     * Show the grading form for a submission.
     */
    public function showGradeForm(AssessmentAllocation $allocation, AssessmentAllocationSubmission $submission)
    {
        // Ensure the submission belongs to this allocation
        if ($submission->assessment_allocation_id !== $allocation->id) {
            abort(404);
        }

        // Get group information if this is a group submission
        $group = null;
        $groupMembers = [];
        if ($submission->group_id) {
            $group = Group::with('students')->find($submission->group_id);
            if ($group) {
                $groupMembers = $group->students;
            }
        }

        // For upload submissions or group submissions with uploads, use a dedicated view
        if ($allocation->submission_type === 'upload' || 
            ($allocation->submission_type === 'group' && (!$allocation->questions || $allocation->questions->isEmpty()))) {
            
            // Set a default percentage grade if not already graded
            $percentageGrade = $submission->grade ?? 0;
            
            // Extract weights from feedback if they exist
            $weights = [];
            if (!empty($submission->feedback)) {
                foreach ($submission->feedback as $key => $value) {
                    if (strpos($key, 'weight_') === 0) {
                        $index = substr($key, 7); // Remove 'weight_' prefix
                        $weights[$index] = $value;
                    }
                }
            }
            
            return view('admin.submissions.types.upload_grade', compact(
                'submission', 
                'allocation', 
                'percentageGrade', 
                'weights', 
                'group', 
                'groupMembers'
            ));
        }

        // For other submission types with questions
        $gradeData = [];
        $totalMaxScore = 0;
        $totalScore = 0;
        
        foreach ($allocation->questions as $question) {
            $answer = $submission->answers[$question->id] ?? null;
            $maxScore = $question->weight;
            $totalMaxScore += $maxScore;
            
            // Initial score is 0
            $score = 0;
            
            if ($answer && $question->question_type === 'multiple_choice') {
                // Get correct option
                $correctOption = $question->options()->where('is_correct', true)->first();
                if ($correctOption && $answer == $correctOption->id) {
                    $score = $maxScore;
                }
            }
            
            // If already graded, use the existing score
            if (isset($submission->grades[$question->id])) {
                $score = $submission->grades[$question->id];
            }
            
            $totalScore += $score;
            $gradeData[$question->id] = [
                'score' => $score,
                'max_score' => $maxScore,
                'feedback' => $submission->feedback[$question->id] ?? ''
            ];
        }
        
        // Calculate initial percentage grade
        $percentageGrade = $totalMaxScore > 0 ? ($totalScore / $totalMaxScore) * 100 : 0;
        
        return view('admin.submissions.grade', compact(
            'submission', 
            'allocation', 
            'gradeData', 
            'percentageGrade', 
            'group', 
            'groupMembers'
        ));
    }

    /**
     * Store grades for a submission.
     */
    public function storeGrades(Request $request, AssessmentAllocation $allocation, AssessmentAllocationSubmission $submission)
    {
        // Ensure the submission belongs to this allocation
        if ($submission->assessment_allocation_id !== $allocation->id) {
            abort(404);
        }
        
        // Check if this is a group submission
        $isGroupSubmission = $submission->group_id !== null;
        $groupMembers = [];
        
        if ($isGroupSubmission) {
            // Get all students in the group
            $group = Group::with('students')->find($submission->group_id);
            if ($group) {
                $groupMembers = $group->students;
            }
        }
        
        // For upload submissions or group submissions with uploads
        if ($allocation->submission_type === 'upload' || 
            ($allocation->submission_type === 'group' && (!$allocation->questions || $allocation->questions->isEmpty()))) {
            
            // Validate the request
            $request->validate([
                'grades' => 'required|array',
                'grades.*' => 'required|numeric|min:0',
                'weights' => 'required|array',
                'weights.*' => 'required|numeric|min:1',
                'feedback' => 'nullable|array',
                'general_feedback' => 'nullable|string'
            ]);
            
            $grades = $request->input('grades');
            $weights = $request->input('weights');
            $feedback = $request->input('feedback') ?? [];
            
            // Calculate total score and percentage
            $totalScore = 0;
            $totalMaxScore = 0;
            
            foreach ($grades as $index => $score) {
                $weight = $weights[$index] ?? 10;
                $totalMaxScore += $weight;
                
                // Ensure score doesn't exceed weight
                $grades[$index] = min((float)$score, (float)$weight);
                $totalScore += $grades[$index];
                
                // Store the weight in the feedback JSON
                $feedback["weight_{$index}"] = $weight;
            }
            
            // Add general feedback if provided
            if ($request->has('general_feedback')) {
                $feedback['general'] = $request->input('general_feedback');
            }
            
            // Calculate percentage grade
            $percentageGrade = $totalMaxScore > 0 ? ($totalScore / $totalMaxScore) * 100 : 0;
            
            // Update the submission
            $submission->grades = $grades;
            $submission->feedback = $feedback;
            $submission->grade = round($percentageGrade, 2); // Store as percentage with 2 decimal places
            $submission->status = 'graded';
            $submission->graded_at = now();
            $submission->save();
            
            // If this is a group submission, update grades for all group members
            if ($isGroupSubmission && count($groupMembers) > 0) {
                foreach ($groupMembers as $student) {
                    // Skip the student who owns the current submission
                    if ($student->id === $submission->student_id) {
                        continue;
                    }
                    
                    // Find or create a submission for this student
                    $studentSubmission = AssessmentAllocationSubmission::firstOrCreate([
                        'assessment_allocation_id' => $allocation->id,
                        'student_id' => $student->id,
                        'group_id' => $submission->group_id
                    ]);
                    
                    // Copy the grades and feedback
                    $studentSubmission->grades = $grades;
                    $studentSubmission->feedback = $feedback;
                    $studentSubmission->grade = round($percentageGrade, 2);
                    $studentSubmission->status = 'graded';
                    $studentSubmission->graded_at = now();
                    $studentSubmission->file_path = $submission->file_path; // Copy the file path
                    $studentSubmission->submitted_at = $submission->submitted_at;
                    $studentSubmission->save();
                }
            }
            
            return redirect()->route('admin.assessment-allocations.submissions.index', $allocation)
                ->with('success', 'Submission graded successfully.');
        }
        
        // For other submission types with questions
        // Validate the request
        $request->validate([
            'grades' => 'required|array',
            'grades.*' => 'required|numeric|min:0',
            'feedback' => 'nullable|array',
            'general_feedback' => 'nullable|string'
        ]);
        
        // Calculate total score
        $totalScore = 0;
        $totalMaxScore = 0;
        $grades = [];
        $feedback = [];
        
        foreach ($allocation->questions as $question) {
            $questionId = $question->id;
            $maxScore = $question->weight;
            $totalMaxScore += $maxScore;
            
            // Get the score for this question (capped at max score)
            $score = min($request->input("grades.$questionId", 0), $maxScore);
            $grades[$questionId] = $score;
            $totalScore += $score;
            
            // Store feedback if provided
            if ($request->has("feedback.$questionId")) {
                $feedback[$questionId] = $request->input("feedback.$questionId");
            }
        }
        
        // Add general feedback if provided
        if ($request->has('general_feedback')) {
            $feedback['general'] = $request->input('general_feedback');
        }
        
        // Calculate percentage grade
        $percentageGrade = $totalMaxScore > 0 ? ($totalScore / $totalMaxScore) * 100 : 0;
        
        // Update the submission
        $submission->grades = $grades;
        $submission->feedback = $feedback;
        $submission->grade = round($percentageGrade, 2); // Store as percentage with 2 decimal places
        $submission->status = 'graded';
        $submission->graded_at = now();
        $submission->save();
        
        // If this is a group submission, update grades for all group members
        if ($isGroupSubmission && count($groupMembers) > 0) {
            foreach ($groupMembers as $student) {
                // Skip the student who owns the current submission
                if ($student->id === $submission->student_id) {
                    continue;
                }
                
                // Find or create a submission for this student
                $studentSubmission = AssessmentAllocationSubmission::firstOrCreate([
                    'assessment_allocation_id' => $allocation->id,
                    'student_id' => $student->id,
                    'group_id' => $submission->group_id
                ]);
                
                // Copy the grades and feedback
                $studentSubmission->grades = $grades;
                $studentSubmission->feedback = $feedback;
                $studentSubmission->grade = round($percentageGrade, 2);
                $studentSubmission->status = 'graded';
                $studentSubmission->graded_at = now();
                $studentSubmission->answers = $submission->answers; // Copy the answers
                $studentSubmission->submitted_at = $submission->submitted_at;
                $studentSubmission->save();
            }
        }
        
        return redirect()->route('admin.assessment-allocations.submissions.index', $allocation)
            ->with('success', 'Submission graded successfully.');
    }

    /**
     * Display a listing of submissions for an assessment (admin view).
     */
    public function indexAdmin(AssessmentAllocation $allocation)
    {
        $submissions = AssessmentAllocationSubmission::where('assessment_allocation_id', $allocation->id)
            ->with('student')
            ->paginate(15);
        
        return view('admin.submissions.index', compact('allocation', 'submissions'));
    }

    // Private helper methods

    private function getOrInitializeSubmission($student, $allocation)
    {
        return AssessmentAllocationSubmission::firstOrNew([
            'assessment_allocation_id' => $allocation->id,
            'student_id' => $student->id
        ]);
    }

    private function handleSubmissionTiming($submission, $allocation)
    {
        if ($allocation->is_timed && $submission->start_time && !$submission->isWithinTimeLimit()) {
            if ($submission->status !== 'submitted') {
                $submission->status = 'submitted';
                $submission->submitted_at = $submission->start_time->addMinutes($allocation->duration_minutes);
                $submission->save();
            }
            return true;
        }
        return false;
    }

    private function validateSubmissionState($submission, $allocation)
    {
        if ($allocation->due_date && now() > $allocation->due_date) {
            throw ValidationException::withMessages(['timing' => 'This assessment is past its due date.']);
        }

        if ($allocation->is_timed) {
            if (!$submission->start_time) {
                throw ValidationException::withMessages(['timing' => 'You must start the timed assessment first.']);
            }
            if (!$submission->isWithinTimeLimit()) {
                throw ValidationException::withMessages(['timing' => 'Time limit exceeded. You cannot submit anymore.']);
            }
        }
    }

    private function validateAndStoreContent(Request $request, $submission, $allocation)
    {
        $rules = $this->getValidationRules($allocation);
        $validator = Validator::make($request->all(), $rules['rules'], $rules['messages']);
        
        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }

        // Handle file uploads
        if ($request->hasFile('file')) {
            if ($submission->file_path) {
                Storage::delete($submission->file_path);
            }
            $path = $request->file('file')->store('submissions');
            $submission->file_path = $path;
        }

        // Handle online or text submissions
        if ($request->has('answers')) {
            // Ensure answers is stored as an array
            $answers = $request->answers;
            if (is_array($answers)) {
                // Convert any empty strings to null to avoid issues
                foreach ($answers as $key => $value) {
                    if ($value === '') {
                        $answers[$key] = null;
                    }
                }
                $submission->answers = $answers;
            }
        }
        if ($request->has('content')) {
            $submission->content = $request->content;
        }
    }

    private function getValidationRules($allocation)
    {
        $rules = [];
        $messages = [];

        switch ($allocation->submission_type) {
            case 'upload':
                $rules['file'] = ['required', 'file', 'max:10240']; // 10MB max
                $messages['file.max'] = 'The file must not be larger than 10MB.';
                break;
            case 'online':
                $rules['answers'] = ['required', 'array'];
                $rules['answers.*'] = ['required', 'string'];
                break;
            case 'in-class':
                $rules['content'] = ['required', 'string', 'max:10000'];
                break;
            case 'group':
                // For group submissions, check if it's a file upload or online submission
                if ($allocation->questions->isNotEmpty()) {
                    $rules['answers'] = ['required', 'array'];
                    $rules['answers.*'] = ['required', 'string'];
                } else {
                    $rules['file'] = ['required', 'file', 'max:10240']; // 10MB max
                    $messages['file.max'] = 'The file must not be larger than 10MB.';
                }
                break;
            default:
                throw ValidationException::withMessages(['type' => 'Invalid submission type.']);
        }

        return ['rules' => $rules, 'messages' => $messages];
    }

    private function canUpdateSubmission($submission, $allocation)
    {
        return !($allocation->due_date && now() > $allocation->due_date) && 
               $submission->status !== 'graded' && 
               (!$allocation->is_timed || $submission->isWithinTimeLimit());
    }
}
