<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Subject;
use App\Models\Student;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    public function index()
    {
        $assessments = Assessment::with(['subject', 'student'])->paginate(10);
        return view('assessments.index', compact('assessments'));
    }

    public function create()
    {
        $subjects = Subject::all();
        $students = Student::all();
        return view('assessments.create', compact('subjects', 'students'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:assignment,test,exam',
            'max_score' => 'required|integer|min:0',
            'subject_id' => 'required|exists:subjects,id',
            'student_id' => 'required|exists:students,id',
            'score' => 'nullable|integer|min:0|max:' . $request->max_score
        ]);

        Assessment::create($validated);

        return redirect()->route('assessments.index')
                        ->with('success', 'Assessment created successfully.');
    }

    public function show(Assessment $assessment)
    {
        $assessment->load(['subject', 'student']);
        return view('assessments.show', compact('assessment'));
    }

    public function edit(Assessment $assessment)
    {
        $subjects = Subject::all();
        $students = Student::all();
        return view('assessments.edit', compact('assessment', 'subjects', 'students'));
    }

    public function update(Request $request, Assessment $assessment)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:assignment,test,exam',
            'max_score' => 'required|integer|min:0',
            'subject_id' => 'required|exists:subjects,id',
            'student_id' => 'required|exists:students,id',
            'score' => 'nullable|integer|min:0|max:' . $request->max_score
        ]);

        $assessment->update($validated);

        return redirect()->route('assessments.index')
                        ->with('success', 'Assessment updated successfully.');
    }

    public function destroy(Assessment $assessment)
    {
        $assessment->delete();
        return redirect()->route('assessments.index')
                        ->with('success', 'Assessment deleted successfully.');
    }

    // Additional method for bulk score entry
    public function bulkScoreEntry(Request $request)
    {
        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'scores' => 'required|array',
                'scores.*' => 'required|integer|min:0'
            ]);

            foreach ($validated['scores'] as $assessmentId => $score) {
                Assessment::where('id', $assessmentId)->update(['score' => $score]);
            }

            return redirect()->back()->with('success', 'Scores updated successfully.');
        }

        $assessments = Assessment::whereNull('score')
                               ->with(['subject', 'student'])
                               ->paginate(20);
        
        return view('assessments.bulk-score', compact('assessments'));
    }
}
