<?php

namespace App\Http\Controllers;

use App\Models\QualificationLevel;
use Illuminate\Http\Request;

class QualificationLevelController extends Controller
{
    public function index()
    {
        $qualificationLevels = QualificationLevel::all();
        return view('admin.qualification-levels.index', compact('qualificationLevels'));
    }

    public function create()
    {
        return view('admin.qualification-levels.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:qualification_levels',
            'description' => 'nullable|string',
        ]);

        QualificationLevel::create($validated);

        return redirect()
            ->route('admin.qualification-levels.index')
            ->with('success', 'Qualification level created successfully.');
    }

    public function edit(QualificationLevel $qualificationLevel)
    {
        return view('admin.qualification-levels.edit', compact('qualificationLevel'));
    }

    public function update(Request $request, QualificationLevel $qualificationLevel)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:qualification_levels,name,' . $qualificationLevel->id,
            'description' => 'nullable|string',
        ]);

        $qualificationLevel->update($validated);

        return redirect()
            ->route('admin.qualification-levels.index')
            ->with('success', 'Qualification level updated successfully.');
    }

    public function destroy(QualificationLevel $qualificationLevel)
    {
        $qualificationLevel->delete();

        return redirect()
            ->route('admin.qualification-levels.index')
            ->with('success', 'Qualification level deleted successfully.');
    }
}
