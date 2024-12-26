<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Language extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'student_id',
        'name',
        'proficiency_level',
        'is_native',
        'speaking',
        'writing',
        'reading',
        'listening'
    ];

    protected $casts = [
        'is_native' => 'boolean'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
