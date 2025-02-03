<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StudentLanguageController extends Controller
{
    public function create(Student $student)
    {
        return view('students.languages.create', compact('student'));
    }

    public function store(Request $request, Student $student)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                // Prevent duplicate languages for the same student
                function ($attribute, $value, $fail) use ($student) {
                    if ($student->languages()->where('name', $value)->exists()) {
                        $fail('This language has already been added for this student.');
                    }
                },
            ],
            'proficiency_level' => 'required|in:beginner,intermediate,advanced,native',
            'is_native' => 'boolean',
            'speaking' => 'required|in:poor,fair,good,excellent',
            'writing' => 'required|in:poor,fair,good,excellent',
            'reading' => 'required|in:poor,fair,good,excellent',
            'listening' => 'required|in:poor,fair,good,excellent',
        ]);

        // Create the language
        $student->languages()->create($validated);

        return redirect()
            ->route('students.show', $student)
            ->with('success', 'Language added successfully');
    }

    public function edit(Student $student, Language $language)
    {
        return view('students.languages.edit', compact('student', 'language'));
    }

    public function update(Request $request, Student $student, Language $language)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                // Prevent duplicate languages for the same student, excluding current one
                function ($attribute, $value, $fail) use ($student, $language) {
                    if ($student->languages()
                              ->where('name', $value)
                              ->where('id', '!=', $language->id)
                              ->exists()) {
                        $fail('This language has already been added for this student.');
                    }
                },
            ],
            'proficiency_level' => 'required|in:beginner,intermediate,advanced,native',
            'is_native' => 'boolean',
            'speaking' => 'required|in:poor,fair,good,excellent',
            'writing' => 'required|in:poor,fair,good,excellent',
            'reading' => 'required|in:poor,fair,good,excellent',
            'listening' => 'required|in:poor,fair,good,excellent',
        ]);

        $language->update($validated);

        return redirect()
            ->route('students.show', $student)
            ->with('success', 'Language updated successfully');
    }

    public function destroy(Student $student, Language $language)
    {
        $language->delete();

        return redirect()
            ->route('students.show', $student)
            ->with('success', 'Language deleted successfully');
    }
}
