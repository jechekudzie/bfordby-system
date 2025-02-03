<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Course;

class CourseSeeder extends Seeder
{
    public function run()
    {
        $courses = [
            [
                'name' => 'Diploma in Agriculture (Full Time)',
                'code' => 'GAD28',
                'description' => 'A comprehensive program emphasizing practical commercial farming, including animal production, crop production, farm mechanisation, and agribusiness management.',
                'created_at' => now(),
            ],
            [
                'name' => 'Diploma in Agriculture (Part Time)',
                'code' => 'GADPT04',
                'description' => 'A part-time diploma program focused on agricultural disciplines taught via digital platforms with Saturday practicals.',
                'created_at' => now(),
            ],
        ];

       foreach ($courses as $course) {
        Course::create($course);
       }
    }
}