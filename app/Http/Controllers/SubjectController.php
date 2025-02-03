<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Course;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::with(['course', 'assessments'])->get();
        return view('admin.subjects.index', compact('subjects'));
    }

    public function create()
    {
        $courses = Course::all();
        return view('admin.subjects.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:subjects,code|max:50',
            'course_id' => 'required|exists:courses,id',
            'credits' => 'nullable|integer',
            'level' => 'nullable|string|in:beginner,intermediate,advanced',
            'status' => 'required|string|in:active,inactive'
        ]);

        Subject::create($validated);

        return redirect()->route('admin.subjects.index')
                        ->with('success', 'Subject created successfully.');
    }

    public function show(Subject $subject)
    {
        $subject->load(['course', 'assessments']);
        return view('admin.subjects.show', compact('subject'));
    }

    public function edit(Subject $subject)
    {
        $courses = Course::all();
        return view('admin.subjects.edit', compact('subject', 'courses'));
    }

    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:subjects,code,'.$subject->id.'|max:50',
            'course_id' => 'required|exists:courses,id',
            'credits' => 'nullable|integer',
            'level' => 'nullable|string|in:beginner,intermediate,advanced',
            'status' => 'required|string|in:active,inactive'
        ]);

        $subject->update($validated);

        return redirect()->route('admin.subjects.index')
                        ->with('success', 'Subject updated successfully.');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();
        return redirect()->route('admin.subjects.index')
                        ->with('success', 'Subject deleted successfully.');
    }
}
