<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Address extends Model
{

    use HasSlug;
    protected $fillable = [
        'student_id',
        'address_type_id',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'zip_code',
        'country_id'
    ];



    //softDeletes
    protected $softDeletes = true;

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function addressType()
    {
        return $this->belongsTo(AddressType::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    //slug
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(['address_line1', 'country_id'])
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
