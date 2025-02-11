<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;



class Module extends Model
{
    //
    use SoftDeletes, HasSlug;

    protected $fillable = ['subject_id', 'name', 'description', 'slug'];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function assessments()
    {
        return $this->hasMany(Assessment::class);
    }

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
