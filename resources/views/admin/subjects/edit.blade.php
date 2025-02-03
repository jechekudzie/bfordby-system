@extends('layouts.admin')

@section('content')
<div class="card mb-3">
    <div class="card-header">
        <div class="row flex-between-end">
            <div class="col-auto align-self-center">
                <h5 class="mb-0">Edit Subject</h5>
            </div>
        </div>
    </div>
    <div class="card-body bg-light">
        <form action="{{ route('admin.subjects.update', $subject->slug) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <!-- Name -->
                <div class="col-md-6">
                    <label class="form-label" for="name">Subject Name</label>
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

                <!-- Code -->
                <div class="col-md-6">
                    <label class="form-label" for="code">Subject Code</label>
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

                <!-- Course Selection -->
                <div class="col-md-6">
                    <label class="form-label" for="course_id">Course</label>
                    <select class="form-select @error('course_id') is-invalid @enderror" 
                            id="course_id" 
                            name="course_id" 
                            required>
                        <option value="">Select Course</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" 
                                {{ old('course_id', $subject->course_id) == $course->id ? 'selected' : '' }}>
                                {{ $course->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('course_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Credits -->
                <div class="col-md-6">
                    <label class="form-label" for="credits">Credits</label>
                    <input type="number" 
                           class="form-control @error('credits') is-invalid @enderror" 
                           id="credits" 
                           name="credits" 
                           value="{{ old('credits', $subject->credits) }}">
                    @error('credits')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Level -->
                <div class="col-md-6">
                    <label class="form-label" for="level">Level</label>
                    <select class="form-select @error('level') is-invalid @enderror" 
                            id="level" 
                            name="level">
                        <option value="">Select Level</option>
                        <option value="beginner" {{ old('level', $subject->level) == 'beginner' ? 'selected' : '' }}>Beginner</option>
                        <option value="intermediate" {{ old('level', $subject->level) == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                        <option value="advanced" {{ old('level', $subject->level) == 'advanced' ? 'selected' : '' }}>Advanced</option>
                    </select>
                    @error('level')
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
                        <option value="active" {{ old('status', $subject->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $subject->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Description -->
                <div class="col-12">
                    <label class="form-label" for="description">Subject Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" 
                              name="description" 
                              rows="3">{{ old('description', $subject->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Save Button -->
            <div class="mt-3">
                <button type="submit" class="btn btn-success">Update Subject</button>
                <a href="{{ route('admin.subjects.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
