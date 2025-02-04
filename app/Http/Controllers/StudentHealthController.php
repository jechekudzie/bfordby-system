<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentHealth;
use Illuminate\Http\Request;

class StudentHealthController extends Controller
{
    public function create(Student $student)
    {
        $bloodGroups = [
            'A+' => 'A+',
            'A-' => 'A-',
            'B+' => 'B+',
            'B-' => 'B-',
            'O+' => 'O+',
            'O-' => 'O-',
            'AB+' => 'AB+',
            'AB-' => 'AB-',
        ];
        
        return view('students.health.create', compact('student', 'bloodGroups'));
    }

    public function store(Request $request, Student $student)
    {
        $validated = $request->validate([
            'blood_group' => 'nullable|string|in:A+,A-,B+,B-,O+,O-,AB+,AB-',
            'allergies' => 'nullable|array',
            'allergies.*' => 'nullable|string|max:255',
            'medical_conditions' => 'nullable|array',
            'medical_conditions.*' => 'nullable|string|max:255',
            'medications' => 'nullable|array',
            'medications.*' => 'nullable|string|max:255',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_phone' => 'required|string|max:255',
            'emergency_contact_relationship' => 'required|string|max:255',
            'last_checkup_date' => 'nullable|date',
            'notes' => 'nullable|string'
        ]);

        // Clean up empty values from arrays
        foreach (['allergies', 'medical_conditions', 'medications'] as $field) {
            if (isset($validated[$field])) {
                $validated[$field] = array_values(array_filter($validated[$field], function($value) {
                    return !empty(trim($value));
                }));
                
                $validated[$field] = empty($validated[$field]) ? null : json_encode($validated[$field]);
            }
        }

        $student->studentHealth()->create($validated);

        return redirect()
            ->route('students.show', $student)
            ->with('success', 'Health information added successfully');
    }

    public function edit(Student $student)
    {
        $health = $student->studentHealth;
        
        if ($health) {
            foreach (['allergies', 'medical_conditions', 'medications'] as $field) {
                $health->$field = $health->$field ? json_decode($health->$field, true) : [];
            }
        }

        $bloodGroups = [
            'A+' => 'A+',
            'A-' => 'A-',
            'B+' => 'B+',
            'B-' => 'B-',
            'O+' => 'O+',
            'O-' => 'O-',
            'AB+' => 'AB+',
            'AB-' => 'AB-',
        ];

        return view('students.health.edit', compact('student', 'health', 'bloodGroups'));
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'blood_group' => 'nullable|string|in:A+,A-,B+,B-,O+,O-,AB+,AB-',
            'allergies' => 'nullable|array',
            'allergies.*' => 'nullable|string|max:255',
            'medical_conditions' => 'nullable|array',
            'medical_conditions.*' => 'nullable|string|max:255',
            'medications' => 'nullable|array',
            'medications.*' => 'nullable|string|max:255',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_phone' => 'required|string|max:255',
            'emergency_contact_relationship' => 'required|string|max:255',
            'last_checkup_date' => 'nullable|date',
            'notes' => 'nullable|string'
        ]);

        foreach (['allergies', 'medical_conditions', 'medications'] as $field) {
            if (isset($validated[$field])) {
                $validated[$field] = array_values(array_filter($validated[$field], function($value) {
                    return !empty(trim($value));
                }));
                
                $validated[$field] = empty($validated[$field]) ? null : json_encode($validated[$field]);
            }
        }

        $student->studentHealth()->update($validated);

        return redirect()
            ->route('students.show', $student)
            ->with('success', 'Health information updated successfully');
    }
}
