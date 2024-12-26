<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QualificationLevel extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'level_order'
    ];

    public function academicHistories()
    {
        return $this->hasMany(AcademicHistory::class);
    }
}
