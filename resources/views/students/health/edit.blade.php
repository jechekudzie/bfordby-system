@extends('layouts.admin')

@section('content')
<div class="container-fluid mt-4">
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">Edit Health Information</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('students.health.update', ['student' => $student, 'health' => $health]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <!-- Blood Group -->
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="fas fa-tint text-danger me-1"></i> Blood Group
                        </label>
                        <select name="blood_group" class="form-select @error('blood_group') is-invalid @enderror">
                            <option value="">Select Blood Group</option>
                            @foreach($bloodGroups as $value => $label)
                                <option value="{{ $value }}" {{ old('blood_group', $health->blood_group) == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('blood_group')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Last Checkup Date -->
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="fas fa-calendar-check text-primary me-1"></i> Last Checkup Date
                        </label>
                        <input type="text" 
                               name="last_checkup_date" 
                               class="form-control datetimepicker @error('last_checkup_date') is-invalid @enderror"
                               value="{{ old('last_checkup_date', $health->last_checkup_date ? date('Y-m-d', strtotime($health->last_checkup_date)) : '') }}"
                               placeholder="Select date">
                        @error('last_checkup_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Dynamic Allergies -->
                    <div class="col-12">
                        <label class="form-label">
                            <i class="fas fa-allergies text-warning me-1"></i> Allergies
                        </label>
                        <div class="dynamic-fields" id="allergies-container">
                            @if(old('allergies', $health->allergies))
                                @foreach(is_array($health->allergies) ? $health->allergies : json_decode($health->allergies, true) as $allergy)
                                    <div class="input-group mb-2">
                                        <input type="text" 
                                               name="allergies[]" 
                                               class="form-control"
                                               value="{{ $allergy }}"
                                               placeholder="Enter allergy">
                                        <button type="button" class="btn btn-outline-danger" onclick="removeField(this)">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                @endforeach
                            @else
                                <div class="input-group mb-2">
                                    <input type="text" 
                                           name="allergies[]" 
                                           class="form-control"
                                           placeholder="Enter allergy">
                                    <button type="button" class="btn btn-outline-danger" onclick="removeField(this)">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="addField('allergies-container', 'Enter allergy')">
                            <i class="fas fa-plus me-1"></i>Add Allergy
                        </button>
                    </div>

                    <!-- Dynamic Medical Conditions -->
                    <div class="col-12">
                        <label class="form-label">
                            <i class="fas fa-notes-medical text-danger me-1"></i> Medical Conditions
                        </label>
                        <div class="dynamic-fields" id="medical-conditions-container">
                            @if(old('medical_conditions', $health->medical_conditions))
                                @foreach(is_array($health->medical_conditions) ? $health->medical_conditions : json_decode($health->medical_conditions, true) as $condition)
                                    <div class="input-group mb-2">
                                        <input type="text" 
                                               name="medical_conditions[]" 
                                               class="form-control"
                                               value="{{ $condition }}"
                                               placeholder="Enter medical condition">
                                        <button type="button" class="btn btn-outline-danger" onclick="removeField(this)">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                @endforeach
                            @else
                                <div class="input-group mb-2">
                                    <input type="text" 
                                           name="medical_conditions[]" 
                                           class="form-control"
                                           placeholder="Enter medical condition">
                                    <button type="button" class="btn btn-outline-danger" onclick="removeField(this)">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="addField('medical-conditions-container', 'Enter medical condition')">
                            <i class="fas fa-plus me-1"></i>Add Medical Condition
                        </button>
                    </div>

                    <!-- Dynamic Medications -->
                    <div class="col-12">
                        <label class="form-label">
                            <i class="fas fa-pills text-info me-1"></i> Medications
                        </label>
                        <div class="dynamic-fields" id="medications-container">
                            @if(old('medications', $health->medications))
                                @foreach(is_array($health->medications) ? $health->medications : json_decode($health->medications, true) as $medication)
                                    <div class="input-group mb-2">
                                        <input type="text" 
                                               name="medications[]" 
                                               class="form-control"
                                               value="{{ $medication }}"
                                               placeholder="Enter medication">
                                        <button type="button" class="btn btn-outline-danger" onclick="removeField(this)">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                @endforeach
                            @else
                                <div class="input-group mb-2">
                                    <input type="text" 
                                           name="medications[]" 
                                           class="form-control"
                                           placeholder="Enter medication">
                                    <button type="button" class="btn btn-outline-danger" onclick="removeField(this)">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="addField('medications-container', 'Enter medication')">
                            <i class="fas fa-plus me-1"></i>Add Medication
                        </button>
                    </div>

                    <hr>

                    <!-- Emergency Contact Information -->
                    <div class="col-md-4">
                        <label class="form-label">
                            <i class="fas fa-user-shield text-primary me-1"></i> Emergency Contact Name
                        </label>
                        <input type="text" 
                               name="emergency_contact_name" 
                               class="form-control @error('emergency_contact_name') is-invalid @enderror"
                               value="{{ old('emergency_contact_name', $health->emergency_contact_name) }}"
                               placeholder="Enter contact name">
                        @error('emergency_contact_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">
                            <i class="fas fa-phone text-primary me-1"></i> Emergency Contact Phone
                        </label>
                        <input type="text" 
                               name="emergency_contact_phone" 
                               class="form-control @error('emergency_contact_phone') is-invalid @enderror"
                               value="{{ old('emergency_contact_phone', $health->emergency_contact_phone) }}"
                               placeholder="Enter contact phone">
                        @error('emergency_contact_phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">
                            <i class="fas fa-user-friends text-primary me-1"></i> Relationship
                        </label>
                        <input type="text" 
                               name="emergency_contact_relationship" 
                               class="form-control @error('emergency_contact_relationship') is-invalid @enderror"
                               value="{{ old('emergency_contact_relationship', $health->emergency_contact_relationship) }}"
                               placeholder="Enter relationship">
                        @error('emergency_contact_relationship')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div class="col-12">
                        <label class="form-label">
                            <i class="fas fa-sticky-note text-warning me-1"></i> Notes
                        </label>
                        <textarea name="notes" 
                                  class="form-control @error('notes') is-invalid @enderror" 
                                  rows="3"
                                  placeholder="Enter any additional notes">{{ old('notes', $health->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <hr>
                        <div class="d-flex justify-content-start gap-2">
                           
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Update Health Information
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

// Function to add new field
function addField(containerId, placeholder) {
    const container = document.getElementById(containerId);
    const newRow = `
        <div class="input-group mb-2">
            <input type="text" 
                   name="${containerId.replace('-container', '')}[]" 
                   class="form-control"
                   placeholder="${placeholder}">
            <button type="button" class="btn btn-outline-danger" onclick="removeField(this)">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', newRow);
}

// Function to remove field
function removeField(button) {
    const container = button.closest('.dynamic-fields');
    const fields = container.querySelectorAll('.input-group');
    if (fields.length > 1) {
        button.closest('.input-group').remove();
    }
}
</script>
@endpush
@endsection 