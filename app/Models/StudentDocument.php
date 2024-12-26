<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentDocument extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'student_id',
        'name',
        'type',
        'file_path',
        'file_name',
        'file_size',
        'mime_type',
        'issued_date',
        'expiry_date',
        'status',
        'notes'
    ];

    protected $dates = [
        'issued_date',
        'expiry_date'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
} 