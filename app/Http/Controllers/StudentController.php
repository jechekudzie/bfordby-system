<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\Student;
use App\Models\Gender;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    /**
     * Display a listing of students
     */
    public function index()
    {
        $students = Student::with(['gender', 'contacts', 'addresses'])->paginate(10);
        return view('admin.students.index', compact('students'));
    }

    /**
     * Show form for creating a new student
     */
    public function create()
    {
        $genders = Gender::all();
        $courses = Course::all();
        return view('admin.students.create', compact('genders', 'courses'));
    }

    /**
     * Store a newly created student
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender_id' => 'required|exists:genders,id',
            'email' => 'required|email|unique:students,email',
            'phone' => 'nullable|string|max:25',
            'enrollment_date' => 'required|date',
            'status' => 'required|in:active,inactive,graduated,withdrawn',
            'course_id' => 'required|exists:courses,id',
        ]);

        DB::beginTransaction();
        try {
            $student = Student::create($validated);

            DB::commit();
            return redirect()->route('admin.students.show', $student)
                             ->with('success', 'Student created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error creating student: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified student
     */
    public function show(Student $student)
    {
        $student->load([
            'gender', 
            'contacts.contactType', 
            'addresses.addressType',
            'guardians',
            'academicHistories',
            'studentCourses.course',
            'scholarships'
        ]);
        
        return view('admin.students.show', compact('student'));
    }

    /**
     * Show form for editing the specified student
     */
    public function edit(Student $student)
    {
        $genders = Gender::all();
        $student->load(['contacts', 'addresses', 'guardians']);
        return view('admin.students.edit', compact('student', 'genders'));
    }

    /**
     * Update the specified student
     */
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender_id' => 'required|exists:genders,id',
            'enrollment_date' => 'required|date',
            'status' => 'required|in:active,inactive,graduated,withdrawn'
        ]);

        DB::beginTransaction();
        try {
            $student->update($validated);

            // Update contacts
            if ($request->has('contacts')) {
                $student->contacts()->delete(); // Remove old contacts
                foreach ($request->contacts as $contact) {
                    $student->contacts()->create($contact);
                }
            }

            // Update addresses
            if ($request->has('addresses')) {
                $student->addresses()->delete(); // Remove old addresses
                foreach ($request->addresses as $address) {
                    $student->addresses()->create($address);
                }
            }

            DB::commit();
            return redirect()->route('students.show', $student)
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

    public function getSemesters(Student $student)
    {
        $semesters = $student->availableSemesters();
        $periodName = $student->getCurrentPeriodName();
        
        return response()->json([
            'semesters' => $semesters,
            'period_type' => $periodName,
            'study_mode' => $student->course->study_mode
        ]);
    }
}
