@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <div class="card border-0 shadow-sm mb-4 bg-gradient-header">
        <div class="card-body py-4">
            <div class="row">
                <div class="col-md-8">
                    <h3 class="mb-2 text-white fw-bold">
                        <i class="fas fa-file-alt me-2"></i>Simplified Transcript
                        <span class="badge ms-2" style="background-color: #FBD801; color: #154832;">Concise View</span>
                    </h3>
                    <p class="text-white-50 mb-0">
                        Simplified record of academic progress and achievements across all enrolled modules.
                    </p>
                </div>
                <div class="col-md-4 text-end d-flex align-items-center justify-content-end">
                    @if($student->enrollments->count() > 0)
                        <a href="{{ route('students.enrollments.show', ['student' => $student, 'enrollment' => $student->enrollments->first()]) }}" class="btn me-2" style="background-color: #FBD801; color: #154832; font-weight: 500;">
                            <i class="fas fa-arrow-left me-2"></i>Back to Enrollment
                        </a>
                    @else
                        <a href="{{ route('students.show', $student) }}" class="btn me-2" style="background-color: #FBD801; color: #154832; font-weight: 500;">
                            <i class="fas fa-arrow-left me-2"></i>Back to Student
                        </a>
                    @endif
                    <a href="{{ route('students.transcript.simplified.download', $student) }}" class="btn btn-light">
                        <i class="fas fa-download me-2"></i>Download PDF
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header" style="background-color: #FBD801; color: #154832;">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-user-graduate me-2"></i>Student Information
                </h5>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Name</dt>
                        <dd class="col-sm-8 fw-bold">{{ $student->first_name }} {{ $student->last_name }}</dd>
                        
                        <dt class="col-sm-4">Student ID</dt>
                        <dd class="col-sm-8">{{ $student->student_number }}</dd>
                    </dl>
                </div>
                <div class="col-md-6">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Program</dt>
                        <dd class="col-sm-8">{{ $student->enrollments->first()->course->name ?? 'N/A' }}</dd>
                        
                        <dt class="col-sm-4">Date</dt>
                        <dd class="col-sm-8">{{ date('F j, Y') }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    @if(count($transcriptData) === 0)
        <div class="alert" style="background-color: rgba(251, 216, 1, 0.2); border-left: 4px solid #FBD801;">
            <i class="fas fa-info-circle text-brand-primary me-2"></i>
            <span>No academic records found. Please contact your advisor if you believe this is an error.</span>
        </div>
    @endif

    @foreach($transcriptData as $trimester)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-brand-primary text-white py-3">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle me-3" style="background-color: #FBD801; height: 28px; width: 28px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-calendar-alt" style="color: #154832;"></i>
                    </div>
                    <h5 class="mb-0 text-white">
                        Year {{ $trimester['semester']->academic_year }} - {{ $trimester['semester']->name }}
                    </h5>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0 transcript-table">
                        <thead style="background-color: rgba(251, 216, 1, 0.15);">
                            <tr>
                                <th class="ps-3">Module</th>
                                <th class="text-center">Status</th>
                                <th class="text-center pe-3">Grade</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($trimester['subjects'] as $subject)
                                <tr class="subject-row">
                                    <td colspan="3" class="fw-bold ps-3" style="background-color: rgba(251, 216, 1, 0.3); color: #154832;">
                                        {{ $subject['subject']->name }}
                                    </td>
                                </tr>
                                
                                @foreach($subject['modules'] as $module)
                                    <tr>
                                        <td class="ps-3 py-3">
                                            <div class="d-flex align-items-start">
                                                <div class="me-3">
                                                    <div class="rounded-circle bg-brand-primary-light p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                        <i class="fas fa-book text-brand-primary"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1 text-brand-primary">{{ $module['module']->name }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center align-middle">
                                            @php
                                                $isComplete = $module['grade'] !== null;
                                            @endphp
                                            
                                            @if($isComplete)
                                                <span class="badge badge-brand-primary">Completed</span>
                                            @else
                                                <span class="badge bg-secondary">Incomplete</span>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle pe-3">
                                            @if($isComplete)
                                                <span class="badge {{ $module['grade'] >= 50 ? 'badge-success' : 'badge-danger' }}">
                                                    {{ $module['grade_classification'] }}
                                                </span>
                                            @else
                                                <span class="text-muted">--</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endforeach

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header" style="background-color: #FBD801; color: #154832;">
            <h5 class="mb-0 fw-bold">
                <i class="fas fa-info-circle me-2"></i>Grade Classification
            </h5>
        </div>
        <div class="card-body py-4">
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="classification-item d-flex align-items-center mb-3">
                        <div class="classification-badge me-3">
                            <span class="badge p-2" style="background-color: #FBD801; color: #154832;">Distinction</span>
                        </div>
                        <div>
                            <h6 class="mb-0">75% and above</h6>
                            <small class="text-muted">Exceptional achievement demonstrating outstanding knowledge</small>
                        </div>
                    </div>
                    <div class="classification-item d-flex align-items-center mb-3">
                        <div class="classification-badge me-3">
                            <span class="badge p-2" style="background-color: #FBD801; color: #154832;">Merit</span>
                        </div>
                        <div>
                            <h6 class="mb-0">65% - 74%</h6>
                            <small class="text-muted">Very good achievement with thorough understanding</small>
                        </div>
                    </div>
                    <div class="classification-item d-flex align-items-center mb-3">
                        <div class="classification-badge me-3">
                            <span class="badge p-2" style="background-color: #FBD801; color: #154832;">Credit</span>
                        </div>
                        <div>
                            <h6 class="mb-0">60% - 64%</h6>
                            <small class="text-muted">Good achievement with sound understanding</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="classification-item d-flex align-items-center mb-3">
                        <div class="classification-badge me-3">
                            <span class="badge p-2" style="background-color: #FBD801; color: #154832;">Pass</span>
                        </div>
                        <div>
                            <h6 class="mb-0">50% - 59%</h6>
                            <small class="text-muted">Satisfactory achievement meeting minimum requirements</small>
                        </div>
                    </div>
                    <div class="classification-item d-flex align-items-center mb-3">
                        <div class="classification-badge me-3">
                            <span class="badge bg-danger p-2">Fail</span>
                        </div>
                        <div>
                            <h6 class="mb-0">Below 50%</h6>
                            <small class="text-muted">Insufficient achievement, remedial work required</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="alert" style="background-color: rgba(251, 216, 1, 0.15); border-left: 4px solid #FBD801;">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="fas fa-exclamation-triangle" style="color: #FBD801;"></i>
                    </div>
                    <div>
                        <p class="mb-0 fw-bold">Official Verification</p>
                        <p class="mb-0 small">This transcript is unofficial unless signed by an authorized officer of the institution.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Brand Colors */
:root {
    --brand-primary: #154832;
    --brand-primary-light: rgba(21, 72, 50, 0.15);
    --brand-primary-lighter: rgba(21, 72, 50, 0.05);
    --brand-secondary: #FBD801;
    --brand-secondary-light: rgba(251, 216, 1, 0.2);
    --brand-secondary-lighter: rgba(251, 216, 1, 0.1);
}

/* Add custom styles for the transcript */
.bg-gradient-header {
    background: linear-gradient(45deg, var(--brand-primary), #1d6446);
}

.badge-brand-primary {
    background-color: var(--brand-primary);
    color: white;
}

.badge-success {
    background-color: #FBD801;
    color: #154832;
}

.badge-danger {
    background-color: #dc3545;
    color: white;
}

.text-brand-primary {
    color: var(--brand-primary);
}

/* Table styling */
.transcript-table {
    border-collapse: separate;
    border-spacing: 0;
}

.transcript-table tr:nth-child(even):not(.subject-row) {
    background-color: rgba(21, 72, 50, 0.03);
}

.classification-badge .badge {
    min-width: 100px;
    text-align: center;
}

/* Add smooth transitions */
.badge, .btn, .card {
    transition: all 0.2s ease-in-out;
}

.badge:hover, .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}
</style>
@endsection 