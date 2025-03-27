<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;



class Module extends Model
{
    use SoftDeletes, HasSlug;

    protected $fillable = ['subject_id', 'name', 'description', 'slug'];

    /**
     * Get the assessment structures for this module
     */
    public function assessmentStructures()
    {
        return $this->hasMany(ModuleAssessmentStructure::class);
    }

    /**
     * Get assessment type weights (non-trimester)
     */
    public function assessmentTypeWeights()
    {
        return $this->assessmentStructures()->assessmentTypes();
    }

    /**
     * Get trimester weights
     */
    public function trimesterWeights()
    {
        return $this->assessmentStructures()->trimesterWeights();
    }

    /**
     * Calculate trimester grade for a student
     */
    public function calculateTrimesterGrade($studentId, $semesterId)
    {
        $semester = Semester::find($semesterId);
        if (!$semester) return 0;

        $assessments = $this->assessments()
            ->whereHas('allocations', function($query) use ($semesterId) {
                $query->where('semester_id', $semesterId);
            })
            ->get();

        $trimesterGrade = 0;
        foreach ($assessments as $assessment) {
            $trimesterGrade += $assessment->calculateWeightedGrade($studentId, $semesterId);
        }

        return $trimesterGrade;
    }

    /**
     * Calculate final grade for a student across all semesters in a year
     */
    public function calculateFinalGrade($studentId, $year)
    {
        $semesters = Semester::where('year', $year)->get();
        $finalGrade = 0;

        foreach ($semesters as $semester) {
            $finalGrade += $this->calculateTrimesterGrade($studentId, $semester->id);
        }

        return $finalGrade;
    }

    /**
     * Get grade classification (Distinction, Merit, etc.)
     */
    public function getGradeClassification($grade)
    {
        if ($grade >= 75) return 'Distinction';
        if ($grade >= 65) return 'Merit';
        if ($grade >= 60) return 'Credit';
        if ($grade >= 50) return 'Pass';
        return 'Fail';
    }

    /**
     * Validate assessment type weights total 100%
     */
    public function validateAssessmentTypeWeights()
    {
        $totalWeight = $this->assessmentTypeWeights()->sum('weight');
        return $totalWeight == 100;
    }

    /**
     * Validate trimester weights total 100%
     */
    public function validateTrimesterWeights()
    {
        $totalWeight = $this->trimesterWeights()->sum('weight');
        return $totalWeight == 100;
    }


    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function assessments()
    {
        return $this->hasMany(Assessment::class);
    }

    public function getSlugOptions() : SlugOptions
     {
         return SlugOptions::create()
             ->generateSlugsFrom(['name'])
             ->saveSlugsTo('slug');
     }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }


}
