<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class StudentPayment extends Model
{
    use SoftDeletes;
    use HasSlug;

    protected $fillable = [
        'student_id',
        'semester_id',
        'amount_invoiced',
        'amount_paid',
        'amount_balance',
        'payment_date',
        'due_date',
        'payment_method',
        'reference_number',
        'description',
        'status',
        'notes',
        'slug'
    ];

    protected $dates = [
        'payment_date',
        'due_date'
    ];

    protected $casts = [
        'amount_invoiced' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'amount_balance' => 'decimal:2'
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
            ->generateSlugsFrom('reference_number')
            ->saveSlugsTo('slug');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
} 
