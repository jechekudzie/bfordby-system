<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;   

class Contact extends Model
{
    use HasSlug;
    protected $fillable = [
        'student_id',
        'contact_type_id',
        'value',
        'is_primary'
    ];

    //softDeletes
    protected $softDeletes = true;

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function contactType()
    {
        return $this->belongsTo(ContactType::class);
    }

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(['value'])
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
