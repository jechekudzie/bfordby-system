<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentDisciplinary;
use Illuminate\Http\Request;

class StudentDisciplinaryController extends Controller
{
    public function create(Student $student)
    {
        $incidentTypes = StudentDisciplinary::INCIDENT_TYPES;
        $sanctions = StudentDisciplinary::SANCTIONS;

        return view('students.disciplinaries.create', compact('student', 'incidentTypes', 'sanctions'));
    }

    public function store(Request $request, Student $student)
    {
        $validated = $request->validate([
            'incident_date' => 'required|date',
            'incident_type' => 'required|string|in:' . implode(',', array_keys(StudentDisciplinary::INCIDENT_TYPES)),
            'description' => 'required|string',
            'location' => 'nullable|string|max:255',
            'reported_by' => 'required|string|max:255',
            'witnesses' => 'nullable|array',
            'witnesses.*' => 'string|max:255',
            'action_taken' => 'required|string',
            'sanction' => 'nullable|string|in:' . implode(',', array_keys(StudentDisciplinary::SANCTIONS)),
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:' . implode(',', StudentDisciplinary::STATUSES),
            'notes' => 'nullable|string'
        ]);

        // Convert witnesses array to JSON if present
        if (isset($validated['witnesses'])) {
            $validated['witnesses'] = array_filter($validated['witnesses']); // Remove empty values
            $validated['witnesses'] = json_encode($validated['witnesses']);
        }

        $student->disciplinaries()->create($validated);

        return redirect()
            ->route('students.show', $student)
            ->with('success', 'Disciplinary record added successfully');
    }

    public function edit(Student $student, StudentDisciplinary $disciplinary)
    {
        $incidentTypes = StudentDisciplinary::INCIDENT_TYPES;
        $sanctions = StudentDisciplinary::SANCTIONS;

        // Decode witnesses JSON for form
        $disciplinary->witnesses = $disciplinary->witnesses ? json_decode($disciplinary->witnesses, true) : [];

        return view('students.disciplinaries.edit', compact('student', 'disciplinary', 'incidentTypes', 'sanctions'));
    }

    public function update(Request $request, Student $student, StudentDisciplinary $disciplinary)
    {
        $validated = $request->validate([
            'incident_date' => 'required|date',
            'incident_type' => 'required|string|in:' . implode(',', array_keys(StudentDisciplinary::INCIDENT_TYPES)),
            'description' => 'required|string',
            'location' => 'nullable|string|max:255',
            'reported_by' => 'required|string|max:255',
            'witnesses' => 'nullable|array',
            'witnesses.*' => 'string|max:255',
            'action_taken' => 'required|string',
            'sanction' => 'nullable|string|in:' . implode(',', array_keys(StudentDisciplinary::SANCTIONS)),
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:' . implode(',', StudentDisciplinary::STATUSES),
            'notes' => 'nullable|string'
        ]);

        // Convert witnesses array to JSON if present
        if (isset($validated['witnesses'])) {
            $validated['witnesses'] = array_filter($validated['witnesses']); // Remove empty values
            $validated['witnesses'] = json_encode($validated['witnesses']);
        }

        $disciplinary->update($validated);

        return redirect()
            ->route('students.show', $student)
            ->with('success', 'Disciplinary record updated successfully');
    }

    public function destroy(Student $student, StudentDisciplinary $disciplinary)
    {
        $disciplinary->delete();

        return redirect()
            ->route('students.show', $student)
            ->with('success', 'Disciplinary record deleted successfully');
    }
}
