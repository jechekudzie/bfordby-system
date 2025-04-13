@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0">
            <i class="fas fa-users text-primary me-2"></i>Next of Kin
        </h6>
        <a href="{{ route('students.next-of-kin.create', $student) }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i>Add Next of Kin
        </a>
    </div>
    <div class="card-body">
        @if($student->nextOfKins->count() > 0)
            <div class="table-responsive">
                <table class="table table-borderless">
                    <thead>
                        <tr>
                            <th class="text-muted">
                                <i class="fas fa-user text-primary me-2"></i>Name
                            </th>
                            <th class="text-muted">
                                <i class="fas fa-heart text-primary me-2"></i>Relationship
                            </th>
                            <th class="text-muted">
                                <i class="fas fa-phone text-primary me-2"></i>Contact
                            </th>
                            <th class="text-muted">
                                <i class="fas fa-map-marker-alt text-primary me-2"></i>Address
                            </th>
                            <th class="text-muted">
                                <i class="fas fa-star text-primary me-2"></i>Emergency Contact
                            </th>
                            <th class="text-muted" width="15%">
                                <i class="fas fa-cog text-primary me-2"></i>Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($student->nextOfKins as $nextOfKin)
                        <tr>
                            <td>{{ $nextOfKin->full_name }}</td>
                            <td>{{ $nextOfKin->relationship }}</td>
                            <td>
                                @if($nextOfKin->phone_number)
                                    <div><i class="fas fa-phone me-1"></i>{{ $nextOfKin->phone_number }}</div>
                                @endif
                                @if($nextOfKin->email)
                                    <div><i class="fas fa-envelope me-1"></i>{{ $nextOfKin->email }}</div>
                                @endif
                            </td>
                            <td>
                                @if($nextOfKin->address_line1)
                                    {{ $nextOfKin->address_line1 }}
                                    @if($nextOfKin->address_line2)
                                        <br>{{ $nextOfKin->address_line2 }}
                                    @endif
                                    <br>
                                    {{ collect([$nextOfKin->city, $nextOfKin->state, $nextOfKin->zip_code])->filter()->join(', ') }}
                                    @if($nextOfKin->country)
                                        <br>{{ $nextOfKin->country->name }}
                                    @endif
                                @else
                                    <span class="text-muted">No address provided</span>
                                @endif
                            </td>
                            <td>
                                @if($nextOfKin->is_emergency_contact)
                                    <span class="badge bg-success">
                                        <i class="fas fa-check me-1"></i>Yes
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-times me-1"></i>No
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('students.next-of-kin.edit', ['student' => $student, 'next_of_kin' => $nextOfKin]) }}"
                                        class="btn btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-outline-danger"
                                        onclick="confirmDelete('{{ route('students.next-of-kin.destroy', ['student' => $student, 'next_of_kin' => $nextOfKin]) }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-4">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <p class="text-muted">No next of kin records found for this student.</p>
                <a href="{{ route('students.next-of-kin.create', $student) }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>Add First Next of Kin
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('modals')
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this next of kin record?
            </div>
            <div class="modal-footer">
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
    function confirmDelete(deleteUrl) {
        document.getElementById('deleteForm').action = deleteUrl;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }
</script>
@endpush 