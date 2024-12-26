<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;  


class Attendance extends Model
{
    use SoftDeletes;
    use HasSlug;

    protected $fillable = [
        'student_id',
        'subject_id',
        'semester_id',
        'date',
        'status',
        'remarks',
        'slug'
    ];

    protected $dates = [
        'date'
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
            ->generateSlugsFrom(['student_id', 'qualification_level_id', 'program_name'])
            ->saveSlugsTo('slug');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }



} 
