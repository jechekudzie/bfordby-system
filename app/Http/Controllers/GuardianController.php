<?php

namespace App\Http\Controllers;

use App\Models\Guardian;
use App\Models\Student;
use Illuminate\Http\Request;

class GuardianController extends Controller
{
    public function index()
    {
        $guardians = Guardian::with('student')->paginate(10);
        return view('guardians.index', compact('guardians'));
    }

    public function create()
    {
        $students = Student::all();
        return view('guardians.create', compact('students'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'relationship' => 'required|string|max:255',
            'contact_details' => 'required|string'
        ]);

        Guardian::create($validated);

        return redirect()->route('guardians.index')
                        ->with('success', 'Guardian created successfully.');
    }

    public function show(Guardian $guardian)
    {
        return view('guardians.show', compact('guardian'));
    }

    public function edit(Guardian $guardian)
    {
        $students = Student::all();
        return view('guardians.edit', compact('guardian', 'students'));
    }

    public function update(Request $request, Guardian $guardian)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'relationship' => 'required|string|max:255',
            'contact_details' => 'required|string'
        ]);

        $guardian->update($validated);

        return redirect()->route('guardians.index')
                        ->with('success', 'Guardian updated successfully.');
    }

    public function destroy(Guardian $guardian)
    {
        $guardian->delete();
        return redirect()->route('guardians.index')
                        ->with('success', 'Guardian deleted successfully.');
    }
}
