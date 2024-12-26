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
        'study_mode',
        'status',
        'slug'
    ];

    //softDeletes
    protected $softDeletes = true;


    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    public function studentCourses()
    {
        return $this->hasMany(StudentCourse::class);
    }


     //slug
     public function getSlugOptions() : SlugOptions
     {
         return SlugOptions::create()
             ->generateSlugsFrom(['name'])
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
