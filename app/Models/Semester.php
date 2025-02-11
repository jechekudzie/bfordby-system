<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Semester extends Model
{
    use SoftDeletes;
    use HasSlug;

    protected $fillable = [
        'name',
        'academic_year',
        'start_date',
        'end_date',
        'type', // Semester or Trimester
        'status',
        'slug'
    ];

    protected $dates = [
        'start_date',
        'end_date'
    ];

    public function studentSubjects()
    {
        return $this->hasMany(StudentSubject::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function assessmentAllocations()
    {
        return $this->hasMany(AssessmentAllocation::class);
    }

    //slug

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
