<?php

namespace App\Http\Controllers;

use App\Models\Title;
use Illuminate\Http\Request;

class TitleController extends Controller
{
    public function index()
    {
        $titles = Title::all();
        return view('admin.titles.index', compact('titles'));
    }

    public function create()
    {
        return view('admin.titles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:titles',
            'description' => 'nullable|string',
        ]);

        Title::create($validated);

        return redirect()
            ->route('admin.titles.index')
            ->with('success', 'Title created successfully.');
    }

    public function edit(Title $title)
    {
        return view('admin.titles.edit', compact('title'));
    }

    public function update(Request $request, Title $title)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:titles,name,' . $title->id,
            'description' => 'nullable|string',
        ]);

        $title->update($validated);

        return redirect()
            ->route('admin.titles.index')
            ->with('success', 'Title updated successfully.');
    }

    public function destroy(Title $title)
    {
        $title->delete();

        return redirect()
            ->route('admin.titles.index')
            ->with('success', 'Title deleted successfully.');
    }
}
