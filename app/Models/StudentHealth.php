<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentHealth extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'blood_group',
        'allergies',
        'medical_conditions',
        'medications',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
        'last_checkup_date',
        'notes'
    ];

    protected $casts = [
        'last_checkup_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
} 