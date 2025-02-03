<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Semester;
class SemesterController extends Controller
{
    //
    public function index()
    {
        $semesters = Semester::all();
        return view('admin.semesters.index', compact('semesters'));
    }

    public function create()
    {
        return view('admin.semesters.create');
    }   


    public function store(Request $request)
    {
        $semester = Semester::create($request->all());
        return redirect()->route('admin.semesters.index')->with('success', 'Semester created successfully');
    }

    public function edit(Semester $semester)
    {
        $semester = Semester::find($semester->id);
        return view('admin.semesters.edit', compact('semester'));
    }

    public function update(Request $request, Semester $semester)
    {
        $semester = Semester::find($semester->id);
        $semester->update($request->all());
        return redirect()->route('admin.semesters.index')->with('success', 'Semester updated successfully');
    }

    public function destroy(Semester $semester)
    {
        $semester = Semester::find($semester->id);
        $semester->delete();
        return redirect()->route('admin.semesters.index')->with('success', 'Semester deleted successfully');
    }

    public function show(Semester $semester )
    {
        $semester = Semester::find($semester->id);
        return view('admin.semesters.show', compact('semester'));
    }   
}
