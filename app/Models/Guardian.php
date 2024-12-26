<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Guardian extends Model
{

    use HasSlug;
    protected $fillable = [
        'student_id',
        'first_name',
        'last_name',
        'relationship',
        'contact_details'
    ];

    //softDeletes
    protected $softDeletes = true;  

    public function student()
    {
        return $this->belongsTo(Student::class);
    }


    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(['first_name', 'last_name'])
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
