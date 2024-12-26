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
                <h5 class="mb-0">Equipment Management</h5>
            </div>
            <div class="col-auto ms-auto">
                <a href="{{ route('equipment.create') }}" class="btn btn-primary btn-sm">
                    <span class="fas fa-plus me-1"></span> Add Equipment
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
                        <th>Category</th>
                        <th>Status</th>
                        <th>Serial Number</th>
                        <th>Asset Number</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($equipment as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->category->name }}</td>
                            <td>
                                <span class="badge bg-{{ $item->status === 'available' ? 'success' :
                                    ($item->status === 'in_use' ? 'warning' :
                                    ($item->status === 'maintenance' ? 'info' : 'secondary')) }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>
                            <td>{{ $item->serial_number ?? 'N/A' }}</td>
                            <td>{{ $item->asset_number ?? 'N/A' }}</td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('equipment.show', $item) }}"
                                       class="btn btn-falcon-info"
                                       title="View">
                                        <span class="fas fa-eye"></span>
                                    </a>
                                    <a href="{{ route('equipment.edit', $item) }}"
                                       class="btn btn-falcon-primary"
                                       title="Edit">
                                        <span class="fas fa-edit"></span>
                                    </a>
                                    <form action="{{ route('equipment.destroy', $item) }}"
                                          method="POST"
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-falcon-danger"
                                                title="Delete"
                                                onclick="return confirm('Are you sure you want to delete this equipment?')">
                                            <span class="fas fa-trash"></span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No equipment found.</td>
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
    $(document).ready(function () {
        $('#buttons-datatables').DataTable();
    });
</script>
@endpush
