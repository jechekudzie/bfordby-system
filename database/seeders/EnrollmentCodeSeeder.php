<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EnrollmentCode;
use App\Models\Course;

class EnrollmentCodeSeeder extends Seeder
{
    public function run(): void
    {
        $course = Course::first();
        if (!$course) {
            throw new \Exception('Please ensure Course seeder has been run first.');
        }

        $studyMode = \App\Models\StudyMode::first();
        if (!$studyMode) {
            throw new \Exception('Please ensure StudyMode seeder has been run first.');
        }

        // Create a test enrollment code
        EnrollmentCode::create([
            'course_id' => $course->id,
            'study_mode_id' => $studyMode->id,
            'year' => 2025,
            'current_number' => 0,
            'base_code' => 'TEST25' // Example base code for test enrollments
        ]);
    }
}
