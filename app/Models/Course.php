<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
    
class Course extends Model
{
    use SoftDeletes;
    use HasSlug;
    protected $fillable = [
        'name',
        'code',
        'description',
        'duration_months',
        'fee',
        'status',
        'slug'
    ];

    //softDeletes
    protected $softDeletes = true;


    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    /**
     * The study modes that belong to the course.
     */
    public function studyModes()
    {
        return $this->belongsToMany(StudyMode::class, 'course_study_modes')
                    ->withTimestamps();
    }

    public function studentCourses()
    {
        return $this->hasMany(StudentCourse::class);
    }


    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }
     //slug
     public function getSlugOptions() : SlugOptions
     {
         return SlugOptions::create()
             ->generateSlugsFrom('name')
             ->saveSlugsTo('slug');
     }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
