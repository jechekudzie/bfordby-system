<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SickNote extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'student_id',
        'issue_date',
        'valid_from',
        'valid_until',
        'diagnosis',
        'notes',
        'issuing_doctor',
        'medical_facility',
        'document_path',
        'is_active'
    ];

    protected $casts = [
        'issue_date' => 'date',
        'valid_from' => 'date',
        'valid_until' => 'date',
        'is_active' => 'boolean'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
} 