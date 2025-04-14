<?php

namespace Database\Seeders;

use App\Models\Admin\Organisation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;

class OrganisationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $organisations = [
            ['name' => 'Blackfordby College of Agriculture', 'organisation_type_id' => 1, 'organisation_id' => null, 'slug' => 'blackfordby-college-of-agriculture', 'created_at' => Carbon::parse('2024-01-28 08:03:49'), 'updated_at' => Carbon::parse('2024-01-28 08:03:49')],
            ['name' => 'Leading Digital', 'organisation_type_id' => 2, 'organisation_id' => 1, 'slug' => 'leading-digital', 'created_at' => Carbon::parse('2024-01-28 08:04:18'), 'updated_at' => Carbon::parse('2024-01-28 08:04:18')],
            
        ];

        foreach ($organisations as $organisation) {

            $newOrganisation = Organisation::create($organisation);

            //set the organisation as primary if organisation type is Rural District Council

            if ($organisation['organisation_type_id'] == 5) {
                $newOrganisation->is_primary = true;
                $newOrganisation->save();
            }

            // Create admin role
            $role = $newOrganisation->organisationRoles()->create([
                'name' => 'admin',
                'guard_name' => 'web',
            ]);

            // Check if the organisation name is similar to the ones that should have all permissions
            if (Str::lower($newOrganisation->name) === Str::lower("Blackfordby College of Agriculture") ||
                Str::lower($newOrganisation->name) === Str::lower("Leading Digital")
            ) {

                // Retrieve all permissions
                $permissions = Permission::all();
            } else {
                // Retrieve all permissions and reject the ones related to 'generic'
                $permissions = Permission::all()->reject(function ($permission) {
                    // Check if the permission name contains 'generic'
                    return Str::contains(Str::lower($permission->name), 'generic');
                });
            }

            // Find or create permissions based on the provided names
            $permissionsToAssign = [];
            foreach ($permissions as $permission) {
                // Ensure $permission->name is a string representing the permission name
                $permissionsToAssign[] = Permission::findOrCreate($permission->name);
            }

            // Sync permissions to the role (this will attach the new permissions and detach any that are not in the array)
            $role->syncPermissions($permissionsToAssign);

        }
    }
}
