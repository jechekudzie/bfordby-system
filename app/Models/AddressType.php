<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;   

class AddressType extends Model
{
    use HasSlug;

    protected $fillable = [
        'name',
        'description'
    ];

    //softDeletes
    protected $softDeletes = true;

    public function addresses()
    {
        return $this->hasMany(Address::class);
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
