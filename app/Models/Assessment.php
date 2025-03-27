<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Assessment extends Model
{
    use SoftDeletes, HasSlug;

    protected $fillable = [
        'module_id', 'name', 'description', 'type', 'max_score', 'status', 'slug'
    ];

    protected $dates = ['due_date'];

    /**
     * Get the contribution weight for this assessment type
     */
    public function getContributionWeight()
    {
        return $this->module->assessmentStructures()
            ->where('is_trimester_weight', false)
            ->whereHas('contributionType', function($query) {
                $query->where('name', $this->type);
            })
            ->first()
            ->weight ?? 0;
    }

    /**
     * Get the trimester weight for a given semester
     */
    public function getTrimesterWeight($semesterId)
    {
        $semester = Semester::find($semesterId);
        if (!$semester) return 0;

        return $this->module->assessmentStructures()
            ->where('is_trimester_weight', true)
            ->where('trimester', $semester->trimester)
            ->first()
            ->weight ?? 0;
    }

    /**
     * Calculate weighted grade for a student submission
     */
    public function calculateWeightedGrade($studentId, $semesterId)
    {
        $submission = $this->allocations()
            ->where('semester_id', $semesterId)
            ->whereHas('submissions', function($query) use ($studentId) {
                $query->where('student_id', $studentId)
                      ->whereNotNull('grade');
            })
            ->first()
            ?->submissions()
            ->where('student_id', $studentId)
            ->first();

        if (!$submission) return 0;

        $contributionWeight = $this->getContributionWeight();
        $trimesterWeight = $this->getTrimesterWeight($semesterId);
        
        return ($submission->grade * ($contributionWeight / 100) * ($trimesterWeight / 100));
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function allocations()
    {
        return $this->hasMany(AssessmentAllocation::class);
    }

    public function questions()
    {
        return $this->hasMany(AssessmentQuestion::class);
    }

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(['name'])
            ->saveSlugsTo('slug');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
