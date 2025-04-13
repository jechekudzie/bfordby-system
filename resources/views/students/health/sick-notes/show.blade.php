@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Sick Note Details</h5>
                    <div class="btn-group">
                        <a href="{{ route('students.health.sick-notes.edit', [$student, $sickNote]) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('students.health.sick-notes.index', $student) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h6 class="text-muted mb-3">Basic Information</h6>
                                <div class="table-responsive">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th style="width: 150px;">Status</th>
                                            <td>
                                                <span class="badge {{ $sickNote->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ $sickNote->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Issue Date</th>
                                            <td>{{ $sickNote->issue_date->format('M d, Y') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Valid Period</th>
                                            <td>
                                                {{ $sickNote->valid_from->format('M d, Y') }} - 
                                                {{ $sickNote->valid_until->format('M d, Y') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Diagnosis</th>
                                            <td>{{ $sickNote->diagnosis }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-4">
                                <h6 class="text-muted mb-3">Medical Information</h6>
                                <div class="table-responsive">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th style="width: 150px;">Issuing Doctor</th>
                                            <td>{{ $sickNote->issuing_doctor }}</td>
                                        </tr>
                                        <tr>
                                            <th>Medical Facility</th>
                                            <td>{{ $sickNote->medical_facility }}</td>
                                        </tr>
                                        <tr>
                                            <th>Related Health Record</th>
                                            <td>
                                                <a href="{{ route('students.health.show', [$student, $sickNote->healthRecord]) }}">
                                                    {{ $sickNote->healthRecord->date->format('M d, Y') }} - 
                                                    {{ $sickNote->healthRecord->type }}
                                                </a>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($sickNote->notes)
                        <div class="mb-4">
                            <h6 class="text-muted mb-3">Additional Notes</h6>
                            <div class="card">
                                <div class="card-body bg-light">
                                    {{ $sickNote->notes }}
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($sickNote->document_path)
                        <div class="mb-4">
                            <h6 class="text-muted mb-3">Attached Document</h6>
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-file-medical fa-2x text-primary me-3"></i>
                                        <div>
                                            <h6 class="mb-1">Medical Document</h6>
                                            <p class="text-muted mb-0">
                                                <small>Uploaded on {{ $sickNote->created_at->format('M d, Y H:i') }}</small>
                                            </p>
                                        </div>
                                        <div class="ms-auto">
                                            <a href="{{ route('students.health.sick-notes.download', [$student, $sickNote]) }}" 
                                               class="btn btn-primary">
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="mt-4">
                        <form action="{{ route('students.health.sick-notes.destroy', [$student, $sickNote]) }}" 
                              method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this sick note?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash"></i> Delete Sick Note
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 