<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\Subject;
use App\Models\ModuleContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ModuleContentController extends Controller
{
    /**
     * Display a listing of the module contents.
     */
    public function index(Subject $subject, Module $module)
    {
        $contents = $module->contents()->orderBy('sort_order')->get();
        $course = $subject->course;

        return view('admin.module-contents.index', compact(
            'contents',
            'course',
            'subject',
            'module'
        ));
    }

    /**
     * Show the form for creating a new module content.
     */
    public function create(Subject $subject, Module $module)
    {
        $course = $subject->course;
        return view('admin.module-contents.create', compact('course', 'subject', 'module'));
    }

    /**
     * Store a newly created module content in storage.
     */
    public function store(Request $request, Subject $subject, Module $module)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content_type' => 'required|in:pdf,video,audio,image,youtube,external_link',
            'is_required' => 'nullable|boolean',
            'file' => 'required_if:content_type,pdf,video,audio,image|file|max:100000',
            'external_url' => 'required_if:content_type,youtube,external_link|nullable|url|max:1000',
        ]);

        $content = new ModuleContent();
        $content->module_id = $module->id;
        $content->title = $validated['title'];
        $content->description = $validated['description'] ?? null;
        $content->content_type = $validated['content_type'];
        $content->is_required = $request->has('is_required');
        
        // Set the highest sort order
        $maxOrder = $module->contents()->max('sort_order') ?? 0;
        $content->sort_order = $maxOrder + 1;

        // Handle file uploads
        if (in_array($validated['content_type'], ['pdf', 'video', 'audio', 'image']) && $request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . Str::slug($validated['title']) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('module-contents/' . $module->id, $filename, 'public');
            $content->file_path = $path;
        }

        // Handle external URLs
        if (in_array($validated['content_type'], ['youtube', 'external_link']) && isset($validated['external_url'])) {
            $content->external_url = $validated['external_url'];
        }

        $content->save();

        return redirect()
            ->route('admin.courses.subjects.modules.contents.index', [$subject->slug, $module])
            ->with('success', 'Module content added successfully.');
    }

    /**
     * Show the form for editing the specified module content.
     */
    public function edit(Subject $subject, Module $module, ModuleContent $content)
    {
        $course = $subject->course;
        return view('admin.module-contents.edit', compact('course', 'subject', 'module', 'content'));
    }

    /**
     * Update the specified module content in storage.
     */
    public function update(Request $request, Subject $subject, Module $module, ModuleContent $content)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content_type' => 'required|in:pdf,video,audio,image,youtube,external_link',
            'is_required' => 'nullable|boolean',
            'file' => $request->content_type === 'pdf' || $request->content_type === 'video' || 
                      $request->content_type === 'audio' || $request->content_type === 'image' ? 
                      'nullable|file|max:100000' : 'nullable',
            'external_url' => $request->content_type === 'youtube' || $request->content_type === 'external_link' ? 
                             'required|url|max:1000' : 'nullable',
        ]);

        $content->title = $validated['title'];
        $content->description = $validated['description'] ?? null;
        $content->content_type = $validated['content_type'];
        $content->is_required = $request->has('is_required');

        // Handle file uploads if a new file is provided
        if (in_array($validated['content_type'], ['pdf', 'video', 'audio', 'image']) && $request->hasFile('file')) {
            // Delete old file if it exists
            if ($content->file_path) {
                Storage::disk('public')->delete($content->file_path);
            }
            
            $file = $request->file('file');
            $filename = time() . '_' . Str::slug($validated['title']) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('module-contents/' . $module->id, $filename, 'public');
            $content->file_path = $path;
        }

        // Handle external URLs
        if (in_array($validated['content_type'], ['youtube', 'external_link'])) {
            $content->external_url = $validated['external_url'];
            // Clear file path if switching to external content
            if ($content->file_path) {
                Storage::disk('public')->delete($content->file_path);
                $content->file_path = null;
            }
        }

        $content->save();

        return redirect()
            ->route('admin.courses.subjects.modules.contents.index', [$subject->slug, $module])
            ->with('success', 'Module content updated successfully.');
    }

    /**
     * Update the sort order of module contents.
     */
    public function updateOrder(Request $request, Subject $subject, Module $module)
    {
        $validated = $request->validate([
            'content_ids' => 'required|array',
            'content_ids.*' => 'exists:module_contents,id',
        ]);

        foreach ($validated['content_ids'] as $index => $id) {
            ModuleContent::where('id', $id)->update(['sort_order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Remove the specified module content from storage.
     */
    public function destroy(Subject $subject, Module $module, ModuleContent $content)
    {
        // Delete the file if it exists
        if ($content->file_path) {
            Storage::disk('public')->delete($content->file_path);
        }
        
        $content->delete();

        return redirect()
            ->route('admin.courses.subjects.modules.contents.index', [$subject->slug, $module])
            ->with('success', 'Module content deleted successfully.');
    }
}
