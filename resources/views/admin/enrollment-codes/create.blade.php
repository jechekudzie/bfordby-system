@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">
                    <i class="fas fa-plus-circle me-1 text-primary"></i>Add Enrollment Code
                </h5>
                <p class="mb-0 text-muted small">Create a new enrollment code</p>
            </div>
        </div>
    </div>

    <div class="card-body">
    

        <form action="{{ route('admin.enrollment-codes.store') }}" method="POST">
            @csrf
            
            <div class="row g-3">
                <!-- Course -->
                <div class="col-md-6">
                    <label class="form-label">
                        <i class="fas fa-book text-primary me-1"></i>Course
                    </label>
                    <select name="course_id" 
                            class="form-select @error('course_id') is-invalid @enderror" 
                            required>
                        <option value="">Select Course</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                {{ $course->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('course_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Study Mode -->
                <div class="col-md-6">
                    <label class="form-label">
                        <i class="fas fa-clock text-primary me-1"></i>Study Mode
                    </label>
                    <select name="study_mode_id" 
                            class="form-select @error('study_mode_id') is-invalid @enderror" 
                            required>
                        <option value="">Select Study Mode</option>
                        @foreach($studyModes as $mode)
                            <option value="{{ $mode->id }}" {{ old('study_mode_id') == $mode->id ? 'selected' : '' }}>
                                {{ $mode->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('study_mode_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Year -->
                <div class="col-md-6">
                    <label class="form-label">
                        <i class="fas fa-calendar text-primary me-1"></i>Year
                    </label>
                    <input type="number" 
                           name="year" 
                           class="form-control @error('year') is-invalid @enderror" 
                           value="{{ old('year', $currentYear) }}"
                           min="{{ $currentYear }}"
                           required>
                    @error('year')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Base Code -->
                <div class="col-md-6">
                    <label class="form-label">
                        <i class="fas fa-qrcode text-primary me-1"></i>Base Code
                    </label>
                    <input type="text" 
                           name="base_code" 
                           class="form-control @error('base_code') is-invalid @enderror" 
                           value="{{ old('base_code') }}"
                           placeholder="e.g., GAD, GADPT"
                           required>
                    <div class="form-text">
                        Enter a unique code that will be used as the base for student enrollment numbers.
                    </div>
                    @error('base_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i>Save Enrollment Code
                </button>
                <a href="{{ route('admin.enrollment-codes.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times me-1"></i>Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection 