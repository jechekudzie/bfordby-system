<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssessmentContributionType extends Model
{
    protected $fillable = [
        'name',
        'description'
    ];

    /**
     * Get all module assessment structures using this contribution type
     */
    public function moduleStructures()
    {
        return $this->hasMany(ModuleAssessmentStructure::class);
    }
}
