<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubjectController;

Route::get('/', function () {
    return view('index');
});

Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('utilities', [\App\Http\Controllers\UtilitiesController::class, 'index'])->name('utilities.index');
    // Dashboard
    Route::get('/', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard'); // Admin dashboard

   
    // Academic Routes
    Route::get('courses', [\App\Http\Controllers\CourseController::class, 'index'])->name('courses.index');
    Route::get('courses/create', [\App\Http\Controllers\CourseController::class, 'create'])->name('courses.create');
    Route::post('courses', [\App\Http\Controllers\CourseController::class, 'store'])->name('courses.store');
    Route::get('courses/{course}', [\App\Http\Controllers\CourseController::class, 'show'])->name('courses.show');
    Route::get('courses/{course}/edit', [\App\Http\Controllers\CourseController::class, 'edit'])->name('courses.edit');
    Route::put('courses/{course}', [\App\Http\Controllers\CourseController::class, 'update'])->name('courses.update');
    Route::delete('courses/{course}', [\App\Http\Controllers\CourseController::class, 'destroy'])->name('courses.destroy');

    // Subjects Routes
    Route::get('subjects', [\App\Http\Controllers\SubjectController::class, 'index'])->name('subjects.index');
    Route::get('subjects/create', [\App\Http\Controllers\SubjectController::class, 'create'])->name('subjects.create');
    Route::post('subjects', [\App\Http\Controllers\SubjectController::class, 'store'])->name('subjects.store');
    Route::get('subjects/{subject}', [\App\Http\Controllers\SubjectController::class, 'show'])->name('subjects.show');
    Route::get('subjects/{subject}/edit', [\App\Http\Controllers\SubjectController::class, 'edit'])->name('subjects.edit');
    Route::put('subjects/{subject}', [\App\Http\Controllers\SubjectController::class, 'update'])->name('subjects.update');
    Route::delete('subjects/{subject}', [\App\Http\Controllers\SubjectController::class, 'destroy'])->name('subjects.destroy');


    Route::get('subjects/{subject}/assessments', [\App\Http\Controllers\AssessmentController::class, 'subjectAssessments'])->name('subjects.assessments');


    

    // Semesters
    Route::get('semesters', [\App\Http\Controllers\SemesterController::class, 'index'])->name('semesters.index');
    Route::get('semesters/create', [\App\Http\Controllers\SemesterController::class, 'create'])->name('semesters.create');
    Route::post('semesters', [\App\Http\Controllers\SemesterController::class, 'store'])->name('semesters.store');
    Route::get('semesters/{semester}', [\App\Http\Controllers\SemesterController::class, 'show'])->name('semesters.show');
    Route::get('semesters/{semester}/edit', [\App\Http\Controllers\SemesterController::class, 'edit'])->name('semesters.edit');
    Route::put('semesters/{semester}', [\App\Http\Controllers\SemesterController::class, 'update'])->name('semesters.update');
    Route::delete('semesters/{semester}', [\App\Http\Controllers\SemesterController::class, 'destroy'])->name('semesters.destroy');


    // Contact Types
    Route::get('contact-types', [\App\Http\Controllers\ContactTypeController::class, 'index'])->name('contact-types.index');
    Route::get('contact-types/create', [\App\Http\Controllers\ContactTypeController::class, 'create'])->name('contact-types.create');
    Route::post('contact-types', [\App\Http\Controllers\ContactTypeController::class, 'store'])->name('contact-types.store');
    Route::get('contact-types/{contactType}', [\App\Http\Controllers\ContactTypeController::class, 'show'])->name('contact-types.show');
    Route::get('contact-types/{contactType}/edit', [\App\Http\Controllers\ContactTypeController::class, 'edit'])->name('contact-types.edit');
    Route::put('contact-types/{contactType}', [\App\Http\Controllers\ContactTypeController::class, 'update'])->name('contact-types.update');
    Route::delete('contact-types/{contactType}', [\App\Http\Controllers\ContactTypeController::class, 'destroy'])->name('contact-types.destroy');

    // Address Types
    Route::resource('address-types', \App\Http\Controllers\AddressTypeController::class)->except(['show']);

    // Genders
    Route::resource('genders', \App\Http\Controllers\GenderController::class)->except(['show']);

    // Titles
    Route::resource('titles', \App\Http\Controllers\TitleController::class)->except(['show']);

    // Countries
    Route::get('countries', [\App\Http\Controllers\CountryController::class, 'index'])->name('countries.index');
    Route::get('countries/create', [\App\Http\Controllers\CountryController::class, 'create'])->name('countries.create');
    Route::post('countries', [\App\Http\Controllers\CountryController::class, 'store'])->name('countries.store');
    Route::get('countries/{country}/edit', [\App\Http\Controllers\CountryController::class, 'edit'])->name('countries.edit');
    Route::put('countries/{country}', [\App\Http\Controllers\CountryController::class, 'update'])->name('countries.update');
    Route::delete('countries/{country}', [\App\Http\Controllers\CountryController::class, 'destroy'])->name('countries.destroy');

    // Qualification Levels
    Route::resource('qualification-levels', \App\Http\Controllers\QualificationLevelController::class)->except(['show']);


   
});

Route::prefix('student')->group(function () {

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
     Route::get('students/{student}/courses/create', [\App\Http\Controllers\StudentCourseController::class, 'create'])->name('students.courses.create');
     Route::post('students/{student}/courses', [\App\Http\Controllers\StudentCourseController::class, 'store'])->name('students.courses.store');
     Route::get('students/{student}/courses/{course}', [\App\Http\Controllers\StudentCourseController::class, 'show'])->name('students.courses.show');
     Route::get('students/{student}/courses/{course}/edit', [\App\Http\Controllers\StudentCourseController::class, 'edit'])->name('students.courses.edit');
     Route::put('students/{student}/courses/{course}', [\App\Http\Controllers\StudentCourseController::class, 'update'])->name('students.courses.update');
     Route::delete('students/{student}/courses/{course}', [\App\Http\Controllers\StudentCourseController::class, 'destroy'])->name('students.courses.destroy');
 

    // Semester-Residency Routes
    Route::get('students/{student}/semesters/{semester}/residency', [\App\Http\Controllers\SemesterResidencyController::class, 'studentSemesterResidency'])->name('students.semesters.residency');
    Route::post('students/{student}/semesters/{semester}/residency', [\App\Http\Controllers\SemesterResidencyController::class, 'store'])->name('students.semesters.residency.store');
    Route::put('students/{student}/semesters/{semester}/residency', [\App\Http\Controllers\SemesterResidencyController::class, 'update'])->name('students.semesters.residency.update');
    Route::delete('students/{student}/semesters/{semester}/residency', [\App\Http\Controllers\SemesterResidencyController::class, 'destroy'])->name('students.semesters.residency.destroy');


    // Guardian Routes
    Route::get('students/{student}/guardians', [\App\Http\Controllers\GuardianController::class, 'index'])->name('students.guardians.index');
    Route::get('students/{student}/guardians/create', [\App\Http\Controllers\GuardianController::class, 'create'])->name('students.guardians.create');
    Route::post('students/{student}/guardians', [\App\Http\Controllers\GuardianController::class, 'store'])->name('students.guardians.store');
    Route::get('students/{student}/guardians/{guardian}', [\App\Http\Controllers\GuardianController::class, 'show'])->name('students.guardians.show');
    Route::get('students/{student}/guardians/{guardian}/edit', [\App\Http\Controllers\GuardianController::class, 'edit'])->name('students.guardians.edit');
    Route::put('students/{student}/guardians/{guardian}', [\App\Http\Controllers\GuardianController::class, 'update'])->name('students.guardians.update');
    Route::delete('students/{student}/guardians/{guardian}', [\App\Http\Controllers\GuardianController::class, 'destroy'])->name('students.guardians.destroy');

    // Guardian Contacts
    Route::get('students/{student}/guardians/{guardian}/contacts', [\App\Http\Controllers\GuardianContactController::class, 'index'])->name('students.guardians.contacts.index');
    Route::get('students/{student}/guardians/{guardian}/contacts/create', [\App\Http\Controllers\GuardianContactController::class, 'create'])->name('students.guardians.contacts.create');
    Route::post('students/{student}/guardians/{guardian}/contacts', [\App\Http\Controllers\GuardianContactController::class, 'store'])->name('students.guardians.contacts.store');
    Route::get('students/{student}/guardians/{guardian}/contacts/{contact}', [\App\Http\Controllers\GuardianContactController::class, 'show'])->name('students.guardians.contacts.show');
    Route::get('students/{student}/guardians/{guardian}/contacts/{contact}/edit', [\App\Http\Controllers\GuardianContactController::class, 'edit'])->name('students.guardians.contacts.edit');
    Route::put('students/{student}/guardians/{guardian}/contacts/{contact}', [\App\Http\Controllers\GuardianContactController::class, 'update'])->name('students.guardians.contacts.update');
    Route::delete('students/{student}/guardians/{guardian}/contacts/{contact}', [\App\Http\Controllers\GuardianContactController::class, 'destroy'])->name('students.guardians.contacts.destroy');

    // Guardian Addresses
    Route::get('students/{student}/guardians/{guardian}/addresses', [\App\Http\Controllers\GuardianAddressController::class, 'index'])->name('students.guardians.addresses.index');
    Route::get('students/{student}/guardians/{guardian}/addresses/create', [\App\Http\Controllers\GuardianAddressController::class, 'create'])->name('students.guardians.addresses.create');
    Route::post('students/{student}/guardians/{guardian}/addresses', [\App\Http\Controllers\GuardianAddressController::class, 'store'])->name('students.guardians.addresses.store');
    Route::get('students/{student}/guardians/{guardian}/addresses/{address}', [\App\Http\Controllers\GuardianAddressController::class, 'show'])->name('students.guardians.addresses.show');
    Route::get('students/{student}/guardians/{guardian}/addresses/{address}/edit', [\App\Http\Controllers\GuardianAddressController::class, 'edit'])->name('students.guardians.addresses.edit');
    Route::put('students/{student}/guardians/{guardian}/addresses/{address}', [\App\Http\Controllers\GuardianAddressController::class, 'update'])->name('students.guardians.addresses.update');
    Route::delete('students/{student}/guardians/{guardian}/addresses/{address}', [\App\Http\Controllers\GuardianAddressController::class, 'destroy'])->name('students.guardians.addresses.destroy');


    //student contacts
    Route::get('students/{student}/contacts', [\App\Http\Controllers\StudentContactController::class, 'index'])->name('students.contacts.index');
    Route::get('students/{student}/contacts/create', [\App\Http\Controllers\StudentContactController::class, 'create'])->name('students.contacts.create');
    Route::post('students/{student}/contacts', [\App\Http\Controllers\StudentContactController::class, 'store'])->name('students.contacts.store');
    Route::get('students/{student}/contacts/{contact}', [\App\Http\Controllers\StudentContactController::class, 'show'])->name('students.contacts.show');
    Route::get('students/{student}/contacts/{contact}/edit', [\App\Http\Controllers\StudentContactController::class, 'edit'])->name('students.contacts.edit');
    Route::put('students/{student}/contacts/{contact}', [\App\Http\Controllers\StudentContactController::class, 'update'])->name('students.contacts.update');
    Route::delete('students/{student}/contacts/{contact}', [\App\Http\Controllers\StudentContactController::class, 'destroy'])->name('students.contacts.destroy');


    //student addresses
    Route::get('students/{student}/addresses', [\App\Http\Controllers\StudentAddressController::class, 'index'])->name('students.addresses.index');
    Route::get('students/{student}/addresses/create', [\App\Http\Controllers\StudentAddressController::class, 'create'])->name('students.addresses.create');
    Route::post('students/{student}/addresses', [\App\Http\Controllers\StudentAddressController::class, 'store'])->name('students.addresses.store');
    Route::get('students/{student}/addresses/{address}', [\App\Http\Controllers\StudentAddressController::class, 'show'])->name('students.addresses.show');
    Route::get('students/{student}/addresses/{address}/edit', [\App\Http\Controllers\StudentAddressController::class, 'edit'])->name('students.addresses.edit');
    Route::put('students/{student}/addresses/{address}', [\App\Http\Controllers\StudentAddressController::class, 'update'])->name('students.addresses.update');
    Route::delete('students/{student}/addresses/{address}', [\App\Http\Controllers\StudentAddressController::class, 'destroy'])->name('students.addresses.destroy');


    //student identification
    Route::get('students/{student}/identifications', [\App\Http\Controllers\StudentIdentificationController::class, 'index'])->name('students.identifications.index');
    Route::get('students/{student}/identifications/create', [\App\Http\Controllers\StudentIdentificationController::class, 'create'])->name('students.identifications.create');
    Route::post('students/{student}/identifications', [\App\Http\Controllers\StudentIdentificationController::class, 'store'])->name('students.identifications.store');
    Route::get('students/{student}/identifications/{identification}', [\App\Http\Controllers\StudentIdentificationController::class, 'show'])->name('students.identifications.show');
    Route::get('students/{student}/identifications/{identification}/edit', [\App\Http\Controllers\StudentIdentificationController::class, 'edit'])->name('students.identifications.edit');
    Route::put('students/{student}/identifications/{identification}', [\App\Http\Controllers\StudentIdentificationController::class, 'update'])->name('students.identifications.update');
    Route::delete('students/{student}/identifications/{identification}', [\App\Http\Controllers\StudentIdentificationController::class, 'destroy'])->name('students.identifications.destroy');

    //student academic histories
    Route::get('students/{student}/academic-histories', [\App\Http\Controllers\StudentAcademicHistoryController::class, 'index'])->name('students.academic-histories.index');
    Route::get('students/{student}/academic-histories/create', [\App\Http\Controllers\StudentAcademicHistoryController::class, 'create'])->name('students.academic-histories.create');
    Route::post('students/{student}/academic-histories', [\App\Http\Controllers\StudentAcademicHistoryController::class, 'store'])->name('students.academic-histories.store');
    Route::get('students/{student}/academic-histories/{academicHistory}', [\App\Http\Controllers\StudentAcademicHistoryController::class, 'show'])->name('students.academic-histories.show');
    Route::get('students/{student}/academic-histories/{academicHistory}/edit', [\App\Http\Controllers\StudentAcademicHistoryController::class, 'edit'])->name('students.academic-histories.edit');
    Route::put('students/{student}/academic-histories/{academicHistory}', [\App\Http\Controllers\StudentAcademicHistoryController::class, 'update'])->name('students.academic-histories.update');
    Route::delete('students/{student}/academic-histories/{academicHistory}', [\App\Http\Controllers\StudentAcademicHistoryController::class, 'destroy'])->name('students.academic-histories.destroy');


    //student languages
    Route::get('students/{student}/languages', [\App\Http\Controllers\StudentLanguageController::class, 'index'])->name('students.languages.index');
    Route::get('students/{student}/languages/create', [\App\Http\Controllers\StudentLanguageController::class, 'create'])->name('students.languages.create');
    Route::post('students/{student}/languages', [\App\Http\Controllers\StudentLanguageController::class, 'store'])->name('students.languages.store');
    Route::get('students/{student}/languages/{language}', [\App\Http\Controllers\StudentLanguageController::class, 'show'])->name('students.languages.show');
    Route::get('students/{student}/languages/{language}/edit', [\App\Http\Controllers\StudentLanguageController::class, 'edit'])->name('students.languages.edit');
    Route::put('students/{student}/languages/{language}', [\App\Http\Controllers\StudentLanguageController::class, 'update'])->name('students.languages.update');
    Route::delete('students/{student}/languages/{language}', [\App\Http\Controllers\StudentLanguageController::class, 'destroy'])->name('students.languages.destroy');


    //student disciplinary
    Route::get('students/{student}/disciplinary', [\App\Http\Controllers\StudentDisciplinaryController::class, 'index'])->name('students.disciplinary.index');
    Route::get('students/{student}/disciplinary/create', [\App\Http\Controllers\StudentDisciplinaryController::class, 'create'])->name('students.disciplinary.create');
    Route::post('students/{student}/disciplinary', [\App\Http\Controllers\StudentDisciplinaryController::class, 'store'])->name('students.disciplinary.store');
    Route::get('students/{student}/disciplinary/{disciplinary}', [\App\Http\Controllers\StudentDisciplinaryController::class, 'show'])->name('students.disciplinary.show');
    Route::get('students/{student}/disciplinary/{disciplinary}/edit', [\App\Http\Controllers\StudentDisciplinaryController::class, 'edit'])->name('students.disciplinary.edit');
    Route::put('students/{student}/disciplinary/{disciplinary}', [\App\Http\Controllers\StudentDisciplinaryController::class, 'update'])->name('students.disciplinary.update');
    Route::delete('students/{student}/disciplinary/{disciplinary}', [\App\Http\Controllers\StudentDisciplinaryController::class, 'destroy'])->name('students.disciplinary.destroy');


    //student health
    Route::get('students/{student}/health', [\App\Http\Controllers\StudentHealthController::class, 'index'])->name('students.health.index');
    Route::get('students/{student}/health/create', [\App\Http\Controllers\StudentHealthController::class, 'create'])->name('students.health.create');
    Route::post('students/{student}/health', [\App\Http\Controllers\StudentHealthController::class, 'store'])->name('students.health.store');
    Route::get('students/{student}/health/{health}', [\App\Http\Controllers\StudentHealthController::class, 'show'])->name('students.health.show');
    Route::get('students/{student}/health/{health}/edit', [\App\Http\Controllers\StudentHealthController::class, 'edit'])->name('students.health.edit');
    Route::put('students/{student}/health/{health}', [\App\Http\Controllers\StudentHealthController::class, 'update'])->name('students.health.update');
    Route::delete('students/{student}/health/{health}', [\App\Http\Controllers\StudentHealthController::class, 'destroy'])->name('students.health.destroy');


    // Financials - Student Payments
    Route::get('students/{student}/payments', [\App\Http\Controllers\StudentPaymentController::class, 'index'])->name('students.payments.index');
    Route::get('students/{student}/payments/create', [\App\Http\Controllers\StudentPaymentController::class, 'create'])->name('students.payments.create');
    Route::post('students/{student}/payments', [\App\Http\Controllers\StudentPaymentController::class, 'store'])->name('students.payments.store');
    Route::put('students/{student}/payments/{payment}', [\App\Http\Controllers\StudentPaymentController::class, 'update'])->name('students.payments.update');
    Route::delete('students/{student}/payments/{payment}', [\App\Http\Controllers\StudentPaymentController::class, 'destroy'])->name('students.payments.destroy');


    //atttendance
    Route::get('attendance', [\App\Http\Controllers\AttendanceController::class, 'index'])->name('students.attendance.index');
    Route::get('attendance/create', [\App\Http\Controllers\AttendanceController::class, 'create'])->name('students.attendance.create');
    Route::post('attendance', [\App\Http\Controllers\AttendanceController::class, 'store'])->name('students.attendance.store');
    Route::get('attendance/{attendance}', [\App\Http\Controllers\AttendanceController::class, 'show'])->name('students.attendance.show');
    Route::get('attendance/{attendance}/edit', [\App\Http\Controllers\AttendanceController::class, 'edit'])->name('students.attendance.edit');
    Route::put('attendance/{attendance}', [\App\Http\Controllers\AttendanceController::class, 'update'])->name('students.attendance.update');
    Route::delete('attendance/{attendance}', [\App\Http\Controllers\AttendanceController::class, 'destroy'])->name('students.attendance.destroy');

});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__ . '/auth.php';
