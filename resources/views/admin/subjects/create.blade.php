@extends('layouts.admin')

@section('content')
<div class="card mb-3">
    <div class="card-header">
        <h5 class="mb-0">Add New Subject</h5>
    </div>
    <div class="card-body bg-light">
        <form action="{{ route('admin.subjects.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="name">Name</label>
                    <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="code">Code</label>
                    <input type="text" class="form-control" name="code" value="{{ old('code') }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="course_id">Course</label>
                    <select class="form-select" name="course_id" required>
                        <option value="">Select Course</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}">{{ $course->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="credits">Credits</label>
                    <input type="number" class="form-control" name="credits" value="{{ old('credits') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="level">Level</label>
                    <select class="form-select" name="level">
                        <option value="">Select Level</option>
                        <option value="beginner">Beginner</option>
                        <option value="intermediate">Intermediate</option>
                        <option value="advanced">Advanced</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="status">Status</label>
                    <select class="form-select" name="status">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label" for="description">Description</label>
                    <textarea class="form-control" name="description" rows="3">{{ old('description') }}</textarea>
                </div>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Create Subject</button>
                <a href="{{ route('admin.subjects.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
