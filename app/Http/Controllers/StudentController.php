<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Gender;
use App\Models\Course;
use App\Models\Title;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    /**
     * Display a listing of students
     */
    public function index()
    {
        $students = Student::with(['gender', 'contacts', 'addresses', 'title'])->get();
        return view('students.index', compact('students'));
    }

    /**
     * Show form for creating a new student
     */
    public function create()
    {
        $genders = Gender::all();
        $titles = Title::all();
        $courses = Course::all();
        return view('students.create', compact('genders', 'titles', 'courses'));
    }

    /**
     * Store a newly created student
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_id' => 'nullable|exists:titles,id',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender_id' => 'required|exists:genders,id',
            'enrollment_date' => 'required|date',
            'status' => 'required|in:pending,active,inactive,graduated,withdrawn',
        
        ]);

        DB::beginTransaction();
        try {
            // Slug is automatically created via Spatie's HasSlug
            $student = Student::create($validated);

            DB::commit();
            return redirect()->route('students.show', $student->slug)
                             ->with('success', 'Student enrolled successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error enrolling student: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified student
     */
    public function show(Student $student)
    {
        $student->load([
            'title',
            'gender',
            'contacts.contactType',
            'addresses.addressType',
            'guardians',
            'enrollments.course',
            'enrollments.studyMode',
            'enrollments.enrollmentCode',
            'academicHistories',
            'scholarships'
        ]);
    
        return view('students.show', compact('student'));
    }
    

    /**
     * Show form for editing the specified student
     */
    public function edit(Student $student)
    {
        $genders = Gender::all();
        $titles = Title::all();
        $courses = Course::all();
        $student->load(['contacts', 'addresses', 'guardians']);
        return view('students.edit', compact('student', 'genders', 'titles', 'courses'));
    }

    /**
     * Update the specified student
     */
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'title_id' => 'nullable|exists:titles,id',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender_id' => 'required|exists:genders,id',
            'enrollment_date' => 'required|date',
            'status' => 'required|in:pending,active,inactive,graduated,withdrawn'
        ]);

        DB::beginTransaction();
        try {
            $student->update($validated);

            DB::commit();
            return redirect()->route('students.show', $student->slug)
                           ->with('success', 'Student updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error updating student: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified student
     */
    public function destroy(Student $student)
    {
        try {
            $student->delete();
            return redirect()->route('students.index')
                           ->with('success', 'Student deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting student: ' . $e->getMessage());
        }
    }
}
