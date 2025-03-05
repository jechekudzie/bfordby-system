@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">
                    <i class="fas fa-edit text-primary me-2"></i>Edit Assessment Allocation
                </h5>
                <div class="text-muted">
                    Assessment: <span class="fw-bold">{{ $assessment->name }}</span>
                    <span class="badge bg-{{ 
                        $assessment->type === 'exam' ? 'danger' : 
                        ($assessment->type === 'test' ? 'warning' : 
                        ($assessment->type === 'practical' ? 'info' : 'primary')) 
                    }} ms-2">{{ ucfirst($assessment->type) }}</span>
                </div>
            </div>
            <a href="{{ route('admin.modules.assessments.allocations.index', [$module, $assessment]) }}" 
               class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i>Back to Allocations
            </a>
        </div>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.modules.assessments.allocations.update', [$module, $assessment, $allocation]) }}" 
              method="POST" 
              enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="enrollment_code_id" class="form-label">Enrollment Code</label>
                    <select class="form-select @error('enrollment_code_id') is-invalid @enderror" 
                            id="enrollment_code_id" 
                            name="enrollment_code_id" 
                            required>
                        <option value="">Select Enrollment Code</option>
                        @foreach($enrollmentCodes as $code)
                            <option value="{{ $code->id }}" {{ old('enrollment_code_id', $allocation->enrollment_code_id) == $code->id ? 'selected' : '' }}>
                                {{ $code->base_code }}
                            </option>
                        @endforeach
                    </select>
                    @error('enrollment_code_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="semester_id" class="form-label">Semester</label>
                    <select class="form-select @error('semester_id') is-invalid @enderror" 
                            id="semester_id" 
                            name="semester_id" 
                            required>
                        <option value="">Select Semester</option>
                        @foreach($semesters as $semester)
                            <option value="{{ $semester->id }}" {{ old('semester_id', $allocation->semester_id) == $semester->id ? 'selected' : '' }}>
                                {{ $semester->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('semester_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="submission_type" class="form-label">Submission Type</label>
                    <select class="form-select @error('submission_type') is-invalid @enderror" 
                            id="submission_type" 
                            name="submission_type" 
                            required>
                        <option value="">Select Submission Type</option>
                        <option value="upload" {{ old('submission_type', $allocation->submission_type) == 'upload' ? 'selected' : '' }}>Upload</option>
                        <option value="online" {{ old('submission_type', $allocation->submission_type) == 'online' ? 'selected' : '' }}>Online</option>
                        <option value="in-class" {{ old('submission_type', $allocation->submission_type) == 'in-class' ? 'selected' : '' }}>In-Class</option>
                        <option value="group" {{ old('submission_type', $allocation->submission_type) == 'group' ? 'selected' : '' }}>Group</option>
                    </select>
                    @error('submission_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Timed Assessment Options -->
                <div id="timedOptions" class="col-12 mb-3" style="display: none;">
                    <div class="card bg-light border">
                        <div class="card-body">
                            <h6 class="card-subtitle mb-3">Timed Assessment Options</h6>
                            
                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" 
                                           class="form-check-input @error('is_timed') is-invalid @enderror" 
                                           id="is_timed" 
                                           name="is_timed" 
                                           value="1"
                                           {{ old('is_timed', $allocation->is_timed) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_timed">
                                        Enable Timed Assessment
                                    </label>
                                </div>
                                @error('is_timed')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div id="durationField" class="mb-0" style="display: none;">
                                <label for="duration_minutes" class="form-label">Duration (minutes)</label>
                                <input type="number" 
                                       class="form-control @error('duration_minutes') is-invalid @enderror" 
                                       id="duration_minutes" 
                                       name="duration_minutes"
                                       min="1"
                                       value="{{ old('duration_minutes', $allocation->duration_minutes) }}"
                                       placeholder="Enter duration in minutes">
                                @error('duration_minutes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select @error('status') is-invalid @enderror" 
                            id="status" 
                            name="status" 
                            required>
                        <option value="pending" {{ old('status', $allocation->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="open" {{ old('status', $allocation->status) == 'open' ? 'selected' : '' }}>Open</option>
                        <option value="closed" {{ old('status', $allocation->status) == 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="due_date" class="form-label">Due Date</label>
                    <input type="text" 
                           class="form-control datetimepicker @error('due_date') is-invalid @enderror" 
                           id="due_date" 
                           name="due_date" 
                           value="{{ old('due_date', $allocation->due_date ? date('Y-m-d H:i:s', strtotime($allocation->due_date)) : '') }}">
                    <div class="form-text">Leave empty if no due date is required</div>
                    @error('due_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 mb-3">
                    <label for="content" class="form-label">Assessment Content</label>
                    <textarea class="form-control @error('content') is-invalid @enderror" 
                              id="content" 
                              name="content" 
                              rows="5"
                              placeholder="Enter assessment instructions or content here...">{{ old('content', $allocation->content) }}</textarea>
                    <div class="form-text">Provide detailed instructions or content for the assessment</div>
                    @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 mb-3">
                    <label for="file_path" class="form-label">Assessment File</label>
                    @if($allocation->file_path)
                        <div class="mb-2">
                            <a href="{{ asset('storage/' . $allocation->file_path) }}" 
                               class="btn btn-sm btn-outline-primary" 
                               target="_blank">
                                <i class="fas fa-file-download me-1"></i>Current File
                            </a>
                        </div>
                    @endif
                    <input type="file" 
                           class="form-control @error('file_path') is-invalid @enderror" 
                           id="file_path" 
                           name="file_path">
                    <div class="form-text">Upload any supporting documents (PDF, DOC, etc.). Leave empty to keep existing file.</div>
                    @error('file_path')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Update Allocation
                </button>
                <a href="{{ route('admin.modules.assessments.allocations.index', [$module, $assessment]) }}" 
                   class="btn btn-outline-secondary ms-2">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const submissionType = document.getElementById('submission_type');
    const dueDateField = document.getElementById('due_date');
    const timedOptions = document.getElementById('timedOptions');
    const isTimedCheckbox = document.getElementById('is_timed');
    const durationField = document.getElementById('durationField');

    // Handle submission type change
    submissionType.addEventListener('change', function() {
        if (this.value === 'in-class') {
            dueDateField.setAttribute('required', 'required');
        } else {
            dueDateField.removeAttribute('required');
        }

        // Show/hide timed options for online submission
        timedOptions.style.display = this.value === 'online' ? 'block' : 'none';
        if (this.value !== 'online') {
            isTimedCheckbox.checked = false;
            durationField.style.display = 'none';
        }
    });

    // Handle is_timed checkbox change
    isTimedCheckbox.addEventListener('change', function() {
        durationField.style.display = this.checked ? 'block' : 'none';
        const durationInput = document.getElementById('duration_minutes');
        if (this.checked) {
            durationInput.setAttribute('required', 'required');
        } else {
            durationInput.removeAttribute('required');
            durationInput.value = '';
        }
    });

    // Initialize state based on current values
    if (submissionType.value === 'online') {
        timedOptions.style.display = 'block';
        if (isTimedCheckbox.checked) {
            durationField.style.display = 'block';
        }
    }
});
</script>
@endpush
@endsection 