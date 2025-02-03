<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Guardian;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GuardianController extends Controller
{
    public function index()
    {
        $guardians = Guardian::with('student')->paginate(10);
        return view('guardians.index', compact('guardians'));
    }

    public function create(Student $student)
    {
        return view('students.guardians.create', compact('student'));
    }

    public function store(Request $request, Student $student)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'relationship' => 'nullable|string|max:255',
            'contact_details' => 'nullable|string'
        ]);

        $guardian = new Guardian($validated);
        $guardian->student_id = $student->id;
        $guardian->slug = Str::slug($student->name . '-' . $validated['first_name'] . '-' . $validated['last_name'] . '-' . uniqid());
        $guardian->save();

        return redirect()
            ->route('students.show', $student)
            ->with('success', 'Guardian added successfully');
    }

    public function show(Guardian $guardian)
    {
        return view('guardians.show', compact('guardian'));
    }

    public function edit(Student $student, Guardian $guardian)
    {
        return view('students.guardians.edit', compact('student', 'guardian'));
    }

    public function update(Request $request, Student $student, Guardian $guardian)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'relationship' => 'nullable|string|max:255',
            'contact_details' => 'nullable|string'
        ]);

        $guardian->update($validated);

        return redirect()
            ->route('students.show', $student)
            ->with('success', 'Guardian updated successfully');
    }

    public function destroy(Student $student, Guardian $guardian)
    {
        $guardian->delete();

        return redirect()
            ->route('students.show', $student)
            ->with('success', 'Guardian deleted successfully');
    }
}
