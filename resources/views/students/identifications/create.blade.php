@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">Add Identification</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('students.identifications.store', $student) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <!-- Type -->
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="fas fa-id-card text-primary me-1"></i> ID Type
                        </label>
                        <select name="type" class="form-select @error('type') is-invalid @enderror">
                            <option value="">Select ID Type</option>
                            @foreach($identificationTypes as $value => $label)
                                <option value="{{ $value }}" {{ old('type') == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Number -->
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="fas fa-hashtag text-primary me-1"></i> ID Number
                        </label>
                        <input type="text" 
                               name="number" 
                               class="form-control @error('number') is-invalid @enderror" 
                               value="{{ old('number') }}"
                               placeholder="Enter ID number">
                        @error('number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Issue Date -->
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="fas fa-calendar-plus text-primary me-1"></i> Issue Date
                        </label>
                        <input type="date" 
                               name="issue_date" 
                               class="form-control datetimepicker @error('issue_date') is-invalid @enderror" 
                               value="{{ old('issue_date') }}">
                        @error('issue_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Expiry Date -->
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="fas fa-calendar-times text-primary me-1"></i> Expiry Date
                        </label>
                        <input type="date" 
                               name="expiry_date" 
                               class="form-control datetimepicker @error('expiry_date') is-invalid @enderror" 
                               value="{{ old('expiry_date') }}">
                        @error('expiry_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Issuing Authority -->
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="fas fa-building text-primary me-1"></i> Issuing Authority
                        </label>
                        <input type="text" 
                               name="issuing_authority" 
                               class="form-control @error('issuing_authority') is-invalid @enderror" 
                               value="{{ old('issuing_authority') }}"
                               placeholder="Enter issuing authority">
                        @error('issuing_authority')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Issuing Country -->
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="fas fa-globe text-primary me-1"></i> Issuing Country
                        </label>
                        <select name="issuing_country_id" class="form-select @error('issuing_country_id') is-invalid @enderror">
                            <option value="">Select Country</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}" {{ old('issuing_country_id') == $country->id ? 'selected' : '' }}>
                                    {{ $country->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('issuing_country_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="fas fa-info-circle text-primary me-1"></i> Status
                        </label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror">
                            <option value="">Select Status</option>
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="expired" {{ old('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                            <option value="pending_verification" {{ old('status', 'pending_verification') == 'pending_verification' ? 'selected' : '' }}>
                                Pending Verification
                            </option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Document Upload -->
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="fas fa-file-upload text-primary me-1"></i> Document
                        </label>
                        <input type="file" 
                               name="document" 
                               class="form-control @error('document') is-invalid @enderror"
                               accept=".pdf,.jpg,.jpeg,.png">
                        <div class="form-text">Max size: 2MB. Allowed types: PDF, JPG, PNG</div>
                        @error('document')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div class="col-12">
                        <label class="form-label">
                            <i class="fas fa-sticky-note text-primary me-1"></i> Notes
                        </label>
                        <textarea name="notes" 
                                  class="form-control @error('notes') is-invalid @enderror" 
                                  rows="3"
                                  placeholder="Enter any additional notes">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <hr>
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('students.show', $student) }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Save Identification
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 