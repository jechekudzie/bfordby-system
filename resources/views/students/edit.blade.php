@extends('layouts.backend')

@section('content')
<div class="card mb-3">
    <div class="card-header">
        <div class="row flex-between-end">
            <div class="col-auto align-self-center">
                <h5 class="mb-0">Edit Student: {{ $student->first_name }} {{ $student->last_name }}</h5>
            </div>
        </div>
    </div>
    <div class="card-body bg-light">
        <form action="{{ route('students.update', $student) }}" method="POST">
            @csrf
            @method('PATCH')
            
            <div class="row g-3">
                <!-- Personal Information -->
                <div class="col-md-6">
                    <label class="form-label" for="first_name">First Name</label>
                    <input type="text" 
                           class="form-control @error('first_name') is-invalid @enderror" 
                           id="first_name" 
                           name="first_name" 
                           value="{{ old('first_name', $student->first_name) }}" 
                           required>
                    @error('first_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="last_name">Last Name</label>
                    <input type="text" 
                           class="form-control @error('last_name') is-invalid @enderror" 
                           id="last_name" 
                           name="last_name" 
                           value="{{ old('last_name', $student->last_name) }}" 
                           required>
                    @error('last_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="email">Email</label>
                    <input type="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           id="email" 
                           name="email" 
                           value="{{ old('email', $student->email) }}" 
                           required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="phone">Phone</label>
                    <input type="text" 
                           class="form-control @error('phone') is-invalid @enderror" 
                           id="phone" 
                           name="phone" 
                           value="{{ old('phone', $student->phone) }}">
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="date_of_birth">Date of Birth</label>
                    <input type="date" 
                           class="form-control @error('date_of_birth') is-invalid @enderror" 
                           id="date_of_birth" 
                           name="date_of_birth" 
                           value="{{ old('date_of_birth', date('Y-m-d', strtotime($student->date_of_birth))) }}" 
                           required>
                    @error('date_of_birth')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="gender_id">Gender</label>
                    <select class="form-select @error('gender_id') is-invalid @enderror" 
                            id="gender_id" 
                            name="gender_id" 
                            required>
                        <option value="">Select Gender</option>
                        @foreach($genders as $gender)
                            <option value="{{ $gender->id }}" 
                                {{ old('gender_id', $student->gender_id) == $gender->id ? 'selected' : '' }}>
                                {{ $gender->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('gender_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Academic Information -->
                <div class="col-md-6">
                    <label class="form-label" for="enrollment_date">Enrollment Date</label>
                    <input type="date" 
                           class="form-control @error('enrollment_date') is-invalid @enderror" 
                           id="enrollment_date" 
                           name="enrollment_date" 
                           value="{{ old('enrollment_date', date('Y-m-d', strtotime($student->enrollment_date))) }}" 
                           required>
                    @error('enrollment_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="courses">Courses</label>
                    <select class="form-select @error('courses') is-invalid @enderror" 
                            id="courses" 
                            name="courses[]" 
                            multiple 
                            required>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" 
                                {{ (old('courses', $student->courses->pluck('id')->toArray()) && 
                                    in_array($course->id, old('courses', $student->courses->pluck('id')->toArray()))) 
                                    ? 'selected' : '' }}>
                                {{ $course->name }} ({{ ucfirst($course->study_mode) }})
                            </option>
                        @endforeach
                    </select>
                    @error('courses')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="status">Status</label>
                    <select class="form-select @error('status') is-invalid @enderror" 
                            id="status" 
                            name="status" 
                            required>
                        <option value="active" {{ old('status', $student->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $student->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label" for="notes">Notes</label>
                    <textarea class="form-control @error('notes') is-invalid @enderror" 
                              id="notes" 
                              name="notes" 
                              rows="3">{{ old('notes', $student->notes) }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Update Student</button>
                <a href="{{ route('students.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize any plugins or form validation here
    });
</script>
@endpush
