<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with('subjects')->get();
        return view('admin.courses.index', compact('courses'));
    }

    public function create()
    {
        return view('admin.courses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:courses,code|max:50',
            'description' => 'nullable|string',
            'duration_months' => 'nullable|integer',
            'fee' => 'nullable|numeric',
            'study_mode' => 'required|in:full-time,part-time',
            'status' => 'required|in:active,inactive'
        ]);

        Course::create($validated);

        return redirect()->route('admin.courses.index')
                        ->with('success', 'Course created successfully.');
    }

    public function show(Course $course)
    {
        $course->load('subjects');
        return view('admin.courses.show', compact('course'));
    }

    public function edit(Course $course)
    {
        return view('admin.courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:courses,code,'.$course->id.'|max:50',
            'description' => 'nullable|string',
            'duration_months' => 'nullable|integer',
            'fee' => 'nullable|numeric',
            'study_mode' => 'required|in:full-time,part-time',
            'status' => 'required|in:active,inactive'
        ]);

        $course->update($validated);

        return redirect()->route('admin.courses.index')
                        ->with('success', 'Course updated successfully.');
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return redirect()->route('admin.courses.index')
                        ->with('success', 'Course deleted successfully.');
    }
}
