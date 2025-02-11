@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">
                    <i class="fas fa-edit text-primary me-2"></i>Edit Subject
                </h5>
                <p class="mb-0 text-muted">
                    Course: <span class="fw-bold text-dark">{{ $course->name }}</span>
                    <span class="mx-2">•</span>
                    Code: <span class="fw-bold text-dark">{{ $course->code }}</span>
                </p>
            </div>
            <a href="{{ route('admin.courses.subjects.index', $course) }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i>Back to Subjects
            </a>
        </div>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.courses.subjects.update', [$course, $subject]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Subject Name</label>
                    <input type="text" 
                           class="form-control @error('name') is-invalid @enderror" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $subject->name) }}" 
                           required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="code" class="form-label">Subject Code</label>
                    <input type="text" 
                           class="form-control @error('code') is-invalid @enderror" 
                           id="code" 
                           name="code" 
                           value="{{ old('code', $subject->code) }}" 
                           required>
                    @error('code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-12 mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" 
                              name="description" 
                              rows="3">{{ old('description', $subject->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="credit_hours" class="form-label">Credit Hours</label>
                    <input type="number" 
                           step="0.01" 
                           class="form-control @error('credit_hours') is-invalid @enderror" 
                           id="credit_hours" 
                           name="credit_hours" 
                           value="{{ old('credit_hours', $subject->credit_hours) }}" 
                           required>
                    @error('credit_hours')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-start gap-2 mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Update Subject
                </button>
                <a href="{{ route('admin.courses.subjects.index', $course) }}" class="btn btn-outline-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
