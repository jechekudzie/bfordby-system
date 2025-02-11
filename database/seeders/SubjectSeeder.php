<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Subject;
use App\Models\Course;

class SubjectSeeder extends Seeder
{
    public function run()
    {
        $subjects = [
            [
                'name' => 'Crop Production',
                'code' => 'AGR101',
                'description' => 'Fundamentals of crop production and management',
                'credits' => 4,
            ],
            [
                'name' => 'Animal Husbandry',
                'code' => 'AGR102',
                'description' => 'Principles of animal breeding and management',
                'credits' => 4,
            ],
            [
                'name' => 'Farm Mechanization',
                'code' => 'AGR103',
                'description' => 'Agricultural machinery and equipment operation',
                'credits' => 3,
            ],
            [
                'name' => 'Agribusiness Management',
                'code' => 'AGR104',
                'description' => 'Business principles in agricultural operations',
                'credits' => 3,
            ],
            [
                'name' => 'Soil Science',
                'code' => 'AGR105',
                'description' => 'Study of soil properties and management',
                'credits' => 3,
            ],
        ];

        // Get all courses
        $courses = Course::all();

        // For each course, create all subjects
        foreach ($courses as $course) {
            foreach ($subjects as $subject) {
                Subject::create([
                    'course_id' => $course->id,
                    'name' => $subject['name'],
                    'code' => $subject['code'], // Make code unique per course
                    'description' => $subject['description'],
                    'credits' => $subject['credits'],
                    'created_at' => now(),
                ]);
            }
        }
    }
}
