<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class StudentSubject extends Model
{
    use SoftDeletes;
    use HasSlug;

    protected $fillable = [
        'student_id',
        'subject_id',
        'semester_id',
        'academic_year',
        'grade',
        'status',
        'notes',
        'slug'
    ]; 

    protected $casts = [
        'grade' => 'decimal:2',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(['student_id', 'subject_id', 'semester_id'])
            ->saveSlugsTo('slug');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
