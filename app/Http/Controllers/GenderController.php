<?php

namespace App\Http\Controllers;

use App\Models\Gender;
use Illuminate\Http\Request;

class GenderController extends Controller
{
    public function index()
    {
        $genders = Gender::all();
        return view('admin.genders.index', compact('genders'));
    }

    public function create()
    {
        return view('admin.genders.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:genders',
            'description' => 'nullable|string',
        ]);

        Gender::create($validated);

        return redirect()
            ->route('admin.genders.index')
            ->with('success', 'Gender created successfully.');
    }

    public function edit(Gender $gender)
    {
        return view('admin.genders.edit', compact('gender'));
    }

    public function update(Request $request, Gender $gender)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:genders,name,' . $gender->id,
            'description' => 'nullable|string',
        ]);

        $gender->update($validated);

        return redirect()
            ->route('admin.genders.index')
            ->with('success', 'Gender updated successfully.');
    }

    public function destroy(Gender $gender)
    {
        $gender->delete();

        return redirect()
            ->route('admin.genders.index')
            ->with('success', 'Gender deleted successfully.');
    }
}
