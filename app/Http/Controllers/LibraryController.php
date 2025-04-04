<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Subject;
use App\Models\Module;
use App\Models\ModuleContent;
use Illuminate\Http\Request;

class LibraryController extends Controller
{
    /**
     * Display the library index page with all learning materials.
     */
    public function index()
    {
        // Get all courses with subjects, modules, and content
        $courses = Course::with([
            'subjects',
            'subjects.modules',
            'subjects.modules.contents' => function ($query) {
                $query->orderBy('sort_order');
            }
        ])->get();

        // Group content by type for each module
        $subjects = Subject::with(['modules.contents'])->get();
        
        // Define possible content types and their details
        $contentTypes = [
            'pdf' => [
                'name' => 'PDF Documents',
                'icon' => 'fas fa-file-pdf',
                'class' => 'text-danger'
            ],
            'video' => [
                'name' => 'Video Materials',
                'icon' => 'fas fa-file-video',
                'class' => 'text-primary'
            ],
            'audio' => [
                'name' => 'Audio Materials',
                'icon' => 'fas fa-file-audio',
                'class' => 'text-success'
            ],
            'image' => [
                'name' => 'Image Resources',
                'icon' => 'fas fa-file-image',
                'class' => 'text-warning'
            ],
            'youtube' => [
                'name' => 'YouTube Videos',
                'icon' => 'fab fa-youtube',
                'class' => 'text-danger'
            ],
            'external_link' => [
                'name' => 'External Resources',
                'icon' => 'fas fa-external-link-alt',
                'class' => 'text-info'
            ],
        ];

        return view('admin.library.index', compact('courses', 'subjects', 'contentTypes'));
    }
    
    /**
     * Display content for a specific module.
     */
    public function showModule(Module $module)
    {
        $module->load(['contents' => function ($query) {
            $query->orderBy('sort_order');
        }]);
        
        // Group content by type
        $contentByType = $module->contents->groupBy('content_type');
        
        // Define content types and their details (same as in index method)
        $contentTypes = [
            'pdf' => [
                'name' => 'PDF Documents',
                'icon' => 'fas fa-file-pdf',
                'class' => 'text-danger'
            ],
            'video' => [
                'name' => 'Video Materials',
                'icon' => 'fas fa-file-video',
                'class' => 'text-primary'
            ],
            'audio' => [
                'name' => 'Audio Materials',
                'icon' => 'fas fa-file-audio',
                'class' => 'text-success'
            ],
            'image' => [
                'name' => 'Image Resources',
                'icon' => 'fas fa-file-image',
                'class' => 'text-warning'
            ],
            'youtube' => [
                'name' => 'YouTube Videos',
                'icon' => 'fab fa-youtube',
                'class' => 'text-danger'
            ],
            'external_link' => [
                'name' => 'External Resources',
                'icon' => 'fas fa-external-link-alt',
                'class' => 'text-info'
            ],
        ];
        
        return view('admin.library.module', compact('module', 'contentByType', 'contentTypes'));
    }
} 