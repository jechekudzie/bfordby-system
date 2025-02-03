@extends('layouts.admin')

@push('head')
<!-- Datatable CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" />
@endpush

@section('content')
<div class="card mb-3">
    <div class="card-header">
        <div class="row flex-between-end">
            <div class="col-auto align-self-center">
                <h5 class="mb-0">Course Management</h5>
            </div>
            <div class="col-auto ms-auto">
                <a href="{{ route('admin.utilities.index') }}" class="btn btn-secondary btn-sm me-2">
                    <span class="fas fa-arrow-left me-1"></span> Back to Utilities
                </a>
                <a href="{{ route('admin.courses.create') }}" class="btn btn-primary btn-sm">
                    <span class="fas fa-plus me-1"></span> Add Course
                </a>
            </div>
        </div>
    </div>
    <div class="card-body bg-light">
        <div class="table-responsive">
            <table id="buttons-datatables" class="table table-bordered table-striped fs--1 mb-0">
                <thead class="bg-200 text-900">
                    <tr>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Duration (Months)</th>
                        <th>Fee</th>
                        <th>Study Mode</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses as $course)
                    <tr>
                        <td>{{ $course->name }}</td>
                        <td>{{ $course->code }}</td>
                        <td>{{ $course->duration_months ?? 'N/A' }}</td>
                        <td>${{ number_format($course->fee, 2) }}</td>
                        <td>
                            <span class="badge bg-{{ $course->study_mode === 'full-time' ? 'primary' : 'info' }}">
                                {{ ucfirst($course->study_mode) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $course->status === 'active' ? 'success' : 'danger' }}">
                                {{ ucfirst($course->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('admin.courses.show', $course) }}"
                                    class="btn btn-falcon-info"
                                    title="View">
                                    <span class="fas fa-eye"></span>
                                </a>
                                <a href="{{ route('admin.courses.edit', $course) }}"
                                    class="btn btn-falcon-primary"
                                    title="Edit">
                                    <span class="fas fa-edit"></span>
                                </a>
                                <form action="{{ route('admin.courses.destroy', $course) }}"
                                    method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="btn btn-falcon-danger"
                                        title="Delete"
                                        onclick="return confirm('Are you sure you want to delete this course?')">
                                        <span class="fas fa-trash"></span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No courses found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script>
    $(document).ready(function() {
        $('#buttons-datatables').DataTable({
            responsive: true,
            autoWidth: false,
            order: [
                [0, 'asc']
            ]
        });
    });
</script>
@endpush