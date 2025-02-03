<?php

namespace App\Http\Controllers;

use App\Models\AddressType;
use Illuminate\Http\Request;

class AddressTypeController extends Controller
{
    public function index()
    {
        $addressTypes = AddressType::all();
        return view('admin.address-types.index', compact('addressTypes'));
    }

    public function create()
    {
        return view('admin.address-types.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:address_types',
            'description' => 'nullable|string',
        ]);

        AddressType::create($validated);

        return redirect()
            ->route('admin.address-types.index')
            ->with('success', 'Address type created successfully.');
    }

    public function edit(AddressType $addressType)
    {
        return view('admin.address-types.edit', compact('addressType'));
    }

    public function update(Request $request, AddressType $addressType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:address_types,name,' . $addressType->id,
            'description' => 'nullable|string',
        ]);

        $addressType->update($validated);

        return redirect()
            ->route('admin.address-types.index')
            ->with('success', 'Address type updated successfully.');
    }

    public function destroy(AddressType $addressType)
    {
        $addressType->delete();

        return redirect()
            ->route('admin.address-types.index')
            ->with('success', 'Address type deleted successfully.');
    }
}
