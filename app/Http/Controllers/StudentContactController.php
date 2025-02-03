<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Contact;
use App\Models\ContactType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StudentContactController extends Controller
{
    //
    public function index()
    {
        return view('students.contacts.index');
    }

    public function create(Student $student)
    {
        $contactTypes = ContactType::all();
        return view('students.contacts.create', compact('student', 'contactTypes'));
    }

    public function store(Request $request, Student $student)
    {
        $validated = $request->validate([
            'contact_type_id' => 'required|exists:contact_types,id',
            'value' => [
                'required',
                'string',
                'max:255',
                Rule::unique('contacts')->where(function ($query) use ($student) {
                    return $query->where('student_id', $student->id);
                })
            ],
            'is_primary' => 'nullable|boolean'
        ], [
            'value.unique' => 'This contact value is already registered for this student.'
        ]);

        // Set is_primary to false if not checked
        $validated['is_primary'] = $request->has('is_primary');

        // If this is marked as primary, remove primary from other contacts of same type
        if ($validated['is_primary']) {
            $student->contacts()
                ->where('contact_type_id', $request->contact_type_id)
                ->update(['is_primary' => false]);
        }

        $contact = new Contact($validated);
        $contact->student_id = $student->id;
        $contact->slug = Str::slug($student->name . '-' . $validated['value'] . '-' . uniqid());
        $contact->save();

        return redirect()
            ->route('students.show', $student)
            ->with('success', 'Contact added successfully');
    }

    public function edit(Student $student, Contact $contact)
    {
        $contactTypes = ContactType::all();
        return view('students.contacts.edit', compact('contact', 'contactTypes'));
    }

    public function update(Request $request, Student $student, Contact $contact)
    {
        $validated = $request->validate([
            'contact_type_id' => 'required|exists:contact_types,id',
            'value' => [
                'required',
                'string',
                'max:255',
                Rule::unique('contacts')->where(function ($query) use ($contact) {
                    return $query->where('student_id', $contact->student_id);
                })->ignore($contact->id)
            ],
            'is_primary' => 'nullable|boolean'
        ], [
            'value.unique' => 'This contact value is already registered for this student.'
        ]);

        // Set is_primary to false if not checked
        $validated['is_primary'] = $request->has('is_primary');

        // If this is marked as primary, remove primary from other contacts of same type
        if ($validated['is_primary']) {
            $contact->student->contacts()
                ->where('contact_type_id', $request->contact_type_id)
                ->where('id', '!=', $contact->id)
                ->update(['is_primary' => false]);
        }

        $contact->update($validated);

        return redirect()
            ->route('students.show', $contact->student)
            ->with('success', 'Contact updated successfully');
    }

    public function destroy(Contact $contact)
    {
        $student = $contact->student;
        $contact->delete();

        return redirect()
            ->route('student.students.show', $student)
            ->with('success', 'Contact deleted successfully');
    }
}
