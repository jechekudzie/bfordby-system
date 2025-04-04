<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LibraryController extends Controller
{
    /**
     * Display a listing of all available learning materials.
     */
    public function index()
    {
        // Get enrolled courses for the current student
        $student = Auth::user()->student;
        $enrollments = $student->enrollments()->with(['course.subjects.modules.contents'])->get();
        
        // Define content types with their icons and CSS classes
        $contentTypes = [
            'pdf' => [
                'name' => 'PDF Documents',
                'icon' => 'fas fa-file-pdf',
                'class' => 'text-danger'
            ],
            'video' => [
                'name' => 'Videos',
                'icon' => 'fas fa-file-video',
                'class' => 'text-primary'
            ],
            'audio' => [
                'name' => 'Audio Files',
                'icon' => 'fas fa-file-audio',
                'class' => 'text-info'
            ],
            'image' => [
                'name' => 'Images',
                'icon' => 'fas fa-file-image',
                'class' => 'text-success'
            ],
            'youtube' => [
                'name' => 'YouTube Videos',
                'icon' => 'fab fa-youtube',
                'class' => 'text-danger'
            ],
            'external_link' => [
                'name' => 'External Resources',
                'icon' => 'fas fa-external-link-alt',
                'class' => 'text-warning'
            ],
        ];
        
        // Count total content items by type across all modules
        $totalContentByType = [];
        foreach ($contentTypes as $type => $data) {
            $totalContentByType[$type] = 0;
        }
        
        $totalContent = 0;
        
        foreach ($enrollments as $enrollment) {
            foreach ($enrollment->course->subjects as $subject) {
                foreach ($subject->modules as $module) {
                    foreach ($module->contents as $content) {
                        $totalContentByType[$content->content_type]++;
                        $totalContent++;
                    }
                }
            }
        }
        
        return view('student.library.index', compact(
            'enrollments', 
            'contentTypes', 
            'totalContentByType', 
            'totalContent'
        ));
    }
    
    /**
     * Display the specified module's content.
     */
    public function showModule(Module $module)
    {
        $student = Auth::user()->student;
        
        // Check if student is enrolled in this module's course
        $isEnrolled = $student->enrollments()
            ->whereHas('course', function($query) use ($module) {
                $query->whereHas('subjects', function($q) use ($module) {
                    $q->where('id', $module->subject_id);
                });
            })
            ->exists();
            
        if (!$isEnrolled) {
            return redirect()->route('student.dashboard')
                ->with('error', 'You do not have access to this module.');
        }
        
        // Define content types with their icons and CSS classes
        $contentTypes = [
            'pdf' => [
                'name' => 'PDF Documents',
                'icon' => 'fas fa-file-pdf',
                'class' => 'text-danger'
            ],
            'video' => [
                'name' => 'Videos',
                'icon' => 'fas fa-file-video',
                'class' => 'text-primary'
            ],
            'audio' => [
                'name' => 'Audio Files',
                'icon' => 'fas fa-file-audio',
                'class' => 'text-info'
            ],
            'image' => [
                'name' => 'Images',
                'icon' => 'fas fa-file-image',
                'class' => 'text-success'
            ],
            'youtube' => [
                'name' => 'YouTube Videos',
                'icon' => 'fab fa-youtube',
                'class' => 'text-danger'
            ],
            'external_link' => [
                'name' => 'External Resources',
                'icon' => 'fas fa-external-link-alt',
                'class' => 'text-warning'
            ],
        ];
        
        // Group content by type
        $contentByType = [];
        foreach ($contentTypes as $type => $data) {
            $contentByType[$type] = $module->contents()->where('content_type', $type)->get();
        }
        
        return view('student.library.module', compact(
            'module', 
            'contentTypes', 
            'contentByType'
        ));
    }
} 