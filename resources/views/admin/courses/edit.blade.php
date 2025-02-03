@extends('layouts.admin')

@section('content')
<div class="card mb-3">
    <div class="card-header">
        <div class="row flex-between-end">
            <div class="col-auto align-self-center">
                <h5 class="mb-0">Edit Course</h5>
            </div>
        </div>
    </div>
    <div class="card-body bg-light">
        <form action="{{ route('admin.courses.update', $course->slug) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <!-- Course Name -->
                <div class="col-md-6">
                    <label class="form-label" for="name">Course Name</label>
                    <input type="text" 
                           class="form-control @error('name') is-invalid @enderror" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $course->name) }}" 
                           required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Course Code -->
                <div class="col-md-6">
                    <label class="form-label" for="code">Course Code</label>
                    <input type="text" 
                           class="form-control @error('code') is-invalid @enderror" 
                           id="code" 
                           name="code" 
                           value="{{ old('code', $course->code) }}" 
                           required>
                    @error('code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Duration -->
                <div class="col-md-6">
                    <label class="form-label" for="duration_months">Duration (Months)</label>
                    <input type="number" 
                           class="form-control @error('duration_months') is-invalid @enderror" 
                           id="duration_months" 
                           name="duration_months" 
                           value="{{ old('duration_months', $course->duration_months) }}">
                    @error('duration_months')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Fee -->
                <div class="col-md-6">
                    <label class="form-label" for="fee">Fee</label>
                    <input type="text" 
                           class="form-control @error('fee') is-invalid @enderror" 
                           id="fee" 
                           name="fee" 
                           value="{{ old('fee', $course->fee) }}">
                    @error('fee')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Study Mode -->
                <div class="col-md-6">
                    <label class="form-label" for="study_mode">Study Mode</label>
                    <select class="form-select @error('study_mode') is-invalid @enderror" 
                            id="study_mode" 
                            name="study_mode" 
                            required>
                        <option value="full-time" {{ old('study_mode', $course->study_mode) == 'full-time' ? 'selected' : '' }}>Full-Time</option>
                        <option value="part-time" {{ old('study_mode', $course->study_mode) == 'part-time' ? 'selected' : '' }}>Part-Time</option>
                    </select>
                    @error('study_mode')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Status -->
                <div class="col-md-6">
                    <label class="form-label" for="status">Status</label>
                    <select class="form-select @error('status') is-invalid @enderror" 
                            id="status" 
                            name="status" 
                            required>
                        <option value="active" {{ old('status', $course->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $course->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Course Description -->
                <div class="col-12">
                    <label class="form-label" for="description">Course Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" 
                              name="description" 
                              rows="3">{{ old('description', $course->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Save Button -->
            <div class="mt-3">
                <button type="submit" class="btn btn-success">Update Course</button>
                <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
