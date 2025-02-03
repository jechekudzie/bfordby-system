@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Student Contacts</h5>
            <a href="{{ route('student.contact.create', $student) }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> Add Contact
            </a>
        </div>
        <div class="card-body">
            @if($student->contacts->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Value</th>
                                <th>Primary</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($student->contacts as $contact)
                            <tr>
                                <td>{{ $contact->contactType->name }}</td>
                                <td>{{ $contact->value }}</td>
                                <td>
                                    <span class="badge bg-{{ $contact->is_primary ? 'success' : 'secondary' }}">
                                        {{ $contact->is_primary ? 'Yes' : 'No' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('student.contact.edit', $contact) }}" class="btn btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger" 
                                                onclick="confirmDelete('{{ route('students.contacts.destroy', $contact) }}')">
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
                    <i class="fas fa-address-book fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No contacts found for this student.</p>
                    <a href="{{ route('student.contact.create', $student) }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Add First Contact
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
@include('components.delete-modal')
@endsection

@push('scripts')
<script>
function confirmDelete(deleteUrl) {
    $('#deleteModal').modal('show');
    $('#deleteForm').attr('action', deleteUrl);
}
</script>
@endpush
