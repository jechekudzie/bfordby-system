<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gender;

class GenderSeeder extends Seeder
{
    public function run()
    {
        $genders = [
            ['name' => 'Male', 'created_at' => now()],
            ['name' => 'Female', 'created_at' => now()],
            ['name' => 'Other', 'created_at' => now()],
        ];

        foreach ($genders as $gender) {
            Gender::create($gender);
        }
    }
}
