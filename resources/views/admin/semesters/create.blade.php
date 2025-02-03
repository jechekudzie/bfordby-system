@extends('layouts.admin')

@section('content')
<div class="card mb-3">
    <div class="card-header">
        <h5 class="mb-0">Add New Semester</h5>
    </div>
    <div class="card-body bg-light">
        <form action="{{ route('admin.semesters.store') }}" method="POST">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="name">Semester Name</label>
                    <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="academic_year">Academic Year</label>
                    <select class="form-select" name="academic_year" id="academic_year">
                        <option value="">Select Year</option>
                        @for ($year = now()->year - 5; $year <= now()->year + 5; $year++)
                            <option value="{{ $year }}" {{ old('academic_year') == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="start_date">Start Date</label>
                    <input type="text" class="form-control datetimepicker" id="start_date" name="start_date" placeholder="dd/mm/yyyy" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="end_date">End Date</label>
                    <input type="text" class="form-control datetimepicker" id="end_date" name="end_date" placeholder="dd/mm/yyyy" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="type">Semester Type</label>
                    <select class="form-select" name="type" required>
                        <option value="semester">Semester</option>
                        <option value="trimester">Trimester</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="status">Status</label>
                    <select class="form-select" name="status">
                        <option value="upcoming">Upcoming</option>
                        <option value="active">Active</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label" for="description">Description</label>
                    <textarea class="form-control" name="description" rows="3">{{ old('description') }}</textarea>
                </div>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Save Semester</button>
                <a href="{{ route('admin.semesters.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection


