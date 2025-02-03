<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index()
    {
        $countries = Country::all();
        return view('admin.countries.index', compact('countries'));
    }

    public function create()
    {
        return view('admin.countries.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:countries',
            'code' => 'required|string|size:2|unique:countries',
            'phone_code' => 'required|string|max:10|unique:countries',
        ]);

        Country::create($validated);

        return redirect()
            ->route('admin.countries.index')
            ->with('success', 'Country created successfully.');
    }

    public function edit(Country $country)
    {
        return view('admin.countries.edit', compact('country'));
    }

    public function update(Request $request, Country $country)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:countries,name,' . $country->id,
            'code' => 'required|string|size:2|unique:countries,code,' . $country->id,
            'phone_code' => 'required|string|max:10|unique:countries,phone_code,' . $country->id,
        ]);

        $country->update($validated);

        return redirect()
            ->route('admin.countries.index')
            ->with('success', 'Country updated successfully.');
    }

    public function destroy(Country $country)
    {
        $country->delete();

        return redirect()
            ->route('admin.countries.index')
            ->with('success', 'Country deleted successfully.');
    }
}
