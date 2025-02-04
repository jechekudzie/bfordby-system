<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Identification extends Model
{
    //
    protected $guarded = [];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function issuingCountry()
    {
        return $this->belongsTo(Country::class, 'issuing_country_id');
    }

    
}
