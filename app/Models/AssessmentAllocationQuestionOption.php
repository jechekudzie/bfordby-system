<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class AssessmentAllocationQuestionOption extends Model
{
    //
    use HasFactory;

    protected $fillable = ['assessment_allocation_question_id', 'option_text', 'is_correct'];

    public function question()
    {
        return $this->belongsTo(AssessmentAllocationQuestion::class);
    }
}
