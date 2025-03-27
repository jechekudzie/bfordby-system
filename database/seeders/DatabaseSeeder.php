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
            AssessmentContributionTypeSeeder::class,
        ]);

        // Then seed main tables in order of dependencies
        $this->call([
            CourseSeeder::class,
            SubjectSeeder::class,
            ModuleSeeder::class,
            SemesterSeeder::class,
            StudyModesSeeder::class,
            StudentSeeder::class,
            EnrollmentCodeSeeder::class,
        ]);

        // Seed assessment structures and test data
        $this->call([
            ModuleAssessmentStructureSeeder::class,
            AssessmentSeeder::class,
        ]);
    }
}
