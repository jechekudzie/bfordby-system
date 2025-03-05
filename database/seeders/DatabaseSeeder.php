<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // First, seed reference tables
        $this->call([
            GenderSeeder::class,
            ContactTypeSeeder::class,
            AddressTypeSeeder::class,
            TitleSeeder::class,
        ]);

        // Then seed main tables in order of dependencies
        $this->call([
            CourseSeeder::class,
            SubjectSeeder::class,
            SemesterSeeder::class,
        ]);

        $this->call([
            StudyModesSeeder::class,
        ]);
    }
}
