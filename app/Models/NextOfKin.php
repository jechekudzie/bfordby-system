<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NextOfKin extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'next_of_kins';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_id',
        'first_name',
        'last_name',
        'relationship',
        'phone_number',
        'email',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'zip_code',
        'country_id',
        'is_emergency_contact',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_emergency_contact' => 'boolean',
    ];

    /**
     * Get the student that owns the next of kin.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the country of the next of kin.
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Get the full name of the next of kin.
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
} 