@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ isset($sickNote) ? 'Edit Sick Note' : 'Add Sick Note' }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ isset($sickNote) 
                        ? route('students.health.sick-notes.update', [$student, $sickNote]) 
                        : route('students.health.sick-notes.store', $student) }}" 
                          method="POST" 
                          enctype="multipart/form-data">
                        @csrf
                        @if(isset($sickNote))
                            @method('PUT')
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="issue_date" class="form-label">Issue Date</label>
                                    <input type="date" 
                                           class="form-control @error('issue_date') is-invalid @enderror" 
                                           id="issue_date" 
                                           name="issue_date" 
                                           value="{{ old('issue_date', isset($sickNote) ? $sickNote->issue_date->format('Y-m-d') : '') }}" 
                                           required>
                                    @error('issue_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="valid_from" class="form-label">Valid From</label>
                                    <input type="date" 
                                           class="form-control @error('valid_from') is-invalid @enderror" 
                                           id="valid_from" 
                                           name="valid_from" 
                                           value="{{ old('valid_from', isset($sickNote) ? $sickNote->valid_from->format('Y-m-d') : '') }}" 
                                           required>
                                    @error('valid_from')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="valid_until" class="form-label">Valid Until</label>
                                    <input type="date" 
                                           class="form-control @error('valid_until') is-invalid @enderror" 
                                           id="valid_until" 
                                           name="valid_until" 
                                           value="{{ old('valid_until', isset($sickNote) ? $sickNote->valid_until->format('Y-m-d') : '') }}" 
                                           required>
                                    @error('valid_until')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="diagnosis" class="form-label">Diagnosis</label>
                                    <input type="text" 
                                           class="form-control @error('diagnosis') is-invalid @enderror" 
                                           id="diagnosis" 
                                           name="diagnosis" 
                                           value="{{ old('diagnosis', $sickNote->diagnosis ?? '') }}" 
                                           required>
                                    @error('diagnosis')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Additional Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" 
                                      name="notes" 
                                      rows="3">{{ old('notes', $sickNote->notes ?? '') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="issuing_doctor" class="form-label">Issuing Doctor</label>
                                    <input type="text" 
                                           class="form-control @error('issuing_doctor') is-invalid @enderror" 
                                           id="issuing_doctor" 
                                           name="issuing_doctor" 
                                           value="{{ old('issuing_doctor', $sickNote->issuing_doctor ?? '') }}" 
                                           required>
                                    @error('issuing_doctor')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="medical_facility" class="form-label">Medical Facility</label>
                                    <input type="text" 
                                           class="form-control @error('medical_facility') is-invalid @enderror" 
                                           id="medical_facility" 
                                           name="medical_facility" 
                                           value="{{ old('medical_facility', $sickNote->medical_facility ?? '') }}" 
                                           required>
                                    @error('medical_facility')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="document" class="form-label">Upload Document</label>
                            <input type="file" 
                                   class="form-control @error('document') is-invalid @enderror" 
                                   id="document" 
                                   name="document" 
                                   accept=".pdf,.jpg,.jpeg,.png">
                            @error('document')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if(isset($sickNote) && $sickNote->document_path)
                                <div class="mt-2">
                                    <small class="text-muted">Current document: 
                                        <a href="{{ route('students.health.sick-notes.download', [$student, $sickNote]) }}">
                                            Download
                                        </a>
                                    </small>
                                </div>
                            @endif
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('students.health.sick-notes.index', $student) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> {{ isset($sickNote) ? 'Update' : 'Save' }} Sick Note
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 