<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentDisciplinary extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'student_id',
        'incident_date',
        'incident_type',
        'description',
        'location',
        'reported_by',
        'witnesses',
        'action_taken',
        'sanction',
        'start_date',
        'end_date',
        'status',
        'notes'
    ];

    protected $dates = [
        'incident_date',
        'start_date',
        'end_date'
    ];

    protected $casts = [
        'witnesses' => 'array'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public const INCIDENT_TYPES = [
        'academic_misconduct' => 'Academic Misconduct',
        'behavioral_misconduct' => 'Behavioral Misconduct',
        'attendance_issues' => 'Attendance Issues',
        'bullying' => 'Bullying',
        'vandalism' => 'Vandalism',
        'substance_abuse' => 'Substance Abuse',
        'other' => 'Other'
    ];

    public const SANCTIONS = [
        'verbal_warning' => 'Verbal Warning',
        'written_warning' => 'Written Warning',
        'suspension' => 'Suspension',
        'other' => 'Other'
    ];

    public const STATUSES = [
        'pending',
        'active',
        'resolved',
        'appealed'
    ];
} 