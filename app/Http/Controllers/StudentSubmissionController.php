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

        $submission = new StudentSubmission();
        $submission->student_id = auth()->user()->student->id;
        $submission->assessment_allocation_id = $allocation->id;
        
        // Handle different submission types
        switch ($allocation->submission_type) {
            case 'upload':
                if ($request->hasFile('file')) {
                    $submission->file_path = $request->file('file')->store('submissions', 'public');
                }
                break;
            case 'online':
                $submission->answers = $validated['answers'];
                break;
            default:
                $submission->content = $validated['content'];
        }

        $submission->status = 'submitted';
        $submission->submitted_at = now();
        $submission->save();

        return redirect()
            ->route('students.enrollments.show', $allocation->enrollmentCode->enrollment)
            ->with('success', 'Assessment submitted successfully.');
    }

    public function update(Request $request, AssessmentAllocation $allocation, StudentSubmission $submission)
    {
        // Similar validation and logic as store, but for updates
        // This allows students to update their submission before the due date
    }
} 