<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Course;
use App\Models\Subject;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AssessmentController extends Controller
{
    public function index(Subject $subject, Module $module)
    {
        
        $assessments = Assessment::where('module_id', $module->id)
            ->latest()
            ->paginate(10);

        $course = $subject->course;

        return view('admin.assessments.index', compact('assessments', 'course', 'subject', 'module'));
    }

    public function create(Subject $subject, Module $module)
    {
    
        $course = $subject->course;

        return view('admin.assessments.create', compact('course', 'subject', 'module'));
    }

    public function store(Request $request, Course $course, Subject $subject, Module $module)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:assignment,test,exam,practical,theory',
            'max_score' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:draft,published,archived'
        ]);

        $validated['module_id'] = $module->id;
        $validated['slug'] = Str::slug($request->name) . '-' . Str::random(8);

        Assessment::create($validated);

        return redirect()
            ->route('admin.courses.subjects.modules.assessments.index', [$subject->slug, $module])
            ->with('success', 'Assessment created successfully.');
    }

    public function edit(Subject $subject, Module $module, Assessment $assessment)
    {
        $course = $subject->course;

        return view('admin.assessments.edit', compact('course', 'subject', 'module', 'assessment'));
    }

    public function update(Request $request, Course $course, Subject $subject, Module $module, Assessment $assessment)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:assignment,test,exam,practical,theory',
            'max_score' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:draft,published,archived'
        ]);

        $assessment->update($validated);

        return redirect()
            ->route('admin.courses.subjects.modules.assessments.index', [$subject->slug, $module])
            ->with('success', 'Assessment updated successfully.');
    }

    public function destroy(Subject $subject, Module $module, Assessment $assessment)
    {
        $assessment->delete();

        return redirect()
            ->route('admin.courses.subjects.modules.assessments.index', [$subject->slug, $module])
            ->with('success', 'Assessment deleted successfully.');
    }
}
