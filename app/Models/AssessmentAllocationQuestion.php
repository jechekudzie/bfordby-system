<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssessmentAllocationQuestion extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['assessment_allocation_id', 'question_text', 'question_type', 'weight', 'order'];

    public function assessmentAllocation()
    {
        return $this->belongsTo(AssessmentAllocation::class);
    }

    public function options()
    {
        return $this->hasMany(AssessmentAllocationQuestionOption::class);
    }
}
