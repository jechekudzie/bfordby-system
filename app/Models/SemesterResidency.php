<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class SemesterResidency extends Model
{
    use SoftDeletes;
    use HasSlug;
    protected $fillable = [
        'student_id',
        'semester_id',
        'room_number',
        'block_name',
        'check_in_date',
        'check_out_date',
        'payment_status',
        'amount_paid',
        'payment_date',
        'damages',
        'damage_charges',
        'status',
        'notes'
    ];

    protected $dates = [
        'check_in_date',
        'check_out_date',
        'payment_date'
    ];

    protected $casts = [
        'amount_paid' => 'decimal:2',
        'damage_charges' => 'decimal:2'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    //slug
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('room_number')
            ->saveSlugsTo('slug');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
