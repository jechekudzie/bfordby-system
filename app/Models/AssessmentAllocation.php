<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssessmentAllocation extends Model
{
    protected $fillable = [
        'assessment_id', 'enrollment_code_id', 'semester_id', 'status', 'due_date', 'content', 'file_path'
    ];

    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }

    public function enrollmentCode()
    {
        return $this->belongsTo(EnrollmentCode::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }
}
