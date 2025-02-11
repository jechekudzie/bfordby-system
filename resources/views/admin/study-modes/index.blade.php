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
                    <i class="fas fa-clock me-1 text-primary"></i>Study Modes
                </h5>
                <p class="mb-0 text-muted small">Manage available study modes</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.utilities.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-1"></i>Back to Utilities
                </a>
                <a href="{{ route('admin.study-modes.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-1"></i>Add Study Mode
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
            <table class="table table-hover align-middle" id="studyModesTable">
                <thead class="bg-light">
                    <tr>
                        <th scope="col" style="width: 60px">#</th>
                        <th scope="col">Study Mode Details</th>
                        <th scope="col" class="text-end" style="width: 120px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($studyModes as $studyMode)
                        <tr>
                            <td>{{ $studyMode->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="fas fa-clock text-primary me-2"></span>
                                    <div>
                                        <div class="fw-medium">{{ $studyMode->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.study-modes.edit', $studyMode) }}" 
                                       class="btn btn-sm btn-outline-primary"
                                       title="Edit Study Mode">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.study-modes.destroy', $studyMode) }}" 
                                          method="POST"
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-outline-danger" 
                                                title="Delete Study Mode"
                                                onclick="return confirm('Are you sure you want to delete this study mode?')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center justify-content-center">
                                    <i class="fas fa-clock fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted mb-0">No Study Modes Found</h5>
                                    <p class="text-muted mb-3">Get started by adding your first study mode</p>
                                    <a href="{{ route('admin.study-modes.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus-circle me-1"></i>Add Study Mode
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

@push('scripts')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script>
    $(document).ready(function () {
        $('#buttons-datatables').DataTable();
    });
</script>
@endpush



@endsection