<?php

namespace App\Http\Controllers;

use App\Models\SickNote;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SickNoteController extends Controller
{
    public function index(Student $student)
    {
        $sickNotes = $student->sickNotes()->latest()->get();
        return view('students.health.sick-notes.index', compact('student', 'sickNotes'));
    }

    public function create(Student $student)
    {
        return view('students.health.sick-notes.create', compact('student'));
    }

    public function store(Request $request, Student $student)
    {
        $validated = $request->validate([
            'issue_date' => 'required|date',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after:valid_from',
            'diagnosis' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'issuing_doctor' => 'required|string|max:255',
            'medical_facility' => 'required|string|max:255',
            'document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        if ($request->hasFile('document')) {
            $path = $request->file('document')->store('sick-notes');
            $validated['document_path'] = $path;
        }

        $validated['student_id'] = $student->id;
        $sickNote = SickNote::create($validated);

        return redirect()
            ->route('students.health.sick-notes.show', [$student, $sickNote])
            ->with('success', 'Sick note created successfully.');
    }

    public function show(Student $student, SickNote $sickNote)
    {
        return view('students.health.sick-notes.show', compact('student', 'sickNote'));
    }

    public function edit(Student $student, SickNote $sickNote)
    {
        return view('students.health.sick-notes.edit', compact('student', 'sickNote'));
    }

    public function update(Request $request, Student $student, SickNote $sickNote)
    {
        $validated = $request->validate([
            'issue_date' => 'required|date',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after:valid_from',
            'diagnosis' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'issuing_doctor' => 'required|string|max:255',
            'medical_facility' => 'required|string|max:255',
            'document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        if ($request->hasFile('document')) {
            if ($sickNote->document_path) {
                Storage::delete($sickNote->document_path);
            }
            $path = $request->file('document')->store('sick-notes');
            $validated['document_path'] = $path;
        }

        $sickNote->update($validated);

        return redirect()
            ->route('students.health.sick-notes.show', [$student, $sickNote])
            ->with('success', 'Sick note updated successfully.');
    }

    public function destroy(Student $student, SickNote $sickNote)
    {
        if ($sickNote->document_path) {
            Storage::delete($sickNote->document_path);
        }
        
        $sickNote->delete();

        return redirect()
            ->route('students.health.sick-notes.index', $student)
            ->with('success', 'Sick note deleted successfully.');
    }

    public function download(Student $student, SickNote $sickNote)
    {
        if (!$sickNote->document_path || !Storage::exists($sickNote->document_path)) {
            return back()->with('error', 'Document not found.');
        }

        return Storage::download($sickNote->document_path);
    }
} 