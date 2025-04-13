<?php

namespace App\Http\Controllers;

use App\Models\NextOfKin;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NextOfKinController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Student $student)
    {
        return view('students.next-of-kin.index', compact('student'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Student $student)
    {
        $countries = \App\Models\Country::orderBy('name')->get();
        return view('students.next-of-kin.create', compact('student', 'countries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Student $student)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'relationship' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'address_line1' => 'nullable|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:255',
            'country_id' => 'nullable|exists:countries,id',
            'is_emergency_contact' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $nextOfKin = $student->nextOfKins()->create($request->all());

        return redirect()->route('students.show', $student)
            ->with('success', 'Next of kin added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student, NextOfKin $nextOfKin)
    {
        return view('students.next-of-kin.show', compact('student', 'nextOfKin'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student, NextOfKin $nextOfKin)
    {
        $countries = \App\Models\Country::orderBy('name')->get();
        return view('students.next-of-kin.edit', compact('student', 'nextOfKin', 'countries'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student, NextOfKin $nextOfKin)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'relationship' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'address_line1' => 'nullable|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:255',
            'country_id' => 'nullable|exists:countries,id',
            'is_emergency_contact' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $nextOfKin->update($request->all());

        return redirect()->route('students.show', $student)
            ->with('success', 'Next of kin updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student, NextOfKin $nextOfKin)
    {
        $nextOfKin->delete();

        return redirect()->route('students.show', $student)
            ->with('success', 'Next of kin removed successfully.');
    }
} 