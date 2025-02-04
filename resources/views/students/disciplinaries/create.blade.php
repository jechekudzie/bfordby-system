@extends('layouts.admin')

@section('content')
<div class="container-fluid mt-4">
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">Add Disciplinary Record</h6>
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

            <form action="{{ route('students.disciplinaries.store', $student->slug) }}" method="POST">
                @csrf
                <div class="row g-3">
                    <!-- Incident Date -->
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="fas fa-calendar text-danger me-1"></i> Incident Date
                        </label>
                        <input type="text" 
                               name="incident_date" 
                               class="form-control datetimepicker @error('incident_date') is-invalid @enderror"
                               value="{{ old('incident_date') }}"
                               placeholder="Select incident date">
                        @error('incident_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Incident Type -->
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="fas fa-exclamation-triangle text-warning me-1"></i> Incident Type
                        </label>
                        <select name="incident_type" class="form-select @error('incident_type') is-invalid @enderror">
                            <option value="">Select Incident Type</option>
                            @foreach($incidentTypes as $value => $label)
                                <option value="{{ $value }}" {{ old('incident_type') == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('incident_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="col-12">
                        <label class="form-label">
                            <i class="fas fa-align-left text-primary me-1"></i> Description
                        </label>
                        <textarea name="description" 
                                  class="form-control @error('description') is-invalid @enderror" 
                                  rows="3"
                                  placeholder="Enter incident description">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Location -->
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="fas fa-map-marker-alt text-info me-1"></i> Location
                        </label>
                        <input type="text" 
                               name="location" 
                               class="form-control @error('location') is-invalid @enderror"
                               value="{{ old('location') }}"
                               placeholder="Enter incident location">
                        @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Reported By -->
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="fas fa-user text-success me-1"></i> Reported By
                        </label>
                        <input type="text" 
                               name="reported_by" 
                               class="form-control @error('reported_by') is-invalid @enderror"
                               value="{{ old('reported_by') }}"
                               placeholder="Enter reporter's name">
                        @error('reported_by')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Witnesses -->
                    <div class="col-12">
                        <label class="form-label">
                            <i class="fas fa-users text-secondary me-1"></i> Witnesses
                        </label>
                        <div id="witnesses-container">
                            <div class="input-group mb-2">
                                <input type="text" 
                                       name="witnesses[]" 
                                       class="form-control"
                                       placeholder="Enter witness name">
                                <button type="button" class="btn btn-outline-danger" onclick="removeField(this)">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="addWitness()">
                            <i class="fas fa-plus me-1"></i>Add Witness
                        </button>
                    </div>

                    <!-- Action Taken -->
                    <div class="col-12">
                        <label class="form-label">
                            <i class="fas fa-gavel text-primary me-1"></i> Action Taken
                        </label>
                        <textarea name="action_taken" 
                                  class="form-control @error('action_taken') is-invalid @enderror" 
                                  rows="3"
                                  placeholder="Enter action taken">{{ old('action_taken') }}</textarea>
                        @error('action_taken')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Sanction -->
                    <div class="col-md-12">
                        <label class="form-label">
                            <i class="fas fa-ban text-danger me-1"></i> Sanction
                        </label>
                        <select name="sanction" class="form-select @error('sanction') is-invalid @enderror">
                            <option value="">Select Sanction</option>
                            @foreach($sanctions as $value => $label)
                                <option value="{{ $value }}" {{ old('sanction') == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('sanction')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Sanction Dates -->
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="fas fa-calendar-plus text-primary me-1"></i> Start Date
                        </label>
                        <input type="text" 
                               name="start_date" 
                               class="form-control datetimepicker @error('start_date') is-invalid @enderror"
                               value="{{ old('start_date') }}"
                               placeholder="Select start date">
                        @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="fas fa-calendar-check text-success me-1"></i> End Date
                        </label>
                        <input type="text" 
                               name="end_date" 
                               class="form-control datetimepicker @error('end_date') is-invalid @enderror"
                               value="{{ old('end_date') }}"
                               placeholder="Select end date">
                        @error('end_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="col-md-12">
                        <label class="form-label">
                            <i class="fas fa-info-circle text-info me-1"></i> Status
                        </label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror">
                            <option value="">Select Status</option>
                            @foreach(['pending', 'active', 'resolved', 'appealed'] as $status)
                                <option value="{{ $status }}" {{ old('status') == $status ? 'selected' : '' }}>
                                    {{ Str::title($status) }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')
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
                                  placeholder="Enter additional notes">{{ old('notes') }}</textarea>
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
                                <i class="fas fa-save me-1"></i> Save Disciplinary Record
                            </button>
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

function addWitness() {
    const container = document.getElementById('witnesses-container');
    const newRow = `
        <div class="input-group mb-2">
            <input type="text" 
                   name="witnesses[]" 
                   class="form-control"
                   placeholder="Enter witness name">
            <button type="button" class="btn btn-outline-danger" onclick="removeField(this)">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', newRow);
}

function removeField(button) {
    const container = document.getElementById('witnesses-container');
    const fields = container.querySelectorAll('.input-group');
    if (fields.length > 1) {
        button.closest('.input-group').remove();
    }
}
</script>
@endpush
@endsection 