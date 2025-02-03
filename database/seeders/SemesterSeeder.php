<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Semester;
use Carbon\Carbon;

class SemesterSeeder extends Seeder
{
    public function run()
    {
        $currentYear = Carbon::now()->year;
        
        $semesters = [
            [
                'name' => 'Trimester 1 ' . $currentYear,
                'academic_year' => $currentYear,
                'start_date' => Carbon::create($currentYear, 5, 1),
                'end_date' => Carbon::create($currentYear, 8, 31),
                'type' => 'trimester',
                'status' => 'completed',
                'created_at' => now(),
            ],
            [
                'name' => 'Trimester 2 ' . $currentYear,
                'academic_year' => $currentYear,
                'start_date' => Carbon::create($currentYear, 9, 1),
                'end_date' => Carbon::create($currentYear, 12, 31),
                'type' => 'trimester',
                'status' => 'active',
                'created_at' => now(),
            ],
            [
                'name' => 'Trimester 3 ' . ($currentYear + 1),
                'academic_year' => $currentYear + 1,
                'start_date' => Carbon::create($currentYear + 1, 1, 1),
                'end_date' => Carbon::create($currentYear + 1, 4, 30),
                'type' => 'trimester',
                'status' => 'upcoming',
                'created_at' => now(),
            ],
            [
                'name' => 'Semester 1 ' . $currentYear,
                'academic_year' => $currentYear,
                'start_date' => Carbon::create($currentYear, 1, 1),
                'end_date' => Carbon::create($currentYear, 5, 31),
                'type' => 'semester',
                'status' => 'completed',
                'created_at' => now(),
            ],
            [
                'name' => 'Semester 2 ' . $currentYear,
                'academic_year' => $currentYear,
                'start_date' => Carbon::create($currentYear, 7, 1),
                'end_date' => Carbon::create($currentYear, 11, 30),
                'type' => 'semester',
                'status' => 'active',
                'created_at' => now(),
            ],
            [
                'name' => 'Semester 3 ' . ($currentYear + 1),
                'academic_year' => $currentYear + 1,
                'start_date' => Carbon::create($currentYear + 1, 1, 1),
                'end_date' => Carbon::create($currentYear + 1, 5, 31),
                'type' => 'semester',
                'status' => 'upcoming',
                'created_at' => now(),
            ],
            [
                'name' => 'Semester 4 ' . ($currentYear + 1),
                'academic_year' => $currentYear + 1,
                'start_date' => Carbon::create($currentYear + 1, 7, 1),
                'end_date' => Carbon::create($currentYear + 1, 11, 30),
                'type' => 'semester',
                'status' => 'upcoming',
                'created_at' => now(),
            ],
        ];

       foreach ($semesters as $semester) {
        Semester::create($semester);
       }



    }
}
