<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Subject extends Model
{
    use SoftDeletes;
    use HasSlug;
    protected $fillable = [
        'name',
        'code',
        'description',
        'course_id',
        'credits',
        'level',
        'prerequisites',
        'status',
        'slug'
    ];

    //softDeletes
    protected $softDeletes = true;

    protected $casts = [
        'prerequisites' => 'array'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function modules()
    {
        return $this->hasMany(Module::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_subjects')
                    ->withPivot(['grade', 'semester', 'academic_year'])
                    ->withTimestamps();
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
