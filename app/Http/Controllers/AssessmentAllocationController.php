<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\Assessment;
use App\Models\AssessmentAllocation;
use App\Models\EnrollmentCode;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssessmentAllocationController extends Controller
{
    public function index(Module $module, Assessment $assessment)
    {
        $allocations = AssessmentAllocation::where('assessment_id', $assessment->id)
            ->with(['enrollmentCode', 'semester'])
            ->latest()
            ->paginate(10);

        // Get enrollment codes for the module's course
        $enrollmentCodes = EnrollmentCode::where('course_id', $module->subject->course_id)->get();
        $semesters = Semester::where('status', 'active')->get();

        return view('admin.assessment-allocations.index', compact(
            'module',
            'assessment',
            'allocations',
            'enrollmentCodes',
            'semesters'
        ));
    }

    public function create(Module $module, Assessment $assessment)
    {
        $enrollmentCodes = EnrollmentCode::where('course_id', $module->subject->course_id)->get();
        $semesters = Semester::where('status', 'active')->get();

        return view('admin.assessment-allocations.create', compact(
            'module',
            'assessment',
            'enrollmentCodes',
            'semesters'
        ));
    }

    public function store(Request $request, Module $module, Assessment $assessment)
    {
        $validated = $request->validate([
            'enrollment_code_id' => 'required|exists:enrollment_codes,id',
            'semester_id' => 'required|exists:semesters,id',
            'due_date' => 'required|date',
            'content' => 'nullable|string',
            'file' => 'nullable|file|max:10240' // 10MB max
        ]);

        $allocation = new AssessmentAllocation();
        $allocation->assessment_id = $assessment->id;
        $allocation->enrollment_code_id = $validated['enrollment_code_id'];
        $allocation->semester_id = $validated['semester_id'];
        $allocation->due_date = $validated['due_date'];
        $allocation->content = $validated['content'] ?? null;

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('assessment-allocations', 'public');
            $allocation->file_path = $path;
        }

        $allocation->save();

        return redirect()
            ->route('modules.assessments.allocations.index', [$module, $assessment])
            ->with('success', 'Assessment allocation created successfully.');
    }

    public function edit(Module $module, Assessment $assessment, AssessmentAllocation $allocation)
    {
        $enrollmentCodes = EnrollmentCode::where('course_id', $module->subject->course_id)->get();
        $semesters = Semester::where('status', 'active')->get();

        return view('admin.assessment-allocations.edit', compact(
            'module',
            'assessment',
            'allocation',
            'enrollmentCodes',
            'semesters'
        ));
    }

    public function update(Request $request, Module $module, Assessment $assessment, AssessmentAllocation $allocation)
    {
        $validated = $request->validate([
            'enrollment_code_id' => 'required|exists:enrollment_codes,id',
            'semester_id' => 'required|exists:semesters,id',
            'due_date' => 'required|date',
            'content' => 'nullable|string',
            'file' => 'nullable|file|max:10240',
            'status' => 'required|in:pending,submitted,graded'
        ]);

        $allocation->enrollment_code_id = $validated['enrollment_code_id'];
        $allocation->semester_id = $validated['semester_id'];
        $allocation->due_date = $validated['due_date'];
        $allocation->content = $validated['content'] ?? null;
        $allocation->status = $validated['status'];

        if ($request->hasFile('file')) {
            if ($allocation->file_path) {
                Storage::disk('public')->delete($allocation->file_path);
            }
            $path = $request->file('file')->store('assessment-allocations', 'public');
            $allocation->file_path = $path;
        }

        $allocation->save();

        return redirect()
            ->route('modules.assessments.allocations.index', [$module, $assessment])
            ->with('success', 'Assessment allocation updated successfully.');
    }

    public function destroy(Module $module, Assessment $assessment, AssessmentAllocation $allocation)
    {
        if ($allocation->file_path) {
            Storage::disk('public')->delete($allocation->file_path);
        }

        $allocation->delete();

        return redirect()
            ->route('modules.assessments.allocations.index', [$module, $assessment])
            ->with('success', 'Assessment allocation deleted successfully.');
    }
}
