<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseStudyMode extends Model
{
    //
    protected $fillable = ['course_id', 'study_mode_id'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function studyMode()
    {
        return $this->belongsTo(StudyMode::class);
    }
}
