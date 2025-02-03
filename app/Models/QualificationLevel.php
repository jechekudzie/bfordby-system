<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class QualificationLevel extends Model
{
    use SoftDeletes, HasSlug;
    
    protected $fillable = [
        'name',
        'code',
        'description',
        'level_order',
        'slug'
    ];

  

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

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function academicHistories()
    {
        return $this->hasMany(AcademicHistory::class);
    }
}
