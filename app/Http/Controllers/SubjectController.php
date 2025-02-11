<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Course;
use App\Models\Assessment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubjectController extends Controller
{
    public function index(Course $course)
    {
        $subjects = Subject::where('course_id', $course->id)
            ->latest()
            ->paginate(10);

        return view('admin.subjects.index', compact('subjects', 'course'));
    }

    public function create(Course $course)
    {
        return view('admin.subjects.create', compact('course'));
    }

    public function store(Request $request, Course $course)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:subjects,code',
            'description' => 'nullable|string',
            'credit_hours' => 'required|numeric|min:0|max:99.99',
        ]);

        $validated['course_id'] = $course->id;
        $validated['slug'] = Str::slug($request->name) . '-' . Str::random(8);

        Subject::create($validated);

        return redirect()
            ->route('admin.courses.subjects.index', $course)
            ->with('success', 'Subject created successfully.');
    }

    public function show(Subject $subject)
    {
        $subject->load(['course', 'assessments']);
        return view('admin.subjects.show', compact('subject'));
    }

    public function edit(Course $course, Subject $subject)
    {
        return view('admin.subjects.edit', compact('course', 'subject'));
    }

    public function update(Request $request, Course $course, Subject $subject)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:subjects,code,' . $subject->id,
            'description' => 'nullable|string',
            'credit_hours' => 'required|numeric|min:0|max:99.99',
        ]);

        $subject->update($validated);

        return redirect()
            ->route('admin.courses.subjects.index', $course)
            ->with('success', 'Subject updated successfully.');
    }

    public function destroy(Course $course, Subject $subject)
    {
        $subject->delete();

        return redirect()
            ->route('admin.courses.subjects.index', $course)
            ->with('success', 'Subject deleted successfully.');
    }

    public function assessments(Subject $subject)
    {
        $assessments = Assessment::where('subject_id', $subject->id)
            ->latest()
            ->paginate(10);
        
        return view('admin.subjects.assessments', compact('subject', 'assessments'));
    }
}
