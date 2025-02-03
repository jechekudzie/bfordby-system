@extends('layouts.admin')

@push('head')
<!-- Datatable CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" />
@endpush

@section('content')
<div class="card">
    <div class="card-header">
        <div class="row flex-between-end">
            <div class="col-auto align-self-center">
                <h5 class="mb-0">Qualification Levels</h5>
            </div>
            <div class="col-auto ms-auto">
                <a href="{{ route('admin.utilities.index') }}" class="btn btn-secondary me-2">Back to Utilities</a>
                <a href="{{ route('admin.qualification-levels.create') }}" class="btn btn-primary">Add New Qualification Level</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="buttons-datatables" class="table table-bordered table-striped fs--1 mb-0">
                <thead class="bg-200 text-900">
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="list">
                    @foreach($qualificationLevels as $level)
                    <tr>
                        <td>{{ $level->name }}</td>
                        <td>{{ $level->description }}</td>
                        <td>
                            <a href="{{ route('admin.qualification-levels.edit', $level) }}" class="btn btn-sm btn-primary">Edit</a>
                            <form action="{{ route('admin.qualification-levels.destroy', $level) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
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
