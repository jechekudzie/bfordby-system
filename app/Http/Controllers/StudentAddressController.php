<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Address;
use App\Models\AddressType;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StudentAddressController extends Controller
{
    public function index()
    {
        return view('students.addresses.index');
    }

    public function create(Student $student)
    {
        $addressTypes = AddressType::all();
        $countries = Country::orderBy('name')->get();
        
        return view('students.addresses.create', compact('student', 'addressTypes', 'countries'));
    }

    public function store(Request $request, Student $student)
    {
        $validated = $request->validate([
            'address_type_id' => 'required|exists:address_types,id',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:255',
            'country_id' => 'nullable|exists:countries,id',
        ]);

        // Set is_primary only if checkbox is checked or it's the first address
        $isPrimary = $student->addresses->count() === 0 || $request->has('is_primary');

        // Only update other addresses if this one is being set as primary
        if ($isPrimary) {
            $student->addresses()->update(['is_primary' => false]);
        }

        // Create the address
        $address = new Address($validated);
        $address->student_id = $student->id;
        $address->is_primary = $isPrimary;
        $address->slug = Str::slug($student->name . '-' . $validated['address_line1'] . '-' . uniqid());
        $address->save();

        return redirect()
            ->route('students.show', $student)
            ->with('success', 'Address added successfully');
    }

    public function edit(Student $student, Address $address)
    {
        $addressTypes = AddressType::all();
        $countries = Country::orderBy('name')->get();
        
        return view('students.addresses.edit', compact('student', 'address', 'addressTypes', 'countries'));
    }

    public function update(Request $request, Student $student, Address $address)
    {
        $validated = $request->validate([
            'address_type_id' => 'required|exists:address_types,id',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:255',
            'country_id' => 'nullable|exists:countries,id',
        ]);

        $isPrimary = $request->has('is_primary');

        // If setting as primary, update other addresses
        if ($isPrimary) {
            $student->addresses()
                   ->where('id', '!=', $address->id)
                   ->update(['is_primary' => false]);
        }
        // If unsetting primary on the only address, keep it primary
        elseif ($student->addresses()->count() === 1) {
            $isPrimary = true;
        }
        // If unsetting primary and this was primary, make another address primary
        elseif ($address->is_primary) {
            $student->addresses()
                   ->where('id', '!=', $address->id)
                   ->first()
                   ->update(['is_primary' => true]);
        }

        // Update the address
        $address->fill($validated);
        $address->is_primary = $isPrimary;
        $address->save();

        return redirect()
            ->route('students.show', $student)
            ->with('success', 'Address updated successfully');
    }

    public function destroy(Student $student, Address $address)
    {
        if ($address->is_primary && $student->addresses()->count() > 1) {
            // Make another address primary
            $student->addresses()
                   ->where('id', '!=', $address->id)
                   ->first()
                   ->update(['is_primary' => true]);
        }

        $address->delete();

        return redirect()
            ->route('students.show', $student)
            ->with('success', 'Address deleted successfully');
    }
}
