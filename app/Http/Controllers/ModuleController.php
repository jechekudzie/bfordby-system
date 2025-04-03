<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ModuleController extends Controller
{
    public function index(Subject $subject)
    {

    
        $modules = $subject->modules()->latest()->paginate(10);

        return view('admin.modules.index', compact('subject', 'modules'));
    }

    public function create(Subject $subject)
    {
        return view('admin.modules.create', compact('subject'));
    }

    public function store(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $module = $subject->modules()->create($validated);

        return redirect()
            ->route('admin.modules.index')
            ->with('success', 'Module created successfully.');
    }

    public function edit(Subject $subject, Module $module)
    {
        return view('admin.modules.edit', compact('subject', 'module'));
    }

    public function update(Request $request, Subject $subject, Module $module)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $module->update($validated);

        return redirect()
            ->route('admin.modules.index')
            ->with('success', 'Module updated successfully.');
    }

    public function destroy(Subject $subject, Module $module)
    {
        $module->delete();

        return redirect()
            ->route('admin.modules.index')
            ->with('success', 'Module deleted successfully.');
    }
}
