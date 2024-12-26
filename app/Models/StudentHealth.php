<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentHealth extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'student_id',
        'blood_group',
        'allergies',
        'medical_conditions',
        'medications',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
        'insurance_provider',
        'insurance_number',
        'last_checkup_date',
        'notes'
    ];

    protected $dates = [
        'last_checkup_date'
    ];

    protected $casts = [
        'allergies' => 'array',
        'medical_conditions' => 'array',
        'medications' => 'array'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
} 