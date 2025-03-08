<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssessmentAllocationSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'assessment_allocation_id',
        'student_id',
        'group_id',
        'content',
        'answers',
        'grades',
        'feedback',
        'file_path',
        'start_time',
        'submitted_at',
        'graded_at',
        'grade',
        'status'
    ];

    protected $casts = [
        'answers' => 'array',
        'grades' => 'array',
        'feedback' => 'array',
        'start_time' => 'datetime',
        'submitted_at' => 'datetime',
        'graded_at' => 'datetime',
        'grade' => 'decimal:2'
    ];

    public function assessmentAllocation()
    {
        return $this->belongsTo(AssessmentAllocation::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Check if the submission is for a timed assessment and still within the time limit
     */
    public function isWithinTimeLimit(): bool
    {
        if (!$this->start_time || !$this->assessmentAllocation->is_timed) {
            return true;
        }

        $endTime = $this->start_time->addMinutes($this->assessmentAllocation->duration_minutes);
        return now() <= $endTime;
    }

    /**
     * Get the remaining time for a timed assessment in seconds
     */
    public function getRemainingTime(): ?int
    {
        if (!$this->start_time || !$this->assessmentAllocation->is_timed) {
            return null;
        }

        $endTime = $this->start_time->addMinutes($this->assessmentAllocation->duration_minutes);
        $remainingSeconds = max(0, $endTime->diffInSeconds(now()));
        
        return $remainingSeconds;
    }
}
