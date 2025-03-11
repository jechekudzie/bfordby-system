<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Subject;
use App\Models\Module;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define modules for each subject/discipline
        $subjectModules = [
            'Crop Production' => [
                [
                    'name' => 'Introduction to Crop Science',
                    'description' => 'Basic principles of crop growth and development'
                ],
                [
                    'name' => 'Cereal Crop Production',
                    'description' => 'Cultivation techniques for cereal crops like maize, wheat, and rice'
                ],
                [
                    'name' => 'Vegetable Production',
                    'description' => 'Growing techniques for various vegetable crops'
                ],
                [
                    'name' => 'Crop Protection',
                    'description' => 'Pest and disease management in crop production'
                ],
                [
                    'name' => 'Sustainable Farming Practices',
                    'description' => 'Environmentally friendly approaches to crop cultivation'
                ]
            ],
            'Animal Husbandry' => [
                [
                    'name' => 'Livestock Management',
                    'description' => 'Basic principles of managing farm animals'
                ],
                [
                    'name' => 'Animal Nutrition',
                    'description' => 'Feed requirements and nutritional management of farm animals'
                ],
                [
                    'name' => 'Animal Health',
                    'description' => 'Disease prevention and health management in livestock'
                ],
                [
                    'name' => 'Breeding Techniques',
                    'description' => 'Principles and methods of animal breeding'
                ],
                [
                    'name' => 'Dairy Production',
                    'description' => 'Management of dairy animals and milk production'
                ]
            ],
            'Farm Mechanization' => [
                [
                    'name' => 'Agricultural Machinery',
                    'description' => 'Types and functions of farm machinery'
                ],
                [
                    'name' => 'Tractor Operation',
                    'description' => 'Principles of tractor operation and maintenance'
                ],
                [
                    'name' => 'Irrigation Systems',
                    'description' => 'Design and management of farm irrigation systems'
                ],
                [
                    'name' => 'Post-Harvest Technology',
                    'description' => 'Equipment and techniques for post-harvest processing'
                ],
                [
                    'name' => 'Farm Workshop Practices',
                    'description' => 'Basic repair and maintenance of farm equipment'
                ]
            ],
            'Agribusiness Management' => [
                [
                    'name' => 'Farm Business Planning',
                    'description' => 'Developing and implementing farm business plans'
                ],
                [
                    'name' => 'Agricultural Marketing',
                    'description' => 'Principles and strategies for marketing agricultural products'
                ],
                [
                    'name' => 'Farm Financial Management',
                    'description' => 'Budgeting, record keeping, and financial analysis for farms'
                ],
                [
                    'name' => 'Agricultural Value Chains',
                    'description' => 'Understanding and managing agricultural value chains'
                ],
                [
                    'name' => 'Entrepreneurship in Agriculture',
                    'description' => 'Developing entrepreneurial skills for agricultural ventures'
                ]
            ],
            'Soil Science' => [
                [
                    'name' => 'Soil Formation and Classification',
                    'description' => 'Understanding soil types and formation processes'
                ],
                [
                    'name' => 'Soil Fertility Management',
                    'description' => 'Techniques for maintaining and improving soil fertility'
                ],
                [
                    'name' => 'Soil Conservation',
                    'description' => 'Methods to prevent soil erosion and degradation'
                ],
                [
                    'name' => 'Soil Testing and Analysis',
                    'description' => 'Procedures for testing soil properties and interpreting results'
                ],
                [
                    'name' => 'Soil and Water Management',
                    'description' => 'Integrated approaches to soil and water conservation'
                ]
            ]
        ];

        // Get all subjects
        $subjects = Subject::all();

        // For each subject, create the corresponding modules
        foreach ($subjects as $subject) {
            // Find the matching modules for this subject
            if (isset($subjectModules[$subject->name])) {
                $modules = $subjectModules[$subject->name];
                
                // Create each module for this subject
                foreach ($modules as $moduleData) {
                    Module::create([
                        'subject_id' => $subject->id,
                        'name' => $moduleData['name'],
                        'description' => $moduleData['description']
                        // No need to specify 'slug' as it's automatically generated by the model
                    ]);
                }
                
                $this->command->info("Created modules for subject: {$subject->name}");
            }
        }
    }
}
