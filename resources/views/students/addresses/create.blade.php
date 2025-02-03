@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">Add Address</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('students.addresses.store', $student->slug) }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="fas fa-tag text-primary me-1"></i> Address Type
                        </label>
                        <select name="address_type_id" class="form-select @error('address_type_id') is-invalid @enderror">
                            <option value="">Select Address Type</option>
                            @foreach($addressTypes as $type)
                                <option value="{{ $type->id }}" {{ old('address_type_id') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('address_type_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="fas fa-map-marker-alt text-primary me-1"></i> Address Line 1
                        </label>
                        <input type="text" name="address_line1" 
                               class="form-control @error('address_line1') is-invalid @enderror"
                               value="{{ old('address_line1') }}"
                               placeholder="Street address">
                        @error('address_line1')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="fas fa-map-marker-alt text-primary me-1"></i> Address Line 2 (Optional)
                        </label>
                        <input type="text" name="address_line2" 
                               class="form-control @error('address_line2') is-invalid @enderror"
                               value="{{ old('address_line2') }}"
                               placeholder="Apartment, suite, unit, etc.">
                        @error('address_line2')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="fas fa-city text-primary me-1"></i> City
                        </label>
                        <input type="text" name="city" 
                               class="form-control @error('city') is-invalid @enderror"
                               value="{{ old('city') }}">
                        @error('city')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">
                            <i class="fas fa-map text-primary me-1"></i> State/Province
                        </label>
                        <input type="text" name="state" 
                               class="form-control @error('state') is-invalid @enderror"
                               value="{{ old('state') }}">
                        @error('state')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">
                            <i class="fas fa-mail-bulk text-primary me-1"></i> ZIP/Postal Code
                        </label>
                        <input type="text" name="zip_code" 
                               class="form-control @error('zip_code') is-invalid @enderror"
                               value="{{ old('zip_code') }}">
                        @error('zip_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">
                            <i class="fas fa-globe text-primary me-1"></i> Country
                        </label>
                        <select name="country_id" class="form-select @error('country_id') is-invalid @enderror">
                            <option value="">Select Country</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>
                                    {{ $country->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('country_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <div class="form-check">
                            <input type="hidden" name="is_primary" value="0">
                            <input type="checkbox" 
                                   class="form-check-input" 
                                   id="isPrimary" 
                                   name="is_primary"
                                   value="1"
                                   {{ !$student->addresses->count() || old('is_primary') ? 'checked' : '' }}>
                            <label class="form-check-label" for="isPrimary">
                                <i class="fas fa-star text-primary me-1"></i> Set as Primary Address
                            </label>
                        </div>
                    </div>

                    <div class="col-12">
                        <hr>
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('students.show', $student) }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Save Address
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
