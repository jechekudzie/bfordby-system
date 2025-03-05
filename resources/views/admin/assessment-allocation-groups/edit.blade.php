@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <!-- Back button -->
        <div class="mb-4">
            <a href="{{ route('admin.assessment-allocation-groups.index', $allocation) }}" 
               class="btn btn-link text-decoration-none ps-0">
                <i class="fas fa-arrow-left me-2"></i>Back to Groups
            </a>
        </div>

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-2">
                        <i class="fas fa-users text-primary"></i>
                    </div>
                    <h1 class="h4 mb-0">Edit Group</h1>
                </div>
                <p class="text-muted small mb-0 mt-2">
                    {{ $allocation->assessment->name }} | {{ $allocation->semester->name }}
                </p>
            </div>
        </div>

        <!-- Form Card -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('admin.assessment-allocation-groups.update', [$allocation, $group]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <!-- Group Name -->
                    <div class="mb-4">
                        <label for="name" class="form-label">Group Name</label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="{{ old('name', $group->name) }}"
                               class="form-control form-control-lg @error('name') is-invalid @enderror"
                               placeholder="Enter group name"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Members Selection -->
                    <div class="mb-4">
                        <label class="form-label d-flex justify-content-between align-items-center">
                            <span>Group Members</span>
                            <div>
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="selectAll()">
                                    <i class="fas fa-check-square me-1"></i>Select All
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary ms-2" onclick="deselectAll()">
                                    <i class="fas fa-square me-1"></i>Clear All
                                </button>
                            </div>
                        </label>
                        <div class="small text-muted mb-3">Select at least 2 members for the group</div>
                        
                        <div class="border rounded-3 p-3" style="max-height: 400px; overflow-y: auto;">
                            <div class="row g-3">
                                @foreach($students as $student)
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input type="checkbox" 
                                                   class="form-check-input" 
                                                   name="members[]" 
                                                   value="{{ $student->id }}"
                                                   id="student_{{ $student->id }}"
                                                   {{ in_array($student->id, old('members', $group->students->pluck('id')->toArray())) ? 'checked' : '' }}>
                                            <label class="form-check-label d-flex align-items-center gap-2" for="student_{{ $student->id }}">
                                                <div class="rounded-circle bg-light p-1">
                                                    <i class="fas fa-user-graduate text-primary"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-medium">{{ $student->first_name }} {{ $student->last_name }}</div>
                                                    <small class="text-muted">{{ optional($student->enrollments->first())->student_number }}</small>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @error('members')
                            <div class="text-danger mt-2 small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Form Actions -->
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.assessment-allocation-groups.index', $allocation) }}"
                           class="btn btn-light">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .form-check-input:checked {
        background-color: var(--bs-primary);
        border-color: var(--bs-primary);
    }
    .form-control-lg {
        font-size: 1rem;
    }
    .btn-light {
        background-color: #f8f9fa;
        border-color: #f8f9fa;
    }
    .btn-light:hover {
        background-color: #e9ecef;
        border-color: #e9ecef;
    }
</style>
@endpush

@push('scripts')
<script>
    function selectAll() {
        document.querySelectorAll('input[name="members[]"]').forEach(checkbox => checkbox.checked = true);
    }

    function deselectAll() {
        document.querySelectorAll('input[name="members[]"]').forEach(checkbox => checkbox.checked = false);
    }
</script>
@endpush 