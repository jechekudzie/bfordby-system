<?php

namespace App\Http\Controllers;

use App\Models\Scholarship;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScholarshipController extends Controller
{
    public function index()
    {
        $scholarships = Scholarship::with('student')->paginate(10);
        return view('scholarships.index', compact('scholarships'));
    }

    public function create()
    {
        $students = Student::all();
        return view('scholarships.create', compact('students'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'scholarship_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'conditions' => 'nullable|string'
        ]);

        DB::transaction(function () use ($validated) {
            Scholarship::create($validated);
        });

        return redirect()->route('scholarships.index')
                        ->with('success', 'Scholarship created successfully.');
    }

    public function show(Scholarship $scholarship)
    {
        $scholarship->load('student');
        return view('scholarships.show', compact('scholarship'));
    }

    public function edit(Scholarship $scholarship)
    {
        $students = Student::all();
        return view('scholarships.edit', compact('scholarship', 'students'));
    }

    public function update(Request $request, Scholarship $scholarship)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'scholarship_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'conditions' => 'nullable|string'
        ]);

        DB::transaction(function () use ($scholarship, $validated) {
            $scholarship->update($validated);
        });

        return redirect()->route('scholarships.index')
                        ->with('success', 'Scholarship updated successfully.');
    }

    public function destroy(Scholarship $scholarship)
    {
        $scholarship->delete();
        return redirect()->route('scholarships.index')
                        ->with('success', 'Scholarship deleted successfully.');
    }

    // Additional method for scholarship reports
    public function reports()
    {
        $scholarshipStats = DB::table('scholarships')
            ->select(
                DB::raw('COUNT(*) as total_scholarships'),
                DB::raw('SUM(amount) as total_amount'),
                DB::raw('AVG(amount) as average_amount')
            )->first();

        $activeScholarships = Scholarship::whereDate('end_date', '>', now())
                                       ->orWhereNull('end_date')
                                       ->count();

        return view('scholarships.reports', compact('scholarshipStats', 'activeScholarships'));
    }
}
