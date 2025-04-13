<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Gender;
use App\Models\Course;
use App\Models\Title;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        \Log::info('Updating student', [
            'student_id' => $student->id,
            'personal_statement_provided' => $request->has('personal_statement'),
            'personal_statement_length' => $request->input('personal_statement') ? strlen($request->input('personal_statement')) : 0,
            'request_data' => $request->all()
        ]);

        $validated = $request->validate([
            'title_id' => 'nullable|exists:titles,id',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender_id' => 'required|exists:genders,id',
            'enrollment_date' => 'required|date',
            'status' => 'required|in:pending,active,inactive,graduated,withdrawn',
            'personal_statement' => 'nullable|string'
        ]);

        \Log::info('Validated data', [
            'personal_statement_in_validated' => isset($validated['personal_statement']),
            'personal_statement_length' => isset($validated['personal_statement']) ? strlen($validated['personal_statement']) : 0
        ]);

        DB::beginTransaction();
        try {
            $student->update($validated);
            
            \Log::info('Student updated', [
                'student_id' => $student->id,
                'personal_statement_after_update' => $student->personal_statement ? strlen($student->personal_statement) : 0
            ]);

            DB::commit();
            return redirect()->route('students.show', $student->slug)
                           ->with('success', 'Student updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating student', [
                'student_id' => $student->id,
                'error' => $e->getMessage()
            ]);
            return back()->with('error', 'Error updating student: ' . $e->getMessage());
        }
    }

    /**
     * Update the personal statement of the specified student
     */
    public function updatePersonalStatement(Request $request, Student $student)
    {
        Log::info('Updating personal statement', [
            'student_id' => $student->id,
            'request_data' => $request->all(),
            'current_personal_statement' => $student->personal_statement
        ]);
       
        try {
            $validated = $request->validate([
                'personal_statement' => 'nullable|string|max:10000'
            ]);

            Log::info('Validated personal statement data', [
                'personal_statement_provided' => isset($validated['personal_statement']),
                'personal_statement_length' => isset($validated['personal_statement']) ? strlen($validated['personal_statement']) : 0
            ]);

            $student->personal_statement = $validated['personal_statement'];
            $result = $student->save();

            Log::info('Personal statement update result', [
                'save_result' => $result,
                'updated_personal_statement' => $student->personal_statement,
                'personal_statement_length' => $student->personal_statement ? strlen($student->personal_statement) : 0
            ]);

            return redirect()
                ->route('students.show', $student)
                ->with('success', 'Personal statement updated successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to update personal statement', [
                'student_id' => $student->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()
                ->route('students.show', $student)
                ->with('error', 'Failed to update personal statement. Please try again.');
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
