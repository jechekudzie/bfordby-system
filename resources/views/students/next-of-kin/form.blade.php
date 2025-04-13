@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header">
        <h6 class="mb-0">
            <i class="fas fa-user-plus text-primary me-2"></i>
            {{ isset($nextOfKin) ? 'Edit Next of Kin' : 'Add Next of Kin' }}
        </h6>
    </div>
    <div class="card-body">
        <form action="{{ isset($nextOfKin) ? route('students.next-of-kin.update', ['student' => $student, 'next_of_kin' => $nextOfKin]) : route('students.next-of-kin.store', $student) }}"
            method="POST">
            @csrf
            @if(isset($nextOfKin))
                @method('PUT')
            @endif

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name"
                        name="first_name" value="{{ old('first_name', $nextOfKin->first_name ?? '') }}" required>
                    @error('first_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name"
                        name="last_name" value="{{ old('last_name', $nextOfKin->last_name ?? '') }}" required>
                    @error('last_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="relationship" class="form-label">Relationship <span class="text-danger">*</span></label>
                    <select class="form-select @error('relationship') is-invalid @enderror" id="relationship" name="relationship" required>
                        <option value="">Select Relationship</option>
                        <option value="Parent" {{ old('relationship', $nextOfKin->relationship ?? '') == 'Parent' ? 'selected' : '' }}>Parent</option>
                        <option value="Guardian" {{ old('relationship', $nextOfKin->relationship ?? '') == 'Guardian' ? 'selected' : '' }}>Guardian</option>
                        <option value="Sibling" {{ old('relationship', $nextOfKin->relationship ?? '') == 'Sibling' ? 'selected' : '' }}>Sibling</option>
                        <option value="Spouse" {{ old('relationship', $nextOfKin->relationship ?? '') == 'Spouse' ? 'selected' : '' }}>Spouse</option>
                        <option value="Other" {{ old('relationship', $nextOfKin->relationship ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('relationship')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="phone_number" class="form-label">Phone Number</label>
                    <input type="tel" class="form-control @error('phone_number') is-invalid @enderror" id="phone_number"
                        name="phone_number" value="{{ old('phone_number', $nextOfKin->phone_number ?? '') }}">
                    @error('phone_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                    name="email" value="{{ old('email', $nextOfKin->email ?? '') }}">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="address_line1" class="form-label">Address Line 1</label>
                <input type="text" class="form-control @error('address_line1') is-invalid @enderror" id="address_line1"
                    name="address_line1" value="{{ old('address_line1', $nextOfKin->address_line1 ?? '') }}">
                @error('address_line1')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="address_line2" class="form-label">Address Line 2</label>
                <input type="text" class="form-control @error('address_line2') is-invalid @enderror" id="address_line2"
                    name="address_line2" value="{{ old('address_line2', $nextOfKin->address_line2 ?? '') }}">
                @error('address_line2')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="city" class="form-label">City</label>
                    <input type="text" class="form-control @error('city') is-invalid @enderror" id="city"
                        name="city" value="{{ old('city', $nextOfKin->city ?? '') }}">
                    @error('city')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="state" class="form-label">State/Province</label>
                    <input type="text" class="form-control @error('state') is-invalid @enderror" id="state"
                        name="state" value="{{ old('state', $nextOfKin->state ?? '') }}">
                    @error('state')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="zip_code" class="form-label">ZIP/Postal Code</label>
                    <input type="text" class="form-control @error('zip_code') is-invalid @enderror" id="zip_code"
                        name="zip_code" value="{{ old('zip_code', $nextOfKin->zip_code ?? '') }}">
                    @error('zip_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="country_id" class="form-label">Country</label>
                    <select class="form-select @error('country_id') is-invalid @enderror" id="country_id" name="country_id">
                        <option value="">Select Country</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}" {{ old('country_id', $nextOfKin->country_id ?? '') == $country->id ? 'selected' : '' }}>
                                {{ $country->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('country_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input @error('is_emergency_contact') is-invalid @enderror" id="is_emergency_contact"
                        name="is_emergency_contact" value="1" {{ old('is_emergency_contact', $nextOfKin->is_emergency_contact ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_emergency_contact">Emergency Contact</label>
                    @error('is_emergency_contact')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="notes" class="form-label">Additional Notes</label>
                <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes', $nextOfKin->notes ?? '') }}</textarea>
                @error('notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('students.next-of-kin.index', $student) }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i>
                    {{ isset($nextOfKin) ? 'Update' : 'Save' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 