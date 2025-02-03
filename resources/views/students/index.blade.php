@extends('layouts.admin')

@push('head')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" />
@endpush

@section('content')
<div class="card mb-3">
    <div class="card-header">
        <h5 class="mb-0">Student Enrollment</h5>
        <a href="{{ route('students.create') }}" class="btn btn-primary btn-sm float-end">
            <span class="fas fa-plus me-1"></span> Enroll Student
        </a>
    </div>
    <div class="card-body bg-light">
        <div class="table-responsive">
            <table id="students-table" class="table table-bordered table-striped fs--1 mb-0">
                <thead class="bg-200 text-900">
                    <tr>
                        <th>Full Name</th>
                        <th>Date of Birth</th>
                        <th>Gender</th>
                        <th>Status</th>
                        <th>Enrollment Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                        <tr>
                            <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                            <td>{{ \Carbon\Carbon::parse($student->date_of_birth)->format('d/m/Y') }}</td>
                            <td>{{ $student->gender->name ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-{{ $student->status == 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($student->status) }}
                                </span>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($student->enrollment_date)->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('students.show', $student->slug) }}" class="btn btn-falcon-info btn-sm">
                                    <span class="fas fa-eye"></span>
                                </a>
                                <a href="{{ route('students.edit', $student->slug) }}" class="btn btn-falcon-primary btn-sm">
                                    <span class="fas fa-edit"></span>
                                </a>
                                <form action="{{ route('students.destroy', $student->slug) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-falcon-danger btn-sm" onclick="return confirm('Are you sure?')">
                                        <span class="fas fa-trash"></span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No students found.</td>
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
        $('#students-table').DataTable({
            responsive: true,
            autoWidth: false,
            order: [[0, 'asc']]
        });
    });
</script>
@endpush
