<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard'); // Admin dashboard

    // Core Student Routes
    Route::get('students', [\App\Http\Controllers\StudentController::class, 'index'])->name('students.index');
    Route::get('students/create', [\App\Http\Controllers\StudentController::class, 'create'])->name('students.create');
    Route::post('students', [\App\Http\Controllers\StudentController::class, 'store'])->name('students.store');
    Route::get('students/{student}', [\App\Http\Controllers\StudentController::class, 'show'])->name('students.show');
    Route::get('students/{student}/edit', [\App\Http\Controllers\StudentController::class, 'edit'])->name('students.edit');
    Route::put('students/{student}', [\App\Http\Controllers\StudentController::class, 'update'])->name('students.update');
    Route::delete('students/{student}', [\App\Http\Controllers\StudentController::class, 'destroy'])->name('students.destroy');

    // Custom Student-Courses Routes
    Route::get('students/{student}/courses', [\App\Http\Controllers\StudentCourseController::class, 'studentCourses'])->name('students.courses');
    Route::post('students/{student}/courses', [\App\Http\Controllers\StudentCourseController::class, 'store'])->name('students.courses.store');
    Route::delete('students/{student}/courses/{course}', [\App\Http\Controllers\StudentCourseController::class, 'destroy'])->name('students.courses.destroy');

    // Academic Routes
    Route::get('courses', [\App\Http\Controllers\CourseController::class, 'index'])->name('courses.index');
    Route::get('courses/create', [\App\Http\Controllers\CourseController::class, 'create'])->name('courses.create');
    Route::post('courses', [\App\Http\Controllers\CourseController::class, 'store'])->name('courses.store');
    Route::get('courses/{course}', [\App\Http\Controllers\CourseController::class, 'show'])->name('courses.show');
    Route::get('courses/{course}/edit', [\App\Http\Controllers\CourseController::class, 'edit'])->name('courses.edit');
    Route::put('courses/{course}', [\App\Http\Controllers\CourseController::class, 'update'])->name('courses.update');
    Route::delete('courses/{course}', [\App\Http\Controllers\CourseController::class, 'destroy'])->name('courses.destroy');

    Route::get('subjects/{subject}/assessments', [\App\Http\Controllers\AssessmentController::class, 'subjectAssessments'])->name('subjects.assessments');

    // Semesters
    Route::get('semesters', [\App\Http\Controllers\SemesterController::class, 'index'])->name('semesters.index');
    Route::get('semesters/create', [\App\Http\Controllers\SemesterController::class, 'create'])->name('semesters.create');
    Route::post('semesters', [\App\Http\Controllers\SemesterController::class, 'store'])->name('semesters.store');
    Route::get('semesters/{semester}', [\App\Http\Controllers\SemesterController::class, 'show'])->name('semesters.show');
    Route::get('semesters/{semester}/edit', [\App\Http\Controllers\SemesterController::class, 'edit'])->name('semesters.edit');
    Route::put('semesters/{semester}', [\App\Http\Controllers\SemesterController::class, 'update'])->name('semesters.update');
    Route::delete('semesters/{semester}', [\App\Http\Controllers\SemesterController::class, 'destroy'])->name('semesters.destroy');

    // Semester-Residency Routes
    Route::get('students/{student}/semesters/{semester}/residency', [\App\Http\Controllers\SemesterResidencyController::class, 'studentSemesterResidency'])->name('students.semesters.residency');
    Route::post('students/{student}/semesters/{semester}/residency', [\App\Http\Controllers\SemesterResidencyController::class, 'store'])->name('students.semesters.residency.store');
    Route::put('students/{student}/semesters/{semester}/residency', [\App\Http\Controllers\SemesterResidencyController::class, 'update'])->name('students.semesters.residency.update');
    Route::delete('students/{student}/semesters/{semester}/residency', [\App\Http\Controllers\SemesterResidencyController::class, 'destroy'])->name('students.semesters.residency.destroy');

    // Financials - Student Payments
    Route::get('students/{student}/payments', [\App\Http\Controllers\StudentPaymentController::class, 'index'])->name('students.payments.index');
    Route::post('students/{student}/payments', [\App\Http\Controllers\StudentPaymentController::class, 'store'])->name('students.payments.store');
    Route::put('students/{student}/payments/{payment}', [\App\Http\Controllers\StudentPaymentController::class, 'update'])->name('students.payments.update');
    Route::delete('students/{student}/payments/{payment}', [\App\Http\Controllers\StudentPaymentController::class, 'destroy'])->name('students.payments.destroy');
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__.'/auth.php';
