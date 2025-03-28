<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    //
    protected $fillable = [
        'assessment_allocation_id',
        'name'
    ];

    public function assessmentAllocation()
    {
        return $this->belongsTo(AssessmentAllocation::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'group_students');
    }
}
