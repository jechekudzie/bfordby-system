@extends('layouts.admin')

@push('head')
<!-- Datatable CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" />
@endpush

@section('content')
<div class="card">
    <div class="card-header bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">
                    <i class="fas fa-graduation-cap me-1 text-primary"></i>Course Management
                </h5>
                <p class="mb-0 text-muted small">Manage academic courses and their details</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.utilities.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-1"></i>Back to Utilities
                </a>
                <a href="{{ route('admin.courses.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-1"></i>Add Course
                </a>
            </div>
        </div>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <div class="d-flex">
                    <i class="fas fa-check-circle me-2 mt-1"></i>
                    <div>{{ session('success') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover align-middle" id="buttons-datatables">
                <thead class="bg-light">
                    <tr>
                        <th scope="col" style="width: 60px">#</th>
                        <th scope="col">Course Details</th>
                        <th scope="col" class="text-center">Duration</th>
                        <th scope="col" class="text-end">Fee</th>
                        <th scope="col" class="text-center">Status</th>
                        <th scope="col" class="text-end" style="width: 120px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses as $course)
                        <tr>
                            <td>{{ $course->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="fas fa-book text-primary me-2"></span>
                                    <div>
                                        <div class="fw-medium">{{ $course->name }}</div>
                                        <div class="text-muted small">Code: {{ $course->code }}</div>
                                        <div class="mt-1">
                                            <a href="{{ route('admin.courses.subjects.index', $course) }}" 
                                               class="btn btn-link btn-sm text-primary p-0">
                                                <i class="fas fa-book-open me-1"></i>
                                                <span>View Disciplines</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                @if($course->duration_months)
                                    <span class="badge bg-info">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ $course->duration_months }} Months
                                    </span>
                                @else
                                    <span class="badge bg-secondary">N/A</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="fw-medium">${{ number_format($course->fee, 2) }}</div>
                            </td>
                            <td class="text-center">
                                @if($course->status === 'active')
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i>Active
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        <i class="fas fa-times-circle me-1"></i>Inactive
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.courses.show', $course) }}"
                                        class="btn btn-sm btn-outline-info"
                                        title="View Course Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.courses.edit', $course) }}"
                                        class="btn btn-sm btn-outline-primary"
                                        title="Edit Course">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.courses.destroy', $course) }}"
                                        method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="btn btn-sm btn-outline-danger"
                                            title="Delete Course"
                                            onclick="return confirm('Are you sure you want to delete this course?')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center justify-content-center">
                                    <i class="fas fa-graduation-cap fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted mb-0">No Courses Found</h5>
                                    <p class="text-muted mb-3">Get started by adding your first course</p>
                                    <a href="{{ route('admin.courses.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus-circle me-1"></i>Add Course
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('styles')
<style>
    .table > :not(caption) > * > * {
        padding: 1rem 1rem;
    }
    .btn-outline-primary:hover i, 
    .btn-outline-danger:hover i,
    .btn-outline-info:hover i {
        color: white;
    }
    .dataTables_filter {
        margin-bottom: 1rem;
    }
    .dataTables_filter label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        float: right;
    }
    .dataTables_filter input {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
        outline: none;
    }
    .dataTables_filter input:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
</style>
@endpush

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
@endsection