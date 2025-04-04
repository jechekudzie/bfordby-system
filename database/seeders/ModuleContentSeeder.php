<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\ModuleContent;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ModuleContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Seeding module content...');
        
        // Get all modules
        $modules = Module::all();
        
        if ($modules->isEmpty()) {
            $this->command->warn('No modules found. Please run the module seeder first.');
            return;
        }
        
        // Create directories for storing dummy content
        $this->createDirectories();
        
        // Define content types and their data
        $contentTypes = [
            'pdf' => [
                'titles' => [
                    'Course Introduction',
                    'Lecture Notes',
                    'Assignment Guidelines',
                    'Reading Materials',
                    'Study Guide',
                ],
                'description' => 'This PDF document contains important information about the module.',
                'icon' => 'fa-file-pdf',
            ],
            'video' => [
                'titles' => [
                    'Video Tutorial',
                    'Practical Demonstration',
                    'Step-by-Step Guide',
                    'Recorded Lecture',
                    'Visual Explainer',
                ],
                'description' => 'This video demonstrates key concepts from the module.',
                'icon' => 'fa-file-video',
            ],
            'audio' => [
                'titles' => [
                    'Audio Lecture',
                    'Podcast Discussion',
                    'Interview with Expert',
                    'Audio Summary',
                    'Listening Exercise',
                ],
                'description' => 'This audio recording provides supplementary learning material.',
                'icon' => 'fa-file-audio',
            ],
            'image' => [
                'titles' => [
                    'Diagram Collection',
                    'Infographic Summary',
                    'Visual Reference Guide',
                    'Charts and Graphs',
                    'Illustration Set',
                ],
                'description' => 'These images provide visual references for key concepts.',
                'icon' => 'fa-file-image',
            ],
            'youtube' => [
                'titles' => [
                    'YouTube Tutorial',
                    'Expert YouTube Lecture',
                    'YouTube Explainer Video',
                    'Supplementary YouTube Resource',
                    'YouTube Demonstration',
                ],
                'description' => 'This YouTube video provides additional context and explanation.',
                'urls' => [
                    'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                    'https://www.youtube.com/watch?v=8aShfolR6w8',
                    'https://www.youtube.com/watch?v=7lCDEYXw3mM',
                    'https://www.youtube.com/watch?v=kbJcQYVtZMo',
                    'https://www.youtube.com/watch?v=xC_c7NgClUU',
                ],
                'icon' => 'fa-youtube',
            ],
            'external_link' => [
                'titles' => [
                    'External Resource',
                    'Further Reading',
                    'Reference Website',
                    'Additional Materials',
                    'Supplementary Guide',
                ],
                'description' => 'This external link provides additional resources and information.',
                'urls' => [
                    'https://www.coursera.org/courses',
                    'https://www.khanacademy.org/',
                    'https://www.udemy.com/courses/',
                    'https://www.codecademy.com/learn',
                    'https://www.edx.org/courses',
                ],
                'icon' => 'fa-external-link-alt',
            ],
        ];
        
        $count = 0;
        
        foreach ($modules as $module) {
            // Add 3-6 content items per module
            $numContent = rand(3, 6);
            
            // Keep track of sort order
            $sortOrder = 1;
            
            for ($i = 0; $i < $numContent; $i++) {
                // Randomly select content type
                $contentType = array_rand($contentTypes);
                $typeData = $contentTypes[$contentType];
                
                // Create module content
                $content = new ModuleContent();
                $content->module_id = $module->id;
                $content->title = $typeData['titles'][array_rand($typeData['titles'])];
                $content->description = $typeData['description'];
                $content->content_type = $contentType;
                $content->is_required = (rand(0, 1) == 1);
                $content->sort_order = $sortOrder++;
                
                // Handle file or URL based on content type
                if (in_array($contentType, ['pdf', 'video', 'audio', 'image'])) {
                    $filename = $this->getFileName($contentType);
                    $content->file_path = 'module-contents/' . $module->id . '/' . $filename;
                    
                    // Copy fake file to storage
                    $this->copyDummyFile($contentType, 'public/' . $content->file_path);
                } else {
                    // External URL or YouTube
                    $content->external_url = $typeData['urls'][array_rand($typeData['urls'])];
                }
                
                $content->save();
                $count++;
            }
        }
        
        $this->command->info("Created {$count} module content items");
    }
    
    /**
     * Create necessary directories for dummy content
     */
    private function createDirectories(): void
    {
        $modules = Module::all();
        
        foreach ($modules as $module) {
            $path = storage_path('app/public/module-contents/' . $module->id);
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }
        }
    }
    
    /**
     * Get an appropriate filename for the content type
     */
    private function getFileName(string $contentType): string
    {
        $extension = match ($contentType) {
            'pdf' => 'pdf',
            'video' => 'mp4',
            'audio' => 'mp3',
            'image' => 'jpg',
            default => 'txt',
        };
        
        return time() . '_' . Str::random(10) . '.' . $extension;
    }
    
    /**
     * Copy a placeholder dummy file for the given content type
     */
    private function copyDummyFile(string $contentType, string $destination): void
    {
        // Create an appropriate placeholder file
        $placeholder = storage_path('app/seeder-placeholders');
        
        if (!file_exists($placeholder)) {
            mkdir($placeholder, 0755, true);
        }
        
        $content = match ($contentType) {
            'pdf' => "%PDF-1.5\n1 0 obj\n<</Type/Catalog/Pages 2 0 R>>\nendobj\n2 0 obj\n<</Type/Pages/Count 1/Kids[3 0 R]>>\nendobj\n3 0 obj\n<</Type/Page/MediaBox[0 0 595 842]/Parent 2 0 R/Resources<<>>>>\nendobj\nxref\n0 4\n0000000000 65535 f \n0000000010 00000 n \n0000000053 00000 n \n0000000102 00000 n \n\ntrailer\n<</Size 4/Root 1 0 R>>\nstartxref\n178\n%%EOF",
            'video' => str_repeat(random_bytes(256), 10),
            'audio' => str_repeat(random_bytes(256), 10), 
            'image' => base64_decode('/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAMCAgICAgMCAgIDAwMDBAYEBAQEBAgGBgUGCQgKCgkICQkKDA8MCgsOCwkJDRENDg8QEBEQCgwSExIQEw8QEBD/wAALCAABAAEBAREA/8QAFAABAAAAAAAAAAAAAAAAAAAACf/EABQQAQAAAAAAAAAAAAAAAAAAAAD/2gAIAQEAAD8AVN//2Q=='),
            default => 'Placeholder content for seeder',
        };
        
        $tmpFile = $placeholder . '/' . $this->getFileName($contentType);
        file_put_contents($tmpFile, $content);
        
        // Copy to destination
        Storage::disk('local')->makeDirectory(dirname($destination));
        copy($tmpFile, storage_path('app/' . $destination));
    }
} 