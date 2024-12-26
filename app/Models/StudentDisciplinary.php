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
} 