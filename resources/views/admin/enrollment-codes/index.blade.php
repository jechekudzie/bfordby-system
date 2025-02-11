@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">
                    <i class="fas fa-qrcode me-1 text-primary"></i>Enrollment Codes
                </h5>
                <p class="mb-0 text-muted small">Manage enrollment codes for courses</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.utilities.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-1"></i>Back to Utilities
                </a>
                <a href="{{ route('admin.enrollment-codes.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-1"></i>Add Enrollment Code
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

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <div class="d-flex">
                    <i class="fas fa-exclamation-circle me-2 mt-1"></i>
                    <div>{{ session('error') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover align-middle" id="enrollmentCodesTable">
                <thead class="bg-light">
                    <tr>
                        <th scope="col" style="width: 60px">#</th>
                        <th scope="col">Code Details</th>
                        <th scope="col">Course</th>
                        <th scope="col">Study Mode</th>
                        <th scope="col" class="text-center">Current Number</th>
                        <th scope="col" class="text-end" style="width: 120px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($enrollmentCodes as $code)
                        <tr>
                            <td>{{ $code->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="fas fa-qrcode text-primary me-2"></span>
                                    <div>
                                        <div class="fw-medium">{{ $code->base_code }}</div>
                                        <div class="fw-medium" style="color: #0d6efd;">
                                            <i class="fas fa-calendar-alt me-1"></i>
                                            Year {{ $code->year }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="fas fa-graduation-cap text-primary me-2"></span>
                                    <div>{{ $code->course->name }}</div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="fas fa-clock text-primary me-2"></span>
                                    <div>{{ $code->studyMode->name }}</div>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info">
                                    <i class="fas fa-hashtag me-1"></i>
                                    {{ $code->current_number }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.enrollment-codes.edit', $code) }}" 
                                       class="btn btn-sm btn-outline-primary"
                                       title="Edit Enrollment Code">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($code->current_number == 0)
                                        <form action="{{ route('admin.enrollment-codes.destroy', $code) }}" 
                                              method="POST"
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-outline-danger" 
                                                    title="Delete Enrollment Code"
                                                    onclick="return confirm('Are you sure you want to delete this enrollment code?')">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center justify-content-center">
                                    <i class="fas fa-qrcode fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted mb-0">No Enrollment Codes Found</h5>
                                    <p class="text-muted mb-3">Get started by adding your first enrollment code</p>
                                    <a href="{{ route('admin.enrollment-codes.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus-circle me-1"></i>Add Enrollment Code
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($enrollmentCodes->hasPages())
            <div class="d-flex justify-content-end mt-4">
                {{ $enrollmentCodes->links() }}
            </div>
        @endif
    </div>
</div>

@push('styles')
<style>
    .table > :not(caption) > * > * {
        padding: 1rem 1rem;
    }
    .btn-outline-primary:hover i, 
    .btn-outline-danger:hover i {
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
<!-- <script>
    $(document).ready(function() {
        $('#enrollmentCodesTable').DataTable({
            dom: '<"d-flex justify-content-end"f>rt<"d-flex justify-content-between"lip>',
            language: {
                search: '<i class="fas fa-search text-muted me-2"></i>',
                searchPlaceholder: 'Search enrollment codes...'
            },
            pageLength: 10,
            ordering: true,
            info: true,
            lengthChange: true,
            columns: [
                { data: 'id' },
                { data: 'code' },
                { data: 'course' },
                { data: 'study_mode' },
                { data: 'current_number' },
                { data: 'actions', orderable: false }
            ]
        });
    });
</script> -->
@endpush
@endsection 