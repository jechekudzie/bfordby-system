<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenderSeeder extends Seeder
{
    public function run()
    {
        $genders = [
            ['name' => 'Male', 'created_at' => now()],
            ['name' => 'Female', 'created_at' => now()],
            ['name' => 'Other', 'created_at' => now()],
        ];

        DB::table('genders')->insert($genders);
    }
}
