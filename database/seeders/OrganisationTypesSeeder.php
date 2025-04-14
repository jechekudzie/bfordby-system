<?php

namespace Database\Seeders;

use App\Models\Admin\OrganisationType;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrganisationTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $organisationTypes = [

            ['name' => 'System users', 'slug' => 'system-users', 'description' => 'Users with access to the core system functionalities, typically involved in daily operations and management tasks.', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Developers', 'slug' => 'developers', 'description' => 'Technical professionals responsible for developing, maintaining, and updating the system software.', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
           
        ];

        foreach ($organisationTypes as $type) {
            OrganisationType::create($type);
        }

    }
}
