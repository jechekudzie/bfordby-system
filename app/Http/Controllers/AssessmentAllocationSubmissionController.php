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
        if ($allocation->is_group_submission) {
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
            
            // Handle group submission
            if ($allocation->is_group_submission) {
                $group = $student->groups()->where('assessment_allocation_id', $allocation->id)->firstOrFail();
                $submission->group_id = $group->id;
            }

            // Validate and store submission content
            $this->validateAndStoreContent($request, $submission, $allocation);

            // Update submission status
            $submission->status = 'submitted';
            $submission->submitted_at = now();
            $submission->save();

            // Get the enrollment through the allocation's module
            $enrollment = $allocation->module->enrollments()
                ->where('student_id', $student->id)
                ->first();

            return redirect()->route('students.enrollments.show', [
                'student' => $student,
                'enrollment' => $enrollment
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

        return Storage::download($submission->file_path);
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

        return redirect()->route('students.submissions.show', $allocation);
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
            $submission->answers = $request->answers;
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
            case 'file':
                $rules['file'] = ['required', 'file', 'max:10240']; // 10MB max
                $messages['file.max'] = 'The file must not be larger than 10MB.';
                break;
            case 'online':
                $rules['answers'] = ['required', 'array'];
                $rules['answers.*'] = ['required', 'string'];
                break;
            case 'text':
                $rules['content'] = ['required', 'string', 'max:10000'];
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
