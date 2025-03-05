<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'assessment_allocation_id',
        'content',
        'file_path',
        'answers',
        'status',
        'submitted_at',
        'graded_at',
        'grade',
        'feedback'
    ];

    protected $casts = [
        'answers' => 'array',
        'submitted_at' => 'datetime',
        'graded_at' => 'datetime'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function assessmentAllocation()
    {
        return $this->belongsTo(AssessmentAllocation::class);
    }
} 