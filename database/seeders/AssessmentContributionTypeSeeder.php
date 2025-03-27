<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AssessmentContributionType;

class AssessmentContributionTypeSeeder extends Seeder
{
    public function run(): void
    {
        // Assessment Types
        $types = [
            [
                'name' => 'Coursework',
                'description' => 'Continuous Assessment'
            ],
            [
                'name' => 'Practical',
                'description' => 'Practical Examination'
            ],
            [
                'name' => 'Test',
                'description' => 'Regular Tests'
            ],
            [
                'name' => 'Theory',
                'description' => 'Theory Examination'
            ],
            // Subject Types
            [
                'name' => 'Animal Production',
                'description' => 'Animal Production Module'
            ],
            [
                'name' => 'Crop Production',
                'description' => 'Crop Production Module'
            ],
            [
                'name' => 'Farm & Agribusiness Management',
                'description' => 'Farm & Agribusiness Management Module'
            ],
            [
                'name' => 'Farm Mechanisation',
                'description' => 'Farm Mechanisation Module'
            ]
        ];

        foreach ($types as $type) {
            AssessmentContributionType::create($type);
        }
    }
}
