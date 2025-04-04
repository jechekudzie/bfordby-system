@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h4 class="mb-2 text-brand-primary">
                <i class="fas fa-file-alt me-2"></i>Academic Transcript
            </h4>
            <p class="text-muted">
                This transcript shows your academic progress and grades for all enrolled modules.
            </p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('students.transcript.download', $student) }}" class="btn btn-brand-primary">
                <i class="fas fa-download me-2"></i>Download PDF
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-brand-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-user-graduate me-2"></i>Student Information
                </h5>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Name</dt>
                        <dd class="col-sm-8">{{ $student->first_name }} {{ $student->last_name }}</dd>
                        
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
            <div class="card-header bg-brand-primary text-white py-3">
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
                                <tr class="bg-brand-primary bg-opacity-10">
                                    <td colspan="5" class="fw-bold text-white ps-3">
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
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="d-flex align-items-center mb-2">
                        <div class="badge badge-brand-primary me-2">Distinction</div>
                        <span>75% and above</span>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <div class="badge badge-brand-primary me-2">Merit</div>
                        <span>65% - 74%</span>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <div class="badge badge-brand-primary me-2">Credit</div>
                        <span>60% - 64%</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-center mb-2">
                        <div class="badge badge-brand-primary me-2">Pass</div>
                        <span>50% - 59%</span>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <div class="badge bg-danger me-2">Fail</div>
                        <span>Below 50%</span>
                    </div>
                </div>
            </div>
            <div class="alert bg-brand-secondary-light mb-0">
                <i class="fas fa-exclamation-triangle text-brand-primary me-2"></i>
                <span>This transcript is unoffical unless signed by an authorized officer of the institution.</span>
            </div>
        </div>
    </div>
</div>

<style>
/* Brand Colors */
.bg-brand-primary {
    background-color: #154832;
    color: white;
}

.bg-brand-secondary {
    background-color: #FBD801;
    color: #154832;
}

.bg-brand-primary-light {
    background-color: rgba(21, 72, 50, 0.2);
    color: #154832;
}

.bg-brand-secondary-light {
    background-color: rgba(251, 216, 1, 0.2);
    color: #154832;
}

.text-brand-primary {
    color: #154832;
}

.border-brand-primary {
    border-color: #154832;
}

.btn-brand-primary {
    background-color: #154832;
    color: white;
}

.btn-brand-primary:hover {
    background-color: #0d3422;
    color: white;
}

.btn-brand-secondary {
    background-color: #FBD801;
    color: #154832;
}

.btn-brand-secondary:hover {
    background-color: #e6c601;
    color: #154832;
}

.badge-brand-primary {
    background-color: #154832;
    color: white;
}

.badge-brand-secondary {
    background-color: #FBD801;
    color: #154832;
}
</style>
@endsection 