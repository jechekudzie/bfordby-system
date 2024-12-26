<?php

namespace App\Http\Controllers;

use App\Models\SemesterResidency;
use App\Models\Student;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SemesterResidencyController extends Controller
{
    public function index()
    {
        $residencies = SemesterResidency::with(['student', 'semester'])
                                      ->paginate(10);
        return view('residencies.index', compact('residencies'));
    }

    public function create()
    {
        $students = Student::all();
        $semesters = Semester::all();
        return view('residencies.create', compact('students', 'semesters'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'semester_id' => 'required|exists:semesters,id',
            'room_number' => 'required|string|max:50',
            'is_paid' => 'required|boolean',
            'damages' => 'nullable|string'
        ]);

        // Check for existing residency
        $exists = SemesterResidency::where('room_number', $validated['room_number'])
            ->where('semester_id', $validated['semester_id'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['room_number' => 'This room is already occupied for this semester.']);
        }

        DB::transaction(function () use ($validated) {
            SemesterResidency::create($validated);
        });

        return redirect()->route('residencies.index')
                        ->with('success', 'Residency created successfully.');
    }

    public function show(SemesterResidency $residency)
    {
        $residency->load(['student', 'semester']);
        return view('residencies.show', compact('residency'));
    }

    public function edit(SemesterResidency $residency)
    {
        $students = Student::all();
        $semesters = Semester::all();
        return view('residencies.edit', compact('residency', 'students', 'semesters'));
    }

    public function update(Request $request, SemesterResidency $residency)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'semester_id' => 'required|exists:semesters,id',
            'room_number' => 'required|string|max:50',
            'is_paid' => 'required|boolean',
            'damages' => 'nullable|string'
        ]);

        // Check for existing residency (excluding current record)
        $exists = SemesterResidency::where('room_number', $validated['room_number'])
            ->where('semester_id', $validated['semester_id'])
            ->where('id', '!=', $residency->id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['room_number' => 'This room is already occupied for this semester.']);
        }

        DB::transaction(function () use ($residency, $validated) {
            $residency->update($validated);
        });

        return redirect()->route('residencies.index')
                        ->with('success', 'Residency updated successfully.');
    }

    public function destroy(SemesterResidency $residency)
    {
        $residency->delete();
        return redirect()->route('residencies.index')
                        ->with('success', 'Residency deleted successfully.');
    }

    // Additional methods for residency management
    public function roomOccupancy()
    {
        $occupancyStats = DB::table('semester_residencies')
            ->join('semesters', 'semester_residencies.semester_id', '=', 'semesters.id')
            ->where('semesters.end_date', '>=', now())
            ->select(
                'room_number',
                DB::raw('COUNT(*) as total_occupants'),
                DB::raw('SUM(CASE WHEN is_paid = 1 THEN 1 ELSE 0 END) as paid_occupants')
            )
            ->groupBy('room_number')
            ->get();

        return view('residencies.occupancy', compact('occupancyStats'));
    }

    public function damageReports()
    {
        $damages = SemesterResidency::whereNotNull('damages')
                                   ->with(['student', 'semester'])
                                   ->paginate(10);

        return view('residencies.damages', compact('damages'));
    }
}
