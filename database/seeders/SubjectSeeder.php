<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubjectSeeder extends Seeder
{
    public function run()
    {
        // Get course IDs
        $fullTimeCourseId = DB::table('courses')->where('code', 'GAD28')->value('id');
        $partTimeCourseId = DB::table('courses')->where('code', 'GADPT04')->value('id');

        $subjects = [
            // Full Time Course Subjects
            [
                'course_id' => $fullTimeCourseId,
                'name' => 'Crop Production',
                'code' => 'AGR101',
                'description' => 'Fundamentals of crop production and management',
                'credits' => 4,
                'created_at' => now(),
            ],
            [
                'course_id' => $fullTimeCourseId,
                'name' => 'Animal Husbandry',
                'code' => 'AGR102',
                'description' => 'Principles of animal breeding and management',
                'credits' => 4,
                'created_at' => now(),
            ],
            [
                'course_id' => $fullTimeCourseId,
                'name' => 'Farm Mechanization',
                'code' => 'AGR103',
                'description' => 'Agricultural machinery and equipment operation',
                'credits' => 3,
                'created_at' => now(),
            ],
            [
                'course_id' => $fullTimeCourseId,
                'name' => 'Agribusiness Management',
                'code' => 'AGR104',
                'description' => 'Business principles in agricultural operations',
                'credits' => 3,
                'created_at' => now(),
            ],
            [
                'course_id' => $fullTimeCourseId,
                'name' => 'Soil Science',
                'code' => 'AGR105',
                'description' => 'Study of soil properties and management',
                'credits' => 3,
                'created_at' => now(),
            ],

            // Part Time Course Subjects
            [
                'course_id' => $partTimeCourseId,
                'name' => 'Crop Production',
                'code' => 'AGRPT101',
                'description' => 'Fundamentals of crop production and management',
                'credits' => 4,
                'created_at' => now(),
            ],
            [
                'course_id' => $partTimeCourseId,
                'name' => 'Animal Husbandry',
                'code' => 'AGRPT102',
                'description' => 'Principles of animal breeding and management',
                'credits' => 4,
                'created_at' => now(),
            ],
            [
                'course_id' => $partTimeCourseId,
                'name' => 'Farm Mechanization',
                'code' => 'AGRPT103',
                'description' => 'Agricultural machinery and equipment operation',
                'credits' => 3,
                'created_at' => now(),
            ],
            [
                'course_id' => $partTimeCourseId,
                'name' => 'Agribusiness Management',
                'code' => 'AGRPT104',
                'description' => 'Business principles in agricultural operations',
                'credits' => 3,
                'created_at' => now(),
            ],
            [
                'course_id' => $partTimeCourseId,
                'name' => 'Soil Science',
                'code' => 'AGRPT105',
                'description' => 'Study of soil properties and management',
                'credits' => 3,
                'created_at' => now(),
            ],
        ];

        DB::table('subjects')->insert($subjects);
    }
}
