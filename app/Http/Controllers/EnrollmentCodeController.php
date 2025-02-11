<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\StudyMode;
use App\Models\EnrollmentCode;
use Illuminate\Http\Request;

class EnrollmentCodeController extends Controller
{
    /**
     * Display a listing of enrollment codes.
     */
    public function index()
    {
        $enrollmentCodes = EnrollmentCode::with(['course', 'studyMode'])
            ->latest()
            ->paginate(10);
            
        return view('admin.enrollment-codes.index', compact('enrollmentCodes'));
    }

    /**
     * Show the form for creating a new enrollment code.
     */
    public function create()
    {
        $courses = Course::where('status', 'active')->get();
        $studyModes = StudyMode::all();
        $currentYear = date('Y');
        
        return view('admin.enrollment-codes.create', compact('courses', 'studyModes', 'currentYear'));
    }

    /**
     * Store a newly created enrollment code.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'study_mode_id' => 'required|exists:study_modes,id',
            'year' => 'required|integer|min:' . date('Y'),
            'base_code' => 'required|string|max:10|unique:enrollment_codes',
        ]);

        // Set initial current_number to 0
        $validated['current_number'] = 0;

        EnrollmentCode::create($validated);

        return redirect()
            ->route('admin.enrollment-codes.index')
            ->with('success', 'Enrollment code created successfully.');
    }

    /**
     * Show the form for editing the specified enrollment code.
     */
    public function edit(EnrollmentCode $enrollmentCode)
    {
        $courses = Course::where('status', 'active')->get();
        $studyModes = StudyMode::all();
        $currentYear = date('Y');
        
        return view('admin.enrollment-codes.edit', compact('enrollmentCode', 'courses', 'studyModes', 'currentYear'));
    }

    /**
     * Update the specified enrollment code.
     */
    public function update(Request $request, EnrollmentCode $enrollmentCode)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'study_mode_id' => 'required|exists:study_modes,id',
            'year' => 'required|integer|min:' . date('Y'),
            'base_code' => 'required|string|max:10|unique:enrollment_codes,base_code,' . $enrollmentCode->id,
        ]);

        $enrollmentCode->update($validated);

        return redirect()
            ->route('admin.enrollment-codes.index')
            ->with('success', 'Enrollment code updated successfully.');
    }

    /**
     * Remove the specified enrollment code.
     */
    public function destroy(EnrollmentCode $enrollmentCode)
    {
        // Only allow deletion if no numbers have been assigned (current_number is 0)
        if ($enrollmentCode->current_number > 0) {
            return redirect()
                ->route('admin.enrollment-codes.index')
                ->with('error', 'Cannot delete enrollment code as it has been used for student enrollments.');
        }

        $enrollmentCode->delete();

        return redirect()
            ->route('admin.enrollment-codes.index')
            ->with('success', 'Enrollment code deleted successfully.');
    }
}
