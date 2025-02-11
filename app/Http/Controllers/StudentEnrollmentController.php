<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Course;
use App\Models\StudyMode;
use App\Models\EnrollmentCode;
use App\Models\Enrollment;
use App\Models\AssessmentAllocation;
use Illuminate\Http\Request;

class StudentEnrollmentController extends Controller
{
    public function create(Student $student)
    {
        $courses = Course::all();
        $studyModes = StudyMode::all();
        $statuses = ['active', 'completed', 'withdrawn', 'repeat'];
        
        return view('students.enrollments.create', compact('student', 'courses', 'studyModes', 'statuses'));
    }

    public function store(Request $request, Student $student)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'study_mode_id' => 'required|exists:study_modes,id',
            'enrollment_date' => 'required|date',
            'status' => 'required|in:active,completed,withdrawn,repeat',
            'enrollment_code_id' => 'required|exists:enrollment_codes,id'
        ]);

        $enrollment = $student->enrollments()->create($validated);

        return redirect()
            ->route('students.show', $student)
            ->with('success', 'Student successfully enrolled in course.');
    }

    private function generateBaseCode($courseId, $studyModeId)
    {
        $course = Course::find($courseId);
        $studyMode = StudyMode::find($studyModeId);
        
        return sprintf(
            "%s-%s-%s",
            strtoupper($course->code),
            strtoupper(substr($studyMode->name, 0, 2)),
            date('Y')
        );
    }

    public function show(Student $student, Enrollment $enrollment)
    {
        // Load the enrollment with all necessary relationships
        $enrollment->load([
            'student',
            'course.subjects.modules.assessments.allocations' => function($query) use ($enrollment) {
                $query->where('enrollment_code_id', $enrollment->enrollment_code_id);
            },
            'enrollmentCode',
            'studyMode'
        ]);

        // Get all subjects for the enrolled course
        $subjects = $enrollment->course->subjects;

        // Get all assessment allocations for this enrollment code
        $allocations = AssessmentAllocation::with([
                'assessment.module.subject',
                'semester',
                'enrollmentCode'
            ])
            ->where('enrollment_code_id', $enrollment->enrollment_code_id)
            ->get()
            ->groupBy('semester.name');

        // Group assessments by subject
        $subjectAssessments = [];
        foreach ($subjects as $subject) {
            $subjectAssessments[$subject->id] = [];
            foreach ($allocations as $semesterName => $semesterAllocations) {
                $subjectAssessments[$subject->id][$semesterName] = $semesterAllocations
                    ->filter(function($allocation) use ($subject) {
                        return $allocation->assessment->module->subject_id === $subject->id;
                    });
            }
        }

        // Add debug information
        \Log::info('Enrollment Details:', [
            'enrollment_id' => $enrollment->id,
            'course' => $enrollment->course->name,
            'subjects_count' => $subjects->count(),
            'allocations_count' => $allocations->count()
        ]);

        return view('students.enrollments.show', [
            'enrollment' => $enrollment,
            'subjects' => $subjects,
            'allocations' => $allocations,
            'subjectAssessments' => $subjectAssessments
        ]);
    }

    // Add method to fetch enrollment codes via AJAX
    public function getEnrollmentCodes(Request $request)
    {
        $codes = EnrollmentCode::where([
            'course_id' => $request->course_id,
            'study_mode_id' => $request->study_mode_id,
            'year' => date('Y')
        ])->get();

        return response()->json($codes);
    }
}
