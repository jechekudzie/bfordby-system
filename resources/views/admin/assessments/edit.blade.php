@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">
                    <i class="fas fa-edit text-primary me-2"></i>Edit Assessment
                </h5>
                <div class="text-muted">
                    <div class="mb-1">
                        Course: <span class="fw-bold text-dark">{{ $subject->course->name }}</span>
                    </div>
                    <div class="mb-1">
                        Subject: <span class="fw-bold text-dark">{{ $subject->name }}</span>
                        <span class="mx-2">•</span>
                        Code: <span class="fw-bold text-dark">{{ $subject->code }}</span>
                    </div>
                    <div>
                        Module: <span class="fw-bold text-dark">{{ $module->name }}</span>
                    </div>
                </div>
            </div>
            <a href="{{ route('admin.courses.subjects.modules.assessments.index', [$subject->slug, $module]) }}" 
               class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i>Back to Assessments
            </a>
        </div>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.courses.subjects.modules.assessments.update', [$subject->slug, $module, $assessment]) }}" 
              method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Assessment Name</label>
                    <input type="text" 
                           class="form-control @error('name') is-invalid @enderror" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $assessment->name) }}" 
                           required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="type" class="form-label">Assessment Type</label>
                    <select class="form-select @error('type') is-invalid @enderror" 
                            id="type" 
                            name="type" 
                            required>
                        <option value="">Select Type</option>
                        @foreach($assessmentWeights as $type => $weight)
                            <option value="{{ $type }}" {{ old('type', $assessment->type) == $type ? 'selected' : '' }}>
                                {{ $type }} ({{ $weight }}%)
                            </option>
                        @endforeach
                    </select>
                    <div class="form-text text-muted">
                        The percentage indicates this assessment type's contribution to the final grade
                    </div>
                    @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-12 mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" 
                              name="description" 
                              rows="3">{{ old('description', $assessment->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="max_score" class="form-label">Maximum Score</label>
                    <input type="number" 
                           step="0.01" 
                           class="form-control @error('max_score') is-invalid @enderror" 
                           id="max_score" 
                           name="max_score" 
                           value="{{ old('max_score', $assessment->max_score) }}" 
                           required>
                    @error('max_score')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select @error('status') is-invalid @enderror" 
                            id="status" 
                            name="status" 
                            required>
                        <option value="">Select Status</option>
                        <option value="draft" {{ old('status', $assessment->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status', $assessment->status) == 'published' ? 'selected' : '' }}>Published</option>
                        <option value="archived" {{ old('status', $assessment->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-start gap-2 mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Update Assessment
                </button>
                <a href="{{ route('admin.courses.subjects.modules.assessments.index', [$subject->slug, $module]) }}" 
                   class="btn btn-outline-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
