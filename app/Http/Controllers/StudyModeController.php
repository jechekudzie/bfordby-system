<?php

namespace App\Http\Controllers;

use App\Models\StudyMode;
use Illuminate\Http\Request;

class StudyModeController extends Controller
{
    /**
     * Display a listing of study modes.
     */
    public function index()
    {
        $studyModes = StudyMode::latest()->paginate(10);
        return view('admin.study-modes.index', compact('studyModes'));
    }

    /**
     * Show the form for creating a new study mode.
     */
    public function create()
    {
        return view('admin.study-modes.create');
    }

    /**
     * Store a newly created study mode.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:study_modes',
        ]);

        StudyMode::create($validated);

        return redirect()
            ->route('admin.study-modes.index')
            ->with('success', 'Study mode created successfully.');
    }

    /**
     * Show the form for editing the specified study mode.
     */
    public function edit(StudyMode $studyMode)
    {
        return view('admin.study-modes.edit', compact('studyMode'));
    }

    /**
     * Update the specified study mode.
     */
    public function update(Request $request, StudyMode $studyMode)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:study_modes,name,' . $studyMode->id,
        ]);

        $studyMode->update($validated);

        return redirect()
            ->route('admin.study-modes.index')
            ->with('success', 'Study mode updated successfully.');
    }

    /**
     * Remove the specified study mode.
     */
    public function destroy(StudyMode $studyMode)
    {
        try {
            $studyMode->delete();
            return redirect()
                ->route('admin.study-modes.index')
                ->with('success', 'Study mode deleted successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.study-modes.index')
                ->with('error', 'Cannot delete study mode as it is being used.');
        }
    }
}
