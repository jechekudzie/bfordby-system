@extends('layouts.admin')

@section('content')
<div class="container-fluid mt-4">
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">Edit Identification</h6>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible mb-4">
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <h6 class="mb-0">Please correct the following errors:</h6>
                    </div>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('students.identifications.update', ['student' => $student, 'identification' => $identification]) }}" 
                  method="POST" 
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row g-3">
                    <!-- ID Type -->
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="fas fa-id-card text-primary me-1"></i> ID Type
                        </label>
                        <select name="type" class="form-select @error('type') is-invalid @enderror">
                            <option value="">Select ID Type</option>
                            @foreach([
                                'national_id' => 'National ID',
                                'passport' => 'Passport',
                                'birth_certificate' => 'Birth Certificate',
                                'drivers_license' => 'Driver\'s License'
                            ] as $value => $label)
                                <option value="{{ $value }}" {{ old('type', $identification->type) == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- ID Number -->
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="fas fa-hashtag text-info me-1"></i> ID Number
                        </label>
                        <input type="text" 
                               name="number" 
                               class="form-control @error('number') is-invalid @enderror"
                               value="{{ old('number', $identification->number) }}"
                               placeholder="Enter ID number">
                        @error('number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Issuing Country -->
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="fas fa-globe text-success me-1"></i> Issuing Country
                        </label>
                        <select name="issuing_country_id" class="form-select @error('issuing_country_id') is-invalid @enderror">
                            <option value="">Select Country</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}" 
                                        {{ old('issuing_country_id', $identification->issuing_country_id) == $country->id ? 'selected' : '' }}>
                                    {{ $country->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('issuing_country_id')
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
                               value="{{ old('issuing_authority', $identification->issuing_authority) }}"
                               placeholder="Enter issuing authority">
                        @error('issuing_authority')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Issue Date -->
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="fas fa-calendar-plus text-success me-1"></i> Issue Date
                        </label>
                        <input type="text" 
                               name="issue_date" 
                               class="form-control datetimepicker @error('issue_date') is-invalid @enderror"
                               value="{{ old('issue_date', $identification->issue_date ? date('Y-m-d', strtotime($identification->issue_date)) : '') }}"
                               placeholder="Select issue date">
                        @error('issue_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Expiry Date -->
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="fas fa-calendar-times text-danger me-1"></i> Expiry Date
                        </label>
                        <input type="text" 
                               name="expiry_date" 
                               class="form-control datetimepicker @error('expiry_date') is-invalid @enderror"
                               value="{{ old('expiry_date', $identification->expiry_date ? date('Y-m-d', strtotime($identification->expiry_date)) : '') }}"
                               placeholder="Select expiry date">
                        @error('expiry_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="col-md-12">
                        <label class="form-label">
                            <i class="fas fa-info-circle text-warning me-1"></i> Status
                        </label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror">
                            <option value="">Select Status</option>
                            @foreach([
                                'active' => 'Active',
                                'expired' => 'Expired',
                                'pending_verification' => 'Pending Verification'
                            ] as $value => $label)
                                <option value="{{ $value }}" {{ old('status', $identification->status) == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Document Upload -->
                    <div class="col-md-12">
                        <label class="form-label">
                            <i class="fas fa-file-upload text-info me-1"></i> Document Upload
                        </label>
                        <input type="file" 
                               name="document" 
                               class="form-control @error('document') is-invalid @enderror"
                               accept=".pdf,.jpg,.jpeg,.png">
                        <small class="text-muted">Maximum file size: 2MB. Allowed types: PDF, JPG, JPEG, PNG</small>
                        @error('document')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if($identification->document)
                            <div class="mt-2">
                                <a href="{{ Storage::url($identification->document) }}" 
                                   class="btn btn-sm btn-outline-primary" 
                                   target="_blank">
                                    <i class="fas fa-file-alt me-1"></i>View Current Document
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Add this after the document upload field -->
                    <!-- <div class="col-md-12">
                        @if($identification->document)
                            <div class="card">
                                <div class="card-header bg-light">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">Current Document</h6>
                                        <a href="{{ Storage::url($identification->document) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           target="_blank">
                                            <i class="fas fa-external-link-alt me-1"></i>Open in New Tab
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    @php
                                        $extension = pathinfo($identification->document, PATHINFO_EXTENSION);
                                        $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png']);
                                    @endphp

                                    @if($isImage)
                                        <img src="{{ Storage::url($identification->document) }}" 
                                             class="img-fluid rounded" 
                                             alt="Document Preview"
                                             style="max-height: 400px; width: auto; margin: 0 auto; display: block;">
                                    @else
                                        <div class="ratio ratio-16x9">
                                            <embed src="{{ Storage::url($identification->document) }}" 
                                                   type="application/pdf" 
                                                   width="100%" 
                                                   height="400px">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
 -->
                    <!-- Notes -->
                    <div class="col-12">
                        <label class="form-label">
                            <i class="fas fa-sticky-note text-warning me-1"></i> Notes
                        </label>
                        <textarea name="notes" 
                                  class="form-control @error('notes') is-invalid @enderror" 
                                  rows="3"
                                  placeholder="Enter additional notes">{{ old('notes', $identification->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <hr>
                        <div class="d-flex justify-content-start gap-2">
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Update Identification
                            </button>
                            <a href="{{ route('students.show', $student) }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize datetimepicker
    $('.datetimepicker').datetimepicker({
        format: 'YYYY-MM-DD',
        useCurrent: false
    });
});
</script>
@endpush
@endsection 