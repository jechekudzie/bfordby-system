<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;   


class AcademicHistory extends Model
{
    use HasSlug;
    use SoftDeletes;

    protected $fillable = [
        'student_id',
        'institution_name',
        'qualification_level_id',
        'program_name',
        'start_date',
        'completion_date',
        'grade_achieved',
        'certificate_number',
        'certificate_path',
        'transcript_path',
        'status',
        'notes',
        'slug'
    ];

    protected $dates = [
        'start_date',
        'completion_date'
    ];
   

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function qualificationLevel()
    {
        return $this->belongsTo(QualificationLevel::class);
    }

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(['student_id', 'qualification_level_id'])
            ->saveSlugsTo('slug');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

   
}
