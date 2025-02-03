@extends('layouts.admin')

@section('content')
<div class="card mb-3">
    <div class="card-header">
        <h5 class="mb-0">Enroll a Student</h5>
    </div>
    <div class="card-body bg-light">
        <form action="{{ route('students.store') }}" method="POST">
            @csrf

            <div class="row g-3">
                <!-- Title -->
                <div class="col-md-4">
                    <label class="form-label">Title</label>
                    <select class="form-select" name="title_id">
                        <option value="">Select Title</option>
                        @foreach($titles as $title)
                        <option value="{{ $title->id }}">{{ $title->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Gender -->
                <div class="col-md-4">
                    <label class="form-label">Gender</label>
                    <select class="form-select" name="gender_id">
                        <option value="">Select Gender</option>
                        @foreach($genders as $gender)
                        <option value="{{ $gender->id }}">{{ $gender->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row g-3">

                <!-- Name Fields in One Row -->
                <div class="col-md-4">
                    <label class="form-label">First Name</label>
                    <input type="text" class="form-control" name="first_name" value="{{ old('first_name') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Middle Name</label>
                    <input type="text" class="form-control" name="middle_name" value="{{ old('middle_name') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Last Name</label>
                    <input type="text" class="form-control" name="last_name" value="{{ old('last_name') }}" required>
                </div>

                <!-- Date of Birth -->
                <div class="col-md-4">
                    <label class="form-label">Date of Birth</label>
                    <input type="text" class="form-control datetimepicker" name="date_of_birth" value="{{ old('date_of_birth') }}" required>
                </div>

                <!-- Enrollment Date -->
                <div class="col-md-4">
                    <label class="form-label">Enrollment Date</label>
                    <input type="text" class="form-control datetimepicker" name="enrollment_date" value="{{ old('enrollment_date') }}">
                </div>

                <!-- Status -->
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="status">
                        <option value="pending">Pending</option>
                        <option value="active">Active</option>
                        <option value="graduated">Graduated</option>
                        <option value="inactive">Inactive</option>
                        <option value="withdrawn">Withdrawn</option>
                    </select>
                </div>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Enroll Student</button>
                <a href="{{ route('students.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')

@endpush