<?php

namespace App\Http\Controllers\Organisation;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Course;
use App\Models\Assessment;
use App\Models\Enrollment;
use App\Models\Module;
use App\Models\Semester;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrganisationDashboardController extends Controller
{

    public function dashboard()
    {
        $user = Auth::user();
        $organisations = $user->organisations;

        return view('organisation.dashboard.dashboard', compact('organisations', 'user' ));
    }

    
    public function index()
    {
        $user = Auth::user();

        // Get current year and term
        $currentYear = date('Y');
        $currentMonth = date('n');
        $currentTerm = ceil($currentMonth / 4); // Divides year into 3 terms

        // Get students data
        $students = Student::query()
            ->withCount(['enrollments as active_enrollments' => function($query) {
                $query->where('status', 'active');
            }])
            ->get();

        // Get courses data
        $courses = Course::query()
            ->withCount(['enrollments as active_students' => function($query) {
                $query->where('status', 'active');
            }])
            ->withCount(['modules as total_modules'])
            ->get();

        // Calculate student statistics
        $studentStats = [
            'total' => $students->count(),
            'active' => $students->where('status', 'active')->count(),
            'new_this_term' => $students->where('created_at', '>=', Carbon::now()->startOfMonth()->subMonths(4))->count(),
            'graduating_soon' => $students->where('expected_completion_date', '<=', Carbon::now()->addMonths(3))->count()
        ];

        // Calculate course statistics
        $courseStats = [
            'total_courses' => $courses->count(),
            'total_modules' => $courses->sum('total_modules'),
            'avg_students_per_course' => $courses->avg('active_students') ?? 0,
            'most_popular_course' => $courses->sortByDesc('active_students')->first()
        ];

        // Get recent activities
        $recentActivities = collect();

        // Recent enrollments
        $recentEnrollments = Enrollment::query()
            ->with(['student', 'course'])
            ->latest()
            ->take(5)
            ->get();

        foreach ($recentEnrollments as $enrollment) {
            $recentActivities->push([
                'type' => 'Enrollment',
                'title' => 'New Student Enrollment',
                'date' => $enrollment->created_at,
                'details' => $enrollment->student->name . ' enrolled in ' . $enrollment->course->name
            ]);
        }

        // Recent assessments
        $recentAssessments = Assessment::query()
            ->with(['allocations.submissions.student'])
            ->latest()
            ->take(5)
            ->get();

        foreach ($recentAssessments as $assessment) {
            $recentActivities->push([
                'type' => 'Assessment',
                'title' => 'Assessment Submitted',
                'date' => $assessment->submitted_at,
                'details' => $assessment->allocations->flatMap->submissions->first()->student->name . ' - ' . $assessment->module->name
            ]);
        }

        // Sort activities by date
        $recentActivities = $recentActivities->sortByDesc('date')->take(10);

        // Calculate monthly statistics
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $monthlyStats = [
            'enrollments' => array_fill_keys($months, 0),
            'assessments' => array_fill_keys($months, 0),
            'completions' => array_fill_keys($months, 0)
        ];

        // Populate monthly statistics
        foreach ($recentEnrollments as $enrollment) {
            $month = date('M', strtotime($enrollment->created_at));
            $monthlyStats['enrollments'][$month]++;
        }

        foreach ($recentAssessments as $assessment) {
            $month = date('M', strtotime($assessment->submitted_at));
            $monthlyStats['assessments'][$month]++;
        }

        // Get assessment statistics
        $assessmentStats = Assessment::whereHas('allocations.submissions', function($query) {
            $query->whereNotNull('grade');
        })
        ->with(['allocations.submissions.student', 'module'])
        ->get()
        ->map(function($assessment) {
            $submissions = $assessment->allocations->flatMap->submissions;
            return [
                'name' => $assessment->name,
                'type' => $assessment->type,
                'module' => $assessment->module->name,
                'total_students' => $submissions->pluck('student_id')->unique()->count(),
                'average_grade' => $submissions->avg('grade') ?? 0,
                'passing_rate' => $this->calculatePassingRate($assessment)
            ];
        });

        return view('organisation.dashboard.index', compact(
            'user',
            'currentYear',
            'currentTerm',
            'studentStats',
            'courseStats',
            'courses',
            'recentActivities',
            'monthlyStats',
            'months',
            'assessmentStats'
        ));
    }

    /**
     * Calculate the passing rate for an assessment
     */
    private function calculatePassingRate($assessment)
    {
        $submissions = $assessment->allocations->flatMap->submissions;
        if ($submissions->isEmpty()) return 0;

        $passingCount = $submissions->filter(function($submission) {
            return $submission->grade >= 50; // Assuming 50 is the passing grade
        })->count();

        return ($passingCount / $submissions->count()) * 100;
    }
}