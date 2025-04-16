@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">
                        <i class="fas fa-edit me-1 text-primary"></i>
                        Edit Enrollment
                    </h5>
                    <p class="mb-0 text-muted small">
                        Student: <span class="fw-bold text-dark">{{ $student->first_name }} {{ $student->last_name }}</span>
                    </p>
                </div>
                <a href="{{ route('students.show', $student) }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-1"></i>Back to Student
                </a>
            </div>
        </div>

        <div class="card-body">
            <form action="{{ route('students.enrollments.update', [$student, $enrollment]) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="course_id" class="form-label">Course</label>
                    <select name="course_id" id="course_id" class="form-select @error('course_id') is-invalid @enderror" required>
                        <option value="">Select Course</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ old('course_id', $enrollment->course_id) == $course->id ? 'selected' : '' }}>
                                {{ $course->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('course_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="study_mode_id" class="form-label">Study Mode</label>
                    <select name="study_mode_id" id="study_mode_id" class="form-select @error('study_mode_id') is-invalid @enderror" required>
                        <option value="">Select Study Mode</option>
                        @foreach($studyModes as $mode)
                            <option value="{{ $mode->id }}" {{ old('study_mode_id', $enrollment->study_mode_id) == $mode->id ? 'selected' : '' }}>
                                {{ $mode->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('study_mode_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="enrollment_code_id" class="form-label">Enrollment Code</label>
                    <select name="enrollment_code_id" id="enrollment_code_id" class="form-select @error('enrollment_code_id') is-invalid @enderror" required>
                        <option value="{{ $enrollment->enrollment_code_id }}">
                            {{ $enrollment->enrollmentCode->base_code }} ({{ $enrollment->enrollmentCode->year }})
                        </option>
                    </select>
                    @error('enrollment_code_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="enrollment_date" class="form-label">Enrollment Date</label>
                    <input type="date" 
                           class="form-control @error('enrollment_date') is-invalid @enderror" 
                           id="enrollment_date" 
                           name="enrollment_date"
                           value="{{ old('enrollment_date', $enrollment->enrollment_date) }}"
                           required>
                    @error('enrollment_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ old('status', $enrollment->status) == $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="entry_type" class="form-label">Entry Type</label>
                    <select name="entry_type" id="entry_type" class="form-select @error('entry_type') is-invalid @enderror" required>
                        <option value="normal" {{ old('entry_type', $enrollment->entry_type) == 'normal' ? 'selected' : '' }}>Normal</option>
                        <option value="special" {{ old('entry_type', $enrollment->entry_type) == 'special' ? 'selected' : '' }}>Special</option>
                        <option value="mature" {{ old('entry_type', $enrollment->entry_type) == 'mature' ? 'selected' : '' }}>Mature</option>
                        <option value="repeat" {{ old('entry_type', $enrollment->entry_type) == 'repeat' ? 'selected' : '' }}>Repeat</option>
                    </select>
                    @error('entry_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('students.show', $student) }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Update Enrollment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const courseSelect = document.getElementById('course_id');
    const studyModeSelect = document.getElementById('study_mode_id');
    const enrollmentCodeSelect = document.getElementById('enrollment_code_id');

    function updateEnrollmentCodes() {
        const courseId = courseSelect.value;
        const studyModeId = studyModeSelect.value;

        if (courseId && studyModeId) {
            fetch(`/admin/enrollments/codes?course_id=${courseId}&study_mode_id=${studyModeId}`)
                .then(response => response.json())
                .then(codes => {
                    enrollmentCodeSelect.innerHTML = '<option value="">Select Enrollment Code</option>';
                    codes.forEach(code => {
                        enrollmentCodeSelect.innerHTML += `
                            <option value="${code.id}">${code.base_code} (${code.year})</option>
                        `;
                    });
                });
        } else {
            enrollmentCodeSelect.innerHTML = '<option value="">Select Course and Study Mode First</option>';
        }
    }

    courseSelect.addEventListener('change', updateEnrollmentCodes);
    studyModeSelect.addEventListener('change', updateEnrollmentCodes);
});
</script>
@endpush
@endsection 