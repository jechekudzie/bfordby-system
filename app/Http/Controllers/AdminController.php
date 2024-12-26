<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Course;
use App\Models\Assessment;
use App\Models\Scholarship;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // Get counts for dashboard widgets
        $stats = [
            'students' => Student::count(),
            'courses' => Course::count(),
            'assessments' => Assessment::count(),
            'scholarships' => Scholarship::count(),
        ];

        // Get recent students
        $recentStudents = Student::latest()
            ->take(5)
            ->get();

        // Get upcoming assessments
        $upcomingAssessments = Assessment::whereNull('score')
            ->with(['student', 'subject'])
            ->latest()
            ->take(5)
            ->get();

        return view('index', compact('stats', 'recentStudents', 'upcomingAssessments'));
    }
}
