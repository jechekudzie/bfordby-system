@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Sick Notes</h5>
                    <a href="{{ route('students.health.sick-notes.create', $student) }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Sick Note
                    </a>
                </div>
                <div class="card-body">
                    @if($sickNotes->isEmpty())
                        <div class="text-center py-4">
                            <i class="fas fa-file-medical fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No sick notes found for this student.</p>
                        </div>
                    @else
                        <div class="row">
                            @foreach($sickNotes as $note)
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <div>
                                                    <h6 class="card-title mb-1">{{ $note->diagnosis }}</h6>
                                                    <p class="text-muted small mb-0">
                                                        Issued: {{ $note->issue_date->format('M d, Y') }}
                                                    </p>
                                                </div>
                                                <span class="badge {{ $note->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ $note->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </div>
                                            <div class="mb-3">
                                                <p class="small text-muted mb-1">
                                                    <i class="fas fa-calendar-alt"></i>
                                                    Valid: {{ $note->valid_from->format('M d, Y') }} - {{ $note->valid_until->format('M d, Y') }}
                                                </p>
                                                <p class="small text-muted mb-1">
                                                    <i class="fas fa-user-md"></i>
                                                    Doctor: {{ $note->issuing_doctor }}
                                                </p>
                                                <p class="small text-muted mb-0">
                                                    <i class="fas fa-hospital"></i>
                                                    Facility: {{ $note->medical_facility }}
                                                </p>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="btn-group">
                                                    <a href="{{ route('students.health.sick-notes.show', [$student, $note]) }}" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i> View
                                                    </a>
                                                    <a href="{{ route('students.health.sick-notes.edit', [$student, $note]) }}" 
                                                       class="btn btn-sm btn-outline-secondary">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                </div>
                                                @if($note->document_path)
                                                    <a href="{{ route('students.health.sick-notes.download', [$student, $note]) }}" 
                                                       class="btn btn-sm btn-outline-success">
                                                        <i class="fas fa-download"></i> Download
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 