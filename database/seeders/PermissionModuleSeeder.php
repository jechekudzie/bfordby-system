<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin\PermissionModule;
use Spatie\Permission\Models\Permission;

class PermissionModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //give all the system modules for the system
        $modules = [
            'students',
            'modules',
            'assessments',
            'results',
            'reports',
            'settings',
            'users',
            'roles',
            'permissions',
            'organisations',
            'organisation-types',
            'organisation-roles',
            'organisation-permissions',
            'courses',
            'subjects',
            'semesters',
            'study-modes',
            'enrollment-codes',
            'enrollments',
            'payments',
            'payments-methods',
            'payments-statuses',
            'payments-types',
            'payments-categories',
            'payments-subcategories',
            'payments-transactions',
        ];
       

        foreach ($modules as $module) {
            PermissionModule::create(['name' => ucfirst($module)]);

            //create permissions for the module
            $module = PermissionModule::where('name', $module)->first();
            $permissions = ['view', 'create', 'read', 'update', 'delete'];
            foreach ($permissions as $permission) {
                Permission::create(['name' => $permission . '-' . $module->slug]);
            }
        }
    }
}
