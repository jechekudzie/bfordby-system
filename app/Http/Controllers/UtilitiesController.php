<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Course;
use App\Models\Subject;
use App\Models\StudentCourse;
use App\Models\StudentPayment;
use App\Models\Attendance;
use App\Models\Semester;
use App\Models\StudyMode;
use App\Models\EnrollmentCode;
use App\Models\Assessment;

class UtilitiesController extends Controller
{
    //
    public function index()
{
    return view('utilities.index', [
        'studentsCount' => Student::count(),
        'coursesCount' => Course::count(),
        'subjectsCount' => Subject::count(),
        'enrollmentsCount' => StudentCourse::count(),
        'paymentsCount' => StudentPayment::count(),
        'attendanceCount' => Attendance::count(),
        'semestersCount' => Semester::count(),
        'studyModesCount' => StudyMode::count(),
        'enrollmentCodesCount' => EnrollmentCode::count(),
        'assessmentsCount' => Assessment::count(),
    ]);
}

}
