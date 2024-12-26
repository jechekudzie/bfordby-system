<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddressTypeSeeder extends Seeder
{
    public function run()
    {
        $types = [
            ['name' => 'Permanent Address', 'description' => 'Permanent residence address', 'created_at' => now()],
            ['name' => 'Current Address', 'description' => 'Current living address', 'created_at' => now()],
            ['name' => 'Parents Address', 'description' => 'Address of parents', 'created_at' => now()],
            ['name' => 'Guardian Address', 'description' => 'Address of guardian', 'created_at' => now()],
        ];

        DB::table('address_types')->insert($types);
    }
}
