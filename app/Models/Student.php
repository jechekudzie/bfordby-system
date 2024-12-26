<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Student extends Model
{
    use HasFactory;
    use HasSlug;
    protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        'date_of_birth',
        'gender_id',
        'enrollment_date',
        'status'
    ];

    //softDeletes
    protected $softDeletes = true;

    public function gender()
    {
        return $this->belongsTo(Gender::class);
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function identifications()
    {
        return $this->hasMany(Identification::class);
    }

    public function guardians()
    {
        return $this->hasMany(Guardian::class);
    }

    public function languages()
    {
        return $this->hasMany(Language::class);
    }

    public function scholarships()
    {
        return $this->hasMany(Scholarship::class);
    }

    public function academicHistories()
    {
        return $this->hasMany(AcademicHistory::class);
    }

    public function studentCourses()
    {
        return $this->hasMany(StudentCourse::class);
    }

    public function studentPayments()
    {
        return $this->hasMany(StudentPayment::class);
    }

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(['first_name', 'last_name', 'middle_name'])
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
