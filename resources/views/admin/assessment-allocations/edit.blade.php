@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">
                    <i class="fas fa-edit text-primary me-2"></i>Edit Allocation
                </h5>
                <div class="text-muted">
                    <div class="mb-1">
                        Course: <span class="fw-bold text-dark">{{ $module->subject->course->name }}</span>
                    </div>
                    <div class="mb-1">
                        Subject: <span class="fw-bold text-dark">{{ $module->subject->name }}</span>
                        <span class="mx-2">•</span>
                        Code: <span class="fw-bold text-dark">{{ $module->subject->code }}</span>
                    </div>
                    <div class="mb-1">
                        Module: <span class="fw-bold text-dark">{{ $module->name }}</span>
                    </div>
                    <div>
                        Assessment: <span class="fw-bold text-dark">{{ $assessment->name }}</span>
                        <span class="badge bg-{{ 
                            $assessment->type === 'exam' ? 'danger' : 
                            ($assessment->type === 'test' ? 'warning' : 
                            ($assessment->type === 'practical' ? 'info' : 
                            ($assessment->type === 'theory' ? 'primary' : 'success'))) 
                        }} ms-2">{{ ucfirst($assessment->type) }}</span>
                    </div>
                </div>
            </div>
            <a href="{{ route('modules.assessments.allocations.index', [$module, $assessment]) }}" 
               class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i>Back to Allocations
            </a>
        </div>
    </div>

    <div class="card-body">
        <form action="{{ route('modules.assessments.allocations.update', [$module, $assessment, $allocation]) }}" 
              method="POST" 
              enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="enrollment_code_id" class="form-label">Enrollment Code</label>
                        <select class="form-select @error('enrollment_code_id') is-invalid @enderror" 
                                id="enrollment_code_id" 
                                name="enrollment_code_id" 
                                required>
                            <option value="">Select Enrollment Code</option>
                            @foreach($enrollmentCodes as $code)
                                <option value="{{ $code->id }}" 
                                        {{ (old('enrollment_code_id') ?? $allocation->enrollment_code_id) == $code->id ? 'selected' : '' }}>
                                    {{ $code->base_code }}
                                </option>
                            @endforeach
                        </select>
                        @error('enrollment_code_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="semester_id" class="form-label">Semester</label>
                        <select class="form-select @error('semester_id') is-invalid @enderror" 
                                id="semester_id" 
                                name="semester_id" 
                                required>
                            <option value="">Select Semester</option>
                            @foreach($semesters as $semester)
                                <option value="{{ $semester->id }}" 
                                        {{ (old('semester_id') ?? $allocation->semester_id) == $semester->id ? 'selected' : '' }}>
                                    {{ $semester->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('semester_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="due_date" class="form-label">Due Date</label>
                        <input type="date" 
                               class="form-control @error('due_date') is-invalid @enderror" 
                               id="due_date" 
                               name="due_date" 
                               value="{{ old('due_date') ?? $allocation->due_date }}" 
                               required>
                        @error('due_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select @error('status') is-invalid @enderror" 
                                id="status" 
                                name="status" 
                                required>
                            <option value="pending" {{ (old('status') ?? $allocation->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="submitted" {{ (old('status') ?? $allocation->status) == 'submitted' ? 'selected' : '' }}>Submitted</option>
                            <option value="graded" {{ (old('status') ?? $allocation->status) == 'graded' ? 'selected' : '' }}>Graded</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group">
                        <label for="content" class="form-label">Content</label>
                        <textarea class="form-control @error('content') is-invalid @enderror" 
                                  id="content" 
                                  name="content" 
                                  rows="4">{{ old('content') ?? $allocation->content }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group">
                        <label for="file" class="form-label">File Attachment</label>
                        @if($allocation->file_path)
                            <div class="mb-2">
                                <a href="{{ asset('storage/' . $allocation->file_path) }}" 
                                   class="btn btn-sm btn-outline-info" 
                                   target="_blank">
                                    <i class="fas fa-file-download me-1"></i>Current File
                                </a>
                            </div>
                        @endif
                        <input type="file" 
                               class="form-control @error('file') is-invalid @enderror" 
                               id="file" 
                               name="file">
                        <small class="text-muted">Upload new file to replace existing one</small>
                        @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-12">
                    <hr>
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('modules.assessments.allocations.index', [$module, $assessment]) }}" 
                           class="btn btn-outline-secondary">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Allocation
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection 