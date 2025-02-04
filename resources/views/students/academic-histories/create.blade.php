@extends('layouts.admin')

@section('content')
<div class="container-fluid mt-4">
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">Add Academic History</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('students.academic-histories.store', $student) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <!-- Institution Name -->
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="fas fa-university text-primary me-1"></i> Institution Name
                        </label>
                        <input type="text" 
                               name="institution_name" 
                               class="form-control @error('institution_name') is-invalid @enderror"
                               value="{{ old('institution_name') }}"
                               placeholder="Enter institution name">
                        @error('institution_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Qualification Level -->
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="fas fa-graduation-cap text-success me-1"></i> Qualification Level
                        </label>
                        <select name="qualification_level_id" class="form-select @error('qualification_level_id') is-invalid @enderror">
                            <option value="">Select Qualification Level</option>
                            @foreach($qualificationLevels as $level)
                                <option value="{{ $level->id }}" {{ old('qualification_level_id') == $level->id ? 'selected' : '' }}>
                                    {{ $level->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('qualification_level_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Program Name -->
                    <div class="col-md-12">
                        <label class="form-label">
                            <i class="fas fa-book text-info me-1"></i> Program Name
                        </label>
                        <input type="text" 
                               name="program_name" 
                               class="form-control @error('program_name') is-invalid @enderror"
                               value="{{ old('program_name') }}"
                               placeholder="Enter program name">
                        @error('program_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Dates -->
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
                            <i class="fas fa-calendar-check text-success me-1"></i> Completion Date
                        </label>
                        <input type="text" 
                               name="completion_date" 
                               class="form-control datetimepicker @error('completion_date') is-invalid @enderror"
                               value="{{ old('completion_date') }}"
                               placeholder="Select completion date">
                        @error('completion_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Grade and Certificate Number -->
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="fas fa-star text-warning me-1"></i> Grade Achieved
                        </label>
                        <input type="text" 
                               name="grade_achieved" 
                               class="form-control @error('grade_achieved') is-invalid @enderror"
                               value="{{ old('grade_achieved') }}"
                               placeholder="Enter grade achieved">
                        @error('grade_achieved')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="fas fa-certificate text-info me-1"></i> Certificate Number
                        </label>
                        <input type="text" 
                               name="certificate_number" 
                               class="form-control @error('certificate_number') is-invalid @enderror"
                               value="{{ old('certificate_number') }}"
                               placeholder="Enter certificate number">
                        @error('certificate_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="col-md-12">
                        <label class="form-label">
                            <i class="fas fa-info-circle text-primary me-1"></i> Status
                        </label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror">
                            <option value="">Select Status</option>
                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="incomplete" {{ old('status') == 'incomplete' ? 'selected' : '' }}>Incomplete</option>
                            <option value="verified" {{ old('status') == 'verified' ? 'selected' : '' }}>Verified</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- File Uploads -->
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="fas fa-file-pdf text-danger me-1"></i> Certificate
                        </label>
                        <input type="file" 
                               name="certificate_path" 
                               class="form-control @error('certificate_path') is-invalid @enderror"
                               accept=".pdf,.jpg,.jpeg,.png">
                        @error('certificate_path')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="fas fa-file-alt text-secondary me-1"></i> Transcript
                        </label>
                        <input type="file" 
                               name="transcript_path" 
                               class="form-control @error('transcript_path') is-invalid @enderror"
                               accept=".pdf,.jpg,.jpeg,.png">
                        @error('transcript_path')
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
                                  placeholder="Enter any additional notes">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <hr>
                        <div class="d-flex justify-content-start gap-2">
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Save Academic History
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