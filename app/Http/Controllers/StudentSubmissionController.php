<?php

namespace App\Http\Controllers;

use App\Models\AssessmentAllocation;
use App\Models\StudentSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentSubmissionController extends Controller
{
    public function show(AssessmentAllocation $allocation)
    {
        $submission = StudentSubmission::where('student_id', auth()->user()->student->id)
            ->where('assessment_allocation_id', $allocation->id)
            ->first();

        return view('students.submissions.show', compact('allocation', 'submission'));
    }

    public function store(Request $request, AssessmentAllocation $allocation)
    {
        $validated = $request->validate([
            'content' => 'required_if:submission_type,text|nullable|string',
            'file' => 'required_if:submission_type,file|nullable|file|max:10240', // 10MB max
            'answers' => 'required_if:submission_type,online|nullable|array',
            'answers.*' => 'required_if:submission_type,online|nullable|string'
        ]);

        // Check if there's an existing submission
        $submission = StudentSubmission::where('student_id', auth()->user()->student->id)
            ->where('assessment_allocation_id', $allocation->id)
            ->first();

        if (!$submission) {
            $submission = new StudentSubmission();
            $submission->student_id = auth()->user()->student->id;
            $submission->assessment_allocation_id = $allocation->id;
        } elseif ($submission->status === 'submitted') {
            return back()->with('error', 'You have already submitted this assessment.');
        }
        
        // Handle different submission types
        switch ($allocation->submission_type) {
            case 'upload':
                if ($request->hasFile('file')) {
                    // Delete old file if it exists
                    if ($submission->file_path) {
                        Storage::disk('public')->delete($submission->file_path);
                    }
                    $submission->file_path = $request->file('file')->store('submissions', 'public');
                }
                break;
            case 'online':
                $submission->answers = $validated['answers'];
                break;
            default:
                $submission->content = $validated['content'];
        }

        // Mark as submitted
        $submission->status = 'submitted';
        $submission->submitted_at = now();
        $submission->save();

        // Find the enrollment using the same robust lookup logic
        $student = auth()->user()->student;
        $enrollment = null;

        // Try to find the enrollment in this order:
        // 1. By enrollment code if available
        // 2. By course if the assessment is linked to a course
        // 3. Latest enrollment for the student
        if ($allocation->enrollmentCode) {
            $enrollment = $student->enrollments()
                ->where('enrollment_code_id', $allocation->enrollmentCode->id)
                ->first();
        }
        
        if (!$enrollment && $allocation->assessment && $allocation->assessment->module && $allocation->assessment->module->subject) {
            $courseId = $allocation->assessment->module->subject->course_id;
            $enrollment = $student->enrollments()
                ->where('course_id', $courseId)
                ->latest()
                ->first();
        }
        
        if (!$enrollment) {
            $enrollment = $student->enrollments()
                ->latest()
                ->first();
        }

        if (!$enrollment) {
            // If no enrollment is found, redirect to a safe fallback route
            return redirect()->route('students.index')
                ->with('success', 'Assessment submitted successfully.')
                ->with('warning', 'Could not find associated enrollment details.');
        }

        return redirect()->route('students.enrollments.show', [
            'student' => $student,
            'enrollment' => $enrollment
        ])->with('success', 'Assessment submitted successfully.');
    }

    public function update(Request $request, AssessmentAllocation $allocation, StudentSubmission $submission)
    {
        // Similar validation and logic as store, but for updates
        // This allows students to update their submission before the due date
    }
}
