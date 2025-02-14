<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Assessment extends Model
{
    use SoftDeletes, HasSlug;

    protected $fillable = [
        'module_id', 'name', 'description', 'type', 'max_score', 'status', 'slug'
    ];

    protected $dates = ['due_date'];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function allocations()
    {
        return $this->hasMany(AssessmentAllocation::class);
    }

    public function questions()
    {
        return $this->hasMany(AssessmentQuestion::class);
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
