<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModuleAssessmentWeight extends Model
{
    protected $fillable = [
        'module_id',
        'assessment_type',
        'weight'
    ];

    /**
     * Get the module that owns the weight.
     */
    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    /**
     * Get assessments of this type for the module
     */
    public function assessments()
    {
        return $this->hasMany(Assessment::class, 'type', 'assessment_type')
                    ->where('module_id', $this->module_id);
    }
}
