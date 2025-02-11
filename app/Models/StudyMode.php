<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudyMode extends Model
{
    //
    protected $fillable = ['name'];

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_study_modes');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }
}
