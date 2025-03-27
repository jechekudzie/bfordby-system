<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\AssessmentAllocation;
use App\Models\AssessmentAllocationSubmission;
use Illuminate\Http\Request;

class StudentAssessmentController extends Controller
{
    /**
     * Display a listing of the student's assessments.
     */
    public function index(Request $request)
    {
        $student = auth()->user()->student;
        
        // Get all assessments allocated to the student's modules
        $assessments = AssessmentAllocation::whereHas('assessment.module', function($query) use ($student) {
            $query->whereHas('subject', function($q) use ($student) {
                $q->whereHas('course', function($cq) use ($student) {
                    $cq->whereHas('enrollments', function($eq) use ($student) {
                        $eq->where('student_id', $student->id);
                    });
                });
            });
        })
        ->with([
            'assessment.module.subject',
            'submissions' => function($query) use ($student) {
                $query->where('student_id', $student->id);
            },
            'semester'
        ])
        ->orderBy('due_date')
        ->get()
        ->groupBy('semester.name');

        return view('students.assessments.list', [
            'assessments' => $assessments,
            'student' => $student
        ]);
    }

    /**
     * Show the assessment submission form or current submission.
     */
    public function show(AssessmentAllocation $allocation)
    {
        $student = auth()->user()->student;
        
        $submission = AssessmentAllocationSubmission::firstOrNew([
            'assessment_allocation_id' => $allocation->id,
            'student_id' => $student->id
        ]);

        return view('students.submissions.show', compact('allocation', 'submission', 'student'));
    }
}
