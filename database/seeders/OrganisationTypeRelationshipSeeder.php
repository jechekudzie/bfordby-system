<?php

namespace Database\Seeders;

use App\Models\OrganisationType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrganisationTypeRelationshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $relationships = [
            ['organisation_type_id' => 1, 'child_id' => 2, 'notes' => null, 'created_at' => Carbon::parse('2024-01-28 08:01:22'), 'updated_at' => Carbon::parse('2024-01-28 08:01:22')],
        ];

        DB::table('organisation_type_organisation_type')->insert($relationships);


    }
}
