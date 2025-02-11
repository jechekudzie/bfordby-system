<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnrollmentCode extends Model
{
    //
    protected $fillable = ['course_id', 'study_mode_id', 'year', 'current_number', 'base_code'];


    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function studyMode()
    {
        return $this->belongsTo(StudyMode::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function assessmentAllocations()
    {
        return $this->hasMany(AssessmentAllocation::class);
    }
}
