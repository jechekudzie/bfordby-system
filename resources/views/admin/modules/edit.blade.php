@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">
                    <i class="fas fa-edit text-primary me-2"></i>Edit Module
                </h5>
                <div class="text-muted">
                    <div class="mb-1">
                        Course: <span class="fw-bold text-dark">{{ $subject->course->name }}</span>
                    </div>
                    <div>
                        Subject: <span class="fw-bold text-dark">{{ $subject->name }}</span>
                        <span class="mx-2">•</span>
                        Code: <span class="fw-bold text-dark">{{ $subject->code }}</span>
                    </div>
                </div>
            </div>
            <a href="{{ route('admin.courses.subjects.modules.index', [$subject->slug]) }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i>Back to Modules
            </a>
        </div>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.courses.subjects.modules.update', [$subject, $module]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="name" class="form-label">Module Name</label>
                    <input type="text" 
                           class="form-control @error('name') is-invalid @enderror" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $module->name) }}" 
                           required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-12 mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" 
                              name="description" 
                              rows="3">{{ old('description', $module->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-start gap-2 mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Update Module
                </button>
                <a href="{{ route('admin.courses.subjects.modules.index', [$subject->slug]) }}" class="btn btn-outline-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection 