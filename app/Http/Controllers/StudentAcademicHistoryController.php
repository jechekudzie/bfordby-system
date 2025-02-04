<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\AcademicHistory;
use App\Models\QualificationLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentAcademicHistoryController extends Controller
{
    public function create(Student $student)
    {
        $qualificationLevels = QualificationLevel::all();
        return view('students.academic-histories.create', compact('student', 'qualificationLevels'));
    }

    public function store(Request $request, Student $student)
    {
        $validated = $request->validate([
            'institution_name' => 'required|string|max:255',
            'qualification_level_id' => 'required|exists:qualification_levels,id',
            'program_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'completion_date' => 'required|date|after_or_equal:start_date',
            'grade_achieved' => 'nullable|string|max:255',
            'certificate_number' => 'nullable|string|max:255',
            'certificate_path' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'transcript_path' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'status' => 'required|in:completed,in_progress,incomplete,verified',
            'notes' => 'nullable|string'
        ]);

        // Handle certificate upload
        if ($request->hasFile('certificate_path')) {
            $validated['certificate_path'] = $request->file('certificate_path')
                ->store('certificates', 'public');
        }

        // Handle transcript upload
        if ($request->hasFile('transcript_path')) {
            $validated['transcript_path'] = $request->file('transcript_path')
                ->store('transcripts', 'public');
        }

        $student->academicHistories()->create($validated);

        return redirect()
            ->route('students.show', $student)
            ->with('success', 'Academic history added successfully');
    }

    public function edit(Student $student, AcademicHistory $academicHistory)
    {
        $qualificationLevels = QualificationLevel::all();
        return view('students.academic-histories.edit', compact('student', 'academicHistory', 'qualificationLevels'));
    }

    public function update(Request $request, Student $student, AcademicHistory $academicHistory)
    {
        $validated = $request->validate([
            'institution_name' => 'required|string|max:255',
            'qualification_level_id' => 'required|exists:qualification_levels,id',
            'program_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'completion_date' => 'required|date|after_or_equal:start_date',
            'grade_achieved' => 'nullable|string|max:255',
            'certificate_number' => 'nullable|string|max:255',
            'certificate_path' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'transcript_path' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'status' => 'required|in:completed,in_progress,incomplete,verified',
            'notes' => 'nullable|string'
        ]);

        // Handle certificate upload
        if ($request->hasFile('certificate_path')) {
            // Delete old file if exists
            if ($academicHistory->certificate_path) {
                Storage::disk('public')->delete($academicHistory->certificate_path);
            }
            $validated['certificate_path'] = $request->file('certificate_path')
                ->store('certificates', 'public');
        }

        // Handle transcript upload
        if ($request->hasFile('transcript_path')) {
            // Delete old file if exists
            if ($academicHistory->transcript_path) {
                Storage::disk('public')->delete($academicHistory->transcript_path);
            }
            $validated['transcript_path'] = $request->file('transcript_path')
                ->store('transcripts', 'public');
        }

        $academicHistory->update($validated);

        return redirect()
            ->route('students.show', $student)
            ->with('success', 'Academic history updated successfully');
    }

    public function destroy(Student $student, AcademicHistory $academicHistory)
    {
        // Delete associated files
        if ($academicHistory->certificate_path) {
            Storage::disk('public')->delete($academicHistory->certificate_path);
        }
        if ($academicHistory->transcript_path) {
            Storage::disk('public')->delete($academicHistory->transcript_path);
        }

        $academicHistory->delete();

        return redirect()
            ->route('students.show', $student)
            ->with('success', 'Academic history deleted successfully');
    }
}
