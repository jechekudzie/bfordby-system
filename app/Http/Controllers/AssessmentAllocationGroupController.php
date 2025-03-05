<?php

namespace App\Http\Controllers;

use App\Models\AssessmentAllocation;
use App\Models\Group;
use App\Models\Student;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
class AssessmentAllocationGroupController extends Controller
{
    public function index(AssessmentAllocation $allocation)
    {
        $groups = $allocation->groups()->with('students')->paginate(10);
        return view('admin.assessment-allocation-groups.index', compact('allocation', 'groups'));
    }

    public function create(AssessmentAllocation $allocation)
    {
        // Get students through enrollments table directly
        $students = Student::join('enrollments', 'students.id', '=', 'enrollments.student_id')
            ->where('enrollments.enrollment_code_id', $allocation->enrollment_code_id)
            ->select('students.*')
            ->distinct()
            ->get();

            

        // Debug information
        if ($students->isEmpty()) {
            Log::info('No students found for allocation', [
                'allocation_id' => $allocation->id,
                'enrollment_code_id' => $allocation->enrollment_code_id,
                'enrollment_count' => \App\Models\Enrollment::where('enrollment_code_id', $allocation->enrollment_code_id)->count()
            ]);
        }
        
        return view('admin.assessment-allocation-groups.create', compact('allocation', 'students'));
    }

    public function store(Request $request, AssessmentAllocation $allocation)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'members' => 'required|array|min:2',
            'members.*' => [
                'required',
                'exists:students,id',
                function ($attribute, $value, $fail) use ($allocation) {
                    $isEnrolled = Enrollment::where('student_id', $value)
                        ->where('enrollment_code_id', $allocation->enrollment_code_id)
                        ->exists();

                    if (!$isEnrolled) {
                        $fail('Selected student is not enrolled in this course.');
                    }
                }
            ]
        ]);

        $group = new Group([
            'assessment_allocation_id' => $allocation->id,
            'name' => $validated['name']
        ]);

        $group->save();
        $group->students()->attach($validated['members']);

        return redirect()
            ->route('admin.assessment-allocation-groups.index', $allocation)
            ->with('success', 'Group created successfully');
    }

    public function show(AssessmentAllocation $allocation, Group $group)
    {
        $group->load(['students' => function($query) use ($allocation) {
            $query->with(['enrollments' => function($query) use ($allocation) {
                $query->where('enrollment_code_id', $allocation->enrollment_code_id);
            }]);
        }]);
        
        return view('admin.assessment-allocation-groups.show', compact('allocation', 'group'));
    }

    public function edit(AssessmentAllocation $allocation, Group $group)
    {
        $students = Student::whereHas('enrollments', function($query) use ($allocation) {
            $query->where('enrollment_code_id', $allocation->enrollment_code_id);
        })
        ->with(['enrollments' => function($query) use ($allocation) {
            $query->where('enrollment_code_id', $allocation->enrollment_code_id);
        }])
        ->get();
        
        $group->load('students');
        
        return view('admin.assessment-allocation-groups.edit', compact('allocation', 'group', 'students'));
    }

    public function update(Request $request, AssessmentAllocation $allocation, Group $group)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'members' => 'required|array|min:2',
            'members.*' => [
                'required',
                'exists:students,id',
                function ($attribute, $value, $fail) use ($allocation) {
                    $isEnrolled = Enrollment::where('student_id', $value)
                        ->where('enrollment_code_id', $allocation->enrollment_code_id)
                        ->exists();

                    if (!$isEnrolled) {
                        $fail('Selected student is not enrolled in this course.');
                    }
                }
            ]
        ]);

        $group->update([
            'name' => $validated['name']
        ]);

        // Simply sync the student IDs without any pivot data
        $group->students()->sync($validated['members']);

        return redirect()
            ->route('admin.assessment-allocation-groups.index', $allocation)
            ->with('success', 'Group updated successfully');
    }

    public function destroy(AssessmentAllocation $allocation, Group $group)
    {
        $group->students()->detach();
        $group->delete();

        return redirect()
            ->route('admin.assessment-allocation-groups.index', $allocation)
            ->with('success', 'Group deleted successfully');
    }

    public function addMember(Request $request, AssessmentAllocation $allocation, Group $group)
    {
        $validated = $request->validate([
            'student_id' => [
                'required',
                'exists:students,id',
                function ($attribute, $value, $fail) use ($allocation) {
                    $isEnrolled = Enrollment::where('student_id', $value)
                        ->where('enrollment_code_id', $allocation->enrollment_code_id)
                        ->exists();

                    if (!$isEnrolled) {
                        $fail('Selected student is not enrolled in this course.');
                    }
                }
            ]
        ]);

        $group->students()->attach($validated['student_id']);

        return redirect()
            ->back()
            ->with('success', 'Member added successfully');
    }

    public function removeMember(AssessmentAllocation $allocation, Group $group, Student $member)
    {
        if ($group->students()->where('student_id', $member->id)->exists() &&
            Enrollment::where('student_id', $member->id)
                     ->where('enrollment_code_id', $allocation->enrollment_code_id)
                     ->exists()) {
            $group->students()->detach($member->id);
            $message = 'success';
            $text = 'Member removed successfully';
        } else {
            $message = 'error';
            $text = 'Unable to remove member. Student not found in group or not enrolled.';
        }

        return redirect()
            ->back()
            ->with($message, $text);
    }
} 