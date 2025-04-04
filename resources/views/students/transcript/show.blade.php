@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <div class="card border-0 shadow-sm mb-4 bg-gradient-header">
        <div class="card-body py-4">
            <div class="row">
                <div class="col-md-8">
                    <h3 class="mb-2 text-white fw-bold">
                        <i class="fas fa-file-alt me-2"></i>Academic Transcript
                    </h3>
                    <p class="text-white-50 mb-0">
                        Comprehensive record of academic progress and achievements across all enrolled modules.
                    </p>
                </div>
                <div class="col-md-4 text-end d-flex align-items-center justify-content-end">
                    @if($student->enrollments->count() > 0)
                        <a href="{{ route('students.enrollments.show', ['student' => $student, 'enrollment' => $student->enrollments->first()]) }}" class="btn btn-brand-secondary me-2">
                            <i class="fas fa-arrow-left me-2"></i>Back to Enrollment
                        </a>
                    @else
                        <a href="{{ route('students.show', $student) }}" class="btn btn-brand-secondary me-2">
                            <i class="fas fa-arrow-left me-2"></i>Back to Student
                        </a>
                    @endif
                    <a href="{{ route('students.transcript.download', $student) }}" class="btn btn-light">
                        <i class="fas fa-download me-2"></i>Download PDF
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-brand-primary">
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
        <div class="alert bg-brand-secondary-light">
            <i class="fas fa-info-circle text-brand-primary me-2"></i>
            <span>No academic records found. Please contact your advisor if you believe this is an error.</span>
        </div>
    @endif

    @foreach($transcriptData as $trimester)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-brand-primary text-white py-3 semester-header">
                <h5 class="mb-0 text-white">
                    <i class="fas fa-calendar-alt me-2"></i>
                    Year {{ $trimester['semester']->academic_year }} - {{ $trimester['semester']->name }}
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-3">Module</th>
                                <th>Assessments</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Grade</th>
                                <th class="text-center pe-3">Classification</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($trimester['subjects'] as $subject)
                                <tr class="subject-row">
                                    <td colspan="5" class="fw-bold ps-3">
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
                                                    <span class="text-muted small">{{ count($module['assessments']) }} Assessment(s)</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column gap-2">
                                                @foreach($module['assessments'] as $assessment)
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span class="small">
                                                            {{ $assessment['assessment']->name }}
                                                        </span>
                                                        <span class="badge {{ $assessment['graded'] ? 'badge-brand-primary' : 'bg-secondary' }}">
                                                            @if($assessment['graded'])
                                                                {{ number_format($assessment['grade'], 1) }}%
                                                            @elseif($assessment['submitted'])
                                                                Submitted
                                                            @else
                                                                Not Submitted
                                                            @endif
                                                        </span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="text-center align-middle">
                                            @php
                                                $allAssessed = true;
                                                $allSubmitted = true;
                                                
                                                foreach($module['assessments'] as $assessment) {
                                                    if(!$assessment['graded']) {
                                                        $allAssessed = false;
                                                    }
                                                    if(!$assessment['submitted']) {
                                                        $allSubmitted = false;
                                                    }
                                                }
                                            @endphp
                                            
                                            @if($allAssessed)
                                                <span class="badge badge-brand-primary">Completed</span>
                                            @elseif($allSubmitted)
                                                <span class="badge badge-brand-secondary">Pending Grade</span>
                                            @else
                                                <span class="badge bg-secondary">Incomplete</span>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle fw-bold">
                                            @if($allAssessed)
                                                {{ number_format($module['grade'], 1) }}%
                                            @elseif($allSubmitted)
                                                <span class="text-muted">--</span>
                                            @else
                                                <span class="text-muted">--</span>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle pe-3">
                                            @if($allAssessed)
                                                <span class="badge {{ $module['grade'] >= 50 ? 'badge-brand-primary' : 'bg-danger' }}">
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
        <div class="card-header bg-light">
            <h5 class="mb-0 text-brand-primary">
                <i class="fas fa-info-circle me-2"></i>Grade Classification
            </h5>
        </div>
        <div class="card-body py-4">
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="classification-item d-flex align-items-center mb-3">
                        <div class="classification-badge me-3">
                            <span class="badge badge-brand-primary p-2">Distinction</span>
                        </div>
                        <div>
                            <h6 class="mb-0">75% and above</h6>
                            <small class="text-muted">Exceptional achievement demonstrating outstanding knowledge</small>
                        </div>
                    </div>
                    <div class="classification-item d-flex align-items-center mb-3">
                        <div class="classification-badge me-3">
                            <span class="badge badge-brand-primary p-2">Merit</span>
                        </div>
                        <div>
                            <h6 class="mb-0">65% - 74%</h6>
                            <small class="text-muted">Very good achievement with thorough understanding</small>
                        </div>
                    </div>
                    <div class="classification-item d-flex align-items-center mb-3">
                        <div class="classification-badge me-3">
                            <span class="badge badge-brand-primary p-2">Credit</span>
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
                            <span class="badge badge-brand-primary p-2">Pass</span>
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
            <div class="alert bg-brand-secondary-light mb-0">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="fas fa-exclamation-triangle text-brand-primary fa-lg"></i>
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
    --text-on-primary: white;
    --text-on-secondary: #154832;
}

/* Card styling */
.card {
    border-radius: 10px;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 4px 8px rgba(0,0,0,0.05) !important;
}

.card:hover {
    box-shadow: 0 6px 12px rgba(0,0,0,0.1) !important;
}

.card-header {
    border-bottom: none;
}

/* Header gradient background */
.bg-gradient-header {
    background: linear-gradient(135deg, #154832, #1d7a54);
    color: white;
}

/* Table styling */
.table-striped > tbody > tr:nth-of-type(odd) {
    background-color: var(--brand-primary-lighter);
}

.table-hover tbody tr:hover {
    background-color: var(--brand-secondary-lighter);
}

.table {
    margin-bottom: 0;
}

.table th {
    font-weight: 600;
    color: var(--brand-primary);
}

/* Semester headers */
.semester-header {
    position: relative;
    overflow: hidden;
}

.semester-header:after {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 150px;
    height: 100%;
    background: linear-gradient(90deg, rgba(21, 72, 50, 0), rgba(255, 255, 255, 0.1));
    z-index: 1;
}

.bg-brand-primary {
    background: linear-gradient(135deg, #154832, #1a5f42);
    color: var(--text-on-primary);
}

.bg-brand-secondary {
    background: linear-gradient(135deg, #FBD801, #f8d201);
    color: var(--text-on-secondary);
}

.bg-brand-primary-light {
    background-color: var(--brand-primary-light);
    color: var(--brand-primary);
}

.bg-brand-secondary-light {
    background-color: var(--brand-secondary-light);
    color: var(--text-on-secondary);
}

.text-brand-primary {
    color: var(--brand-primary);
}

.border-brand-primary {
    border-color: var(--brand-primary);
}

/* Button styles */
.btn-brand-primary {
    background: linear-gradient(135deg, #154832, #1a5f42);
    color: var(--text-on-primary);
    border: none;
    transition: all 0.3s ease;
}

.btn-brand-primary:hover {
    background: linear-gradient(135deg, #1a5f42, #154832);
    color: var(--text-on-primary);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.btn-brand-secondary {
    background: linear-gradient(135deg, #FBD801, #f8d201);
    color: var(--text-on-secondary);
    border: none;
    transition: all 0.3s ease;
}

.btn-brand-secondary:hover {
    background: linear-gradient(135deg, #f8d201, #FBD801);
    color: var(--text-on-secondary);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

/* Badge styles */
.badge-brand-primary {
    background: linear-gradient(135deg, #154832, #1a5f42);
    color: var(--text-on-primary);
    font-weight: 500;
    padding: 5px 8px;
}

.badge-brand-secondary {
    background: linear-gradient(135deg, #FBD801, #f8d201);
    color: var(--text-on-secondary);
    font-weight: 500;
    padding: 5px 8px;
}

/* Override bootstrap styles */
.bg-opacity-10 {
    background-color: var(--brand-primary) !important;
    opacity: 0.1;
}

/* Subject heading */
tr.subject-row {
    background: linear-gradient(135deg, var(--brand-secondary), #ffe43b);
}

tr.subject-row td {
    color: var(--brand-primary);
    padding: 12px;
    font-weight: 600;
    font-size: 1.05rem;
    border-left: 4px solid var(--brand-primary);
}

tr.bg-brand-primary.bg-opacity-10 td {
    background: linear-gradient(135deg, rgba(21, 72, 50, 0.8), rgba(26, 95, 66, 0.8));
    color: white !important;
    padding: 10px;
    font-weight: 600;
}

/* Module icon styling */
.rounded-circle.bg-brand-primary-light {
    background: linear-gradient(135deg, var(--brand-primary-light), rgba(21, 72, 50, 0.25));
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* Header icon styling */
.card-header i.fas {
    filter: drop-shadow(0 2px 2px rgba(0,0,0,0.2));
}

/* Assessment badges */
.badge {
    border-radius: 6px;
    font-weight: 500;
    padding: 5px 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

/* Alert styling */
.alert {
    border-radius: 8px;
    border-left: 4px solid var(--brand-primary);
}
</style>
@endsection 