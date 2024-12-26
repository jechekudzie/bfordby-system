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
        // Get course IDs
        $fullTimeCourseId = Course::where('study_mode', 'full-time')->first()->id;
        $partTimeCourseId = Course::where('study_mode', 'part-time')->first()->id;
        
        // Get a gender ID
        $genderId = Gender::first()->id;

        // Create some full-time students
        $fullTimeStudents = [
            [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'date_of_birth' => '2000-01-15',
                'gender_id' => $genderId,
                'email' => 'john.doe@example.com',
                'phone' => '1234567890',
                'enrollment_date' => now(),
                
                'status' => 'active',
                'created_at' => now(),
            ],
            [
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'date_of_birth' => '2001-03-20',
                'gender_id' => $genderId,
                'email' => 'jane.smith@example.com',
                'phone' => '0987654321',
                'enrollment_date' => now(),
              
                'status' => 'active',
                'created_at' => now(),
            ],
        ];

        // Create some part-time students
        $partTimeStudents = [
            [
                'first_name' => 'Robert',
                'last_name' => 'Johnson',
                'date_of_birth' => '1995-06-10',
                'gender_id' => $genderId,
                'email' => 'robert.johnson@example.com',
                'phone' => '5555555555',
                'enrollment_date' => now(),
            
                'status' => 'active',
                'created_at' => now(),
            ],
            [
                'first_name' => 'Mary',
                'last_name' => 'Williams',
                'date_of_birth' => '1998-12-25',
                'gender_id' => $genderId,
                'email' => 'mary.williams@example.com',
                'phone' => '4444444444',
                'enrollment_date' => now(),
            
                'status' => 'active',
                'created_at' => now(),
            ],
        ];

        // Insert all students
        Student::insert(array_merge($fullTimeStudents, $partTimeStudents));
    }
}
