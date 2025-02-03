@extends('layouts.admin')

@section('content')
<div class="card mb-3">
    <div class="card-header">
        <h5 class="mb-0">Edit Semester</h5>
    </div>
    <div class="card-body bg-light">
        <form action="{{ route('admin.semesters.update', $semester->slug) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="name">Semester Name</label>
                    <input type="text" class="form-control" name="name" value="{{ old('name', $semester->name) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="academic_year">Academic Year</label>
                    <select class="form-select" name="academic_year" id="academic_year">
                        <option value="">Select Year</option>
                        @for ($year = now()->year - 5; $year <= now()->year + 5; $year++)
                            <option value="{{ $year }}" {{ old('academic_year', $semester->academic_year) == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="start_date">Start Date</label>
                    <input type="text" class="form-control datetimepicker" id="start_date" name="start_date"
                           value="{{ old('start_date', \Carbon\Carbon::parse($semester->start_date)->format('d/m/Y')) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="end_date">End Date</label>
                    <input type="text" class="form-control datetimepicker" id="end_date" name="end_date"
                           value="{{ old('end_date', \Carbon\Carbon::parse($semester->end_date)->format('d/m/Y')) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="type">Semester Type</label>
                    <select class="form-select" name="type" required>
                        <option value="semester" {{ old('type', $semester->type) == 'semester' ? 'selected' : '' }}>Semester</option>
                        <option value="trimester" {{ old('type', $semester->type) == 'trimester' ? 'selected' : '' }}>Trimester</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="status">Status</label>
                    <select class="form-select" name="status">
                        <option value="upcoming" {{ old('status', $semester->status) == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                        <option value="active" {{ old('status', $semester->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="completed" {{ old('status', $semester->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label" for="description">Description</label>
                    <textarea class="form-control" name="description" rows="3">{{ old('description', $semester->description) }}</textarea>
                </div>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Update Semester</button>
                <a href="{{ route('admin.semesters.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection


