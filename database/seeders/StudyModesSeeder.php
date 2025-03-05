<?php

namespace Database\Seeders;

use App\Models\StudyMode;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudyModesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $studyModes = [
            [
                'name' => 'Full Time',
                'description' => 'Regular full-time study mode',
            ],
            [
                'name' => 'Part Time',
                'description' => 'Flexible part-time study mode',
            ],
        
        ];

        foreach ($studyModes as $mode) {
            StudyMode::create($mode);
        }
    }
}
