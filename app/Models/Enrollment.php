<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    //
    protected $fillable = ['student_id', 'course_id', 'study_mode_id', 'enrollment_code_id', 'enrollment_date',
     'status', 'end_date','entry_type'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function studyMode()
    {
        return $this->belongsTo(StudyMode::class, 'study_mode_id');
    }

    public function enrollmentCode()
    {
        return $this->belongsTo(EnrollmentCode::class, 'enrollment_code_id');
    }
}
