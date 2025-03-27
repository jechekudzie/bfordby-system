<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModuleAssessmentStructure extends Model
{
    protected $fillable = [
        'module_id',
        'assessment_contribution_type_id',
        'weight',
        'is_trimester_weight',
        'trimester'
    ];

    /**
     * Get the module that owns this assessment structure
     */
    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    /**
     * Get the contribution type for this structure
     */
    public function contributionType()
    {
        return $this->belongsTo(AssessmentContributionType::class, 'assessment_contribution_type_id');
    }

    /**
     * Get assessments of this type for the module
     */
    public function assessments()
    {
        return $this->hasMany(Assessment::class, 'type', 'name')
                    ->where('module_id', $this->module_id);
    }

    /**
     * Scope for assessment type weights (non-trimester)
     */
    public function scopeAssessmentTypes($query)
    {
        return $query->where('is_trimester_weight', false);
    }

    /**
     * Scope for trimester weights
     */
    public function scopeTrimesterWeights($query)
    {
        return $query->where('is_trimester_weight', true);
    }
}
