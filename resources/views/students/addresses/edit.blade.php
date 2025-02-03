@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">Edit Address</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('students.addresses.update', ['student' => $student->slug, 'address' => $address->slug]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label"><i class="fas fa-tag text-primary me-1"></i> Address Type</label>
                        <select name="address_type_id" class="form-select @error('address_type_id') is-invalid @enderror">
                            <option value="">Select Address Type</option>
                            @foreach($addressTypes as $type)
                            <option value="{{ $type->id }}" {{ old('address_type_id', $address->address_type_id) == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('address_type_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label"><i class="fas fa-map-marker-alt text-primary me-1"></i> Address Line 1</label>
                        <input type="text" name="address_line1" class="form-control @error('address_line1') is-invalid @enderror"
                            value="{{ old('address_line1', $address->address_line1) }}">
                        @error('address_line1')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label"><i class="fas fa-map-marker-alt text-primary me-1"></i> Address Line 2 (Optional)</label>
                        <input type="text" name="address_line2" class="form-control @error('address_line2') is-invalid @enderror"
                            value="{{ old('address_line2', $address->address_line2) }}">
                        @error('address_line2')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label"><i class="fas fa-city text-primary me-1"></i> City</label>
                        <input type="text" name="city" class="form-control @error('city') is-invalid @enderror"
                            value="{{ old('city', $address->city) }}">
                        @error('city')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label"><i class="fas fa-map text-primary me-1"></i> State</label>
                        <input type="text" name="state" class="form-control @error('state') is-invalid @enderror"
                            value="{{ old('state', $address->state) }}">
                        @error('state')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label"><i class="fas fa-mail-bulk text-primary me-1"></i> ZIP Code</label>
                        <input type="text" name="zip_code" class="form-control @error('zip_code') is-invalid @enderror"
                            value="{{ old('zip_code', $address->zip_code) }}">
                        @error('zip_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label"><i class="fas fa-globe text-primary me-1"></i> Country</label>
                        <select name="country_id" class="form-select @error('country_id') is-invalid @enderror">
                            <option value="">Select Country</option>
                            @foreach($countries as $country)
                            <option value="{{ $country->id }}" {{ old('country_id', $address->country_id) == $country->id ? 'selected' : '' }}>
                                {{ $country->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('country_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Primary Address Checkbox -->
                    <div class="col-12">
                        <div class="form-check">
                            <!-- Hidden input ensures unchecked checkbox submits '0' -->
                            <input type="hidden" name="is_primary" value="0">

                            <!-- Checkbox for primary address -->
                            <input type="checkbox" name="is_primary" class="form-check-input" id="isPrimary"
                                value="1" {{ old('is_primary', $address->is_primary) ? 'checked' : '' }}>
                            <label class="form-check-label" for="isPrimary">
                                <i class="fas fa-star text-primary me-1"></i> Set as Primary Address
                            </label>
                        </div>
                    </div>


                    <div class="col-12">
                        <hr>
                        <div class="d-flex justify-content-start gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Update Address
                            </button>
                            <a href="{{ route('students.show', $student->slug) }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection