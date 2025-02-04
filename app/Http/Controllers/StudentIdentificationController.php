<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Identification;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class StudentIdentificationController extends Controller
{
    public function create(Student $student)
    {
        $countries = Country::orderBy('name')->get();
        $identificationTypes = [
            'national_id' => 'National ID',
            'passport' => 'Passport',
            'birth_certificate' => 'Birth Certificate',
            'drivers_license' => 'Driver\'s License'
        ];
        
        return view('students.identifications.create', compact('student', 'countries', 'identificationTypes'));
    }

    public function store(Request $request, Student $student)
    {
        $validated = $request->validate([
            'type' => 'required|in:national_id,passport,birth_certificate,drivers_license',
            'number' => 'required|string|max:255|unique:identifications,number',
            'issue_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after:issue_date',
            'issuing_authority' => 'required|string|max:255',
            'issuing_country_id' => 'required|exists:countries,id',
            'document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'notes' => 'nullable|string',
            'status' => 'required|in:active,expired,pending_verification'
        ]);

        if ($request->hasFile('document')) {
            $validated['document'] = $request->file('document')->store('identifications', 'public');
        }

        $student->identifications()->create($validated);

        return redirect()
            ->route('students.show', $student)
            ->with('success', 'Identification added successfully');
    }

    public function edit(Student $student, Identification $identification)
    {
        $countries = Country::orderBy('name')->get();
        $identificationTypes = [
            'national_id' => 'National ID',
            'passport' => 'Passport',
            'birth_certificate' => 'Birth Certificate',
            'drivers_license' => 'Driver\'s License'
        ];
        
        return view('students.identifications.edit', compact('student', 'identification', 'countries', 'identificationTypes'));
    }

    public function update(Request $request, Student $student, Identification $identification)
    {
        $validated = $request->validate([
            'type' => 'required|in:national_id,passport,birth_certificate,drivers_license',
            'number' => 'required|string|max:255|unique:identifications,number,' . $identification->id,
            'issue_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after:issue_date',
            'issuing_authority' => 'required|string|max:255',
            'issuing_country_id' => 'required|exists:countries,id',
            'document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'notes' => 'nullable|string',
            'status' => 'required|in:active,expired,pending_verification'
        ]);

        if ($request->hasFile('document')) {
            // Delete old file if exists
            if ($identification->document) {
                Storage::disk('public')->delete($identification->document);
            }
            
            $validated['document'] = $request->file('document')->store('identifications', 'public');
        }

        $identification->update($validated);

        return redirect()
            ->route('students.show', $student)
            ->with('success', 'Identification updated successfully');
    }

    public function destroy(Student $student, Identification $identification)
    {
        if ($identification->document) {
            Storage::disk('public')->delete($identification->document);
        }

        $identification->delete();

        return redirect()
            ->route('students.show', $student)
            ->with('success', 'Identification deleted successfully');
    }
}
