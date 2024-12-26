<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Assessment extends Model
{
    use SoftDeletes;
    use HasSlug;
    
    protected $fillable = [
        'subject_id',
        'name',
        'description',
        'type',
        'max_score',
        'due_date',
        'status',
        'slug'
    ];

    protected $dates = [
        'due_date'
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function studentAssessments()
    {
        return $this->hasMany(StudentAssessment::class);
    }

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(['name'])
            ->saveSlugsTo('slug');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

}
