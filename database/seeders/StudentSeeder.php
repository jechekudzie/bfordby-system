<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\Course;
use App\Models\Gender;

class StudentSeeder extends Seeder
{
    public function run()
    {
        // Get first course and study mode
        $course = Course::first();
        if (!$course) {
            throw new \Exception('Please ensure Course seeder has been run first.');
        }
        
        // Get a gender ID
        $genderId = Gender::first()->id;
        if (!$genderId) {
            throw new \Exception('Please ensure Gender seeder has been run first.');
        }

        // Create test students
        $students = [
            [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'date_of_birth' => '2000-01-15',
                'gender_id' => $genderId,
                'enrollment_date' => now(),
                'status' => 'active',
                'slug' => \Str::slug('John Doe') . '-' . \Str::random(8),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'date_of_birth' => '2001-03-20',
                'gender_id' => $genderId,
                'enrollment_date' => now(),
                'status' => 'active',
                'slug' => \Str::slug('Jane Smith') . '-' . \Str::random(8),
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        // Insert students
        Student::insert($students);
    }
}
