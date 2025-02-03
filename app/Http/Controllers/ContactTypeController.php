<?php

namespace App\Http\Controllers;

use App\Models\ContactType;
use Illuminate\Http\Request;

class ContactTypeController extends Controller
{
    public function index()
    {
        $contactTypes = ContactType::all();
        return view('admin.contact-types.index', compact('contactTypes'));
    }

    public function create()
    {
        return view('admin.contact-types.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:contact_types',
            'description' => 'nullable|string',
        ]);

        ContactType::create($validated);

        return redirect()
            ->route('admin.contact-types.index')
            ->with('success', 'Contact type created successfully.');
    }

    public function show(ContactType $contactType)
    {
        return view('admin.contact-types.show', compact('contactType'));
    }

    public function edit(ContactType $contactType)
    {
        return view('admin.contact-types.edit', compact('contactType'));
    }

    public function update(Request $request, ContactType $contactType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:contact_types,name,' . $contactType->id,
            'description' => 'nullable|string',
        ]);

        $contactType->update($validated);

        return redirect()
            ->route('admin.contact-types.index')
            ->with('success', 'Contact type updated successfully.');
    }

    public function destroy(ContactType $contactType)
    {
        $contactType->delete();

        return redirect()
            ->route('admin.contact-types.index')
            ->with('success', 'Contact type deleted successfully.');
    }
}
