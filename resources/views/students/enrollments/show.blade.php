@extends('layouts.admin')

@section('content')
<div class="card">
    {{-- Enhanced Header Section --}}
    <div class="card-header bg-white">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">
                <i class="fas fa-graduation-cap text-primary me-2"></i>Course Enrollment Details
            </h5>
            <a href="{{ route('students.show', $enrollment->student) }}" 
               class="btn btn-outline-primary btn-sm">
                <i class="fas fa-arrow-left me-2"></i>Back to Student
            </a>
        </div>

        {{-- Enhanced Info Cards --}}
        <div class="row g-3">
            <div class="col-md-6">
                <div class="p-3 bg-light rounded-3 h-100">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                            <i class="fas fa-user text-primary"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Student Information</h6>
                            <p class="mb-0">
                                <span class="fw-bold">{{ $enrollment->student->first_name }} {{ $enrollment->student->last_name }}</span>
                                <span class="text-muted mx-2">•</span>
                                ID: <span class="fw-bold">{{ $enrollment->student->id }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="p-3 bg-light rounded-3 h-100">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                            <i class="fas fa-book text-success"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Course Information</h6>
                            <p class="mb-0">
                                <span class="fw-bold">{{ $enrollment->course->name }}</span>
                                <span class="text-muted mx-2">•</span>
                                Code: <span class="fw-bold">{{ $enrollment->course->code }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-12">
                <div class="p-3 bg-light rounded-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-info bg-opacity-10 p-3 me-3">
                            <i class="fas fa-info text-info"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="row align-items-center">
                                <div class="col-md-4">
                                    <h6 class="mb-1">Study Mode</h6>
                                    <p class="mb-0 fw-bold">{{ $enrollment->studyMode->name }}</p>
                                </div>
                                <div class="col-md-4">
                                    <h6 class="mb-1">Enrollment Code</h6>
                                    <p class="mb-0 fw-bold">{{ $enrollment->enrollmentCode->base_code }}</p>
                                </div>
                                <div class="col-md-4">
                                    <h6 class="mb-1">Status</h6>
                                    <span class="badge bg-{{ 
                                        $enrollment->status === 'active' ? 'success' : 
                                        ($enrollment->status === 'completed' ? 'primary' : 
                                        ($enrollment->status === 'withdrawn' ? 'danger' : 'warning')) 
                                    }} px-3 py-2">
                                        <i class="fas fa-circle me-1 small"></i>
                                        {{ ucfirst($enrollment->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card-body">
        {{-- Tabs Navigation --}}
        <ul class="nav nav-tabs mb-4" id="enrollmentTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" 
                        id="subjects-tab" 
                        data-bs-toggle="tab" 
                        data-bs-target="#subjects" 
                        type="button" 
                        role="tab" 
                        aria-selected="true">
                    <i class="fas fa-book-open me-2"></i>Subjects & Assessments
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" 
                        id="progress-tab" 
                        data-bs-toggle="tab" 
                        data-bs-target="#progress" 
                        type="button" 
                        role="tab" 
                        aria-selected="false">
                    <i class="fas fa-chart-line me-2"></i>Assessment Progress
                </button>
            </li>
        </ul>

        {{-- Tabs Content --}}
        <div class="tab-content" id="enrollmentTabsContent">
            {{-- Subjects Tab --}}
            <div class="tab-pane fade show active" id="subjects" role="tabpanel">
                @foreach($subjects as $subject)
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3">
                                    <i class="fas fa-book text-primary"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0">{{ $subject->name }}</h5>
                                    <small class="text-muted">Code: {{ $subject->code }}</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            @foreach($subject->modules as $module)
                                <div class="mb-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="rounded-circle bg-info bg-opacity-10 p-2 me-3">
                                            <i class="fas fa-cube text-info"></i>
                                        </div>
                                        <h6 class="mb-0">{{ $module->name }}</h6>
                                    </div>

                                    @php
                                        $moduleAssessments = collect();
                                        foreach ($subjectAssessments[$subject->id] as $semesterAllocations) {
                                            $moduleAssessments = $moduleAssessments->concat(
                                                $semesterAllocations->filter(function($allocation) use ($module) {
                                                    return $allocation->assessment->module_id === $module->id;
                                                })
                                            );
                                        }
                                        $hasAssessments = $moduleAssessments->isNotEmpty();
                                    @endphp

                                    @if($hasAssessments)
                                        <div class="ms-4 ps-3 border-start">
                                            @foreach($moduleAssessments->groupBy(function($allocation) {
                                                return $allocation->semester->name;
                                            }) as $semesterName => $semesterAllocations)
                                                <div class="mb-3">
                                                    <h6 class="text-muted mb-3">
                                                        <i class="fas fa-calendar-alt me-2"></i>{{ $semesterName }}
                                                    </h6>
                                                    
                                                    <div class="table-responsive">
                                                        <table class="table table-hover align-middle border">
                                                            <thead class="table-light">
                                                                <tr>
                                                                    <th style="width: 25%">Assessment</th>
                                                                    <th style="width: 15%">Type</th>
                                                                    <th style="width: 15%">Submission</th>
                                                                    <th style="width: 15%">Due Date</th>
                                                                    <th style="width: 15%">Status</th>
                                                                    <th style="width: 15%">Actions</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($semesterAllocations as $allocation)
                                                                    @php
                                                                        // Check if student has a completed submission for this allocation
                                                                        $submission = \App\Models\AssessmentAllocationSubmission::where([
                                                                            'assessment_allocation_id' => $allocation->id,
                                                                            'student_id' => $enrollment->student_id
                                                                        ])->first();
                                                                        
                                                                        $isSubmitted = $submission && ($submission->status === 'submitted' || $submission->status === 'graded');
                                                                    @endphp
                                                                    
                                                                    @if(!$isSubmitted && ($allocation->status === 'open' || $allocation->status === 'pending'))
                                                                    <tr>
                                                                        <td colspan="6" class="p-0 border-bottom-0">
                                                                            @if($allocation->submission_type === 'group')
                                                                                <a href="{{ route('students.submissions.create', ['allocation' => $allocation, 'group' => 1]) }}" 
                                                                                   class="btn btn-success w-100 rounded-0 d-flex align-items-center justify-content-center">
                                                                                    <i class="fas fa-users me-2"></i> Submit Group Assignment
                                                                                </a>
                                                                            @else
                                                                                <a href="{{ route('students.submissions.create', ['allocation' => $allocation]) }}" 
                                                                                   class="btn btn-success w-100 rounded-0 d-flex align-items-center justify-content-center">
                                                                                    <i class="fas fa-paper-plane me-2"></i> Submit Assessment
                                                                                </a>
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                    @endif
                                                                    <tr>
                                                                        <td>
                                                                            <div class="d-flex flex-column">
                                                                                <span class="fw-medium">{{ $allocation->assessment->name }}</span>
                                                                                @if($allocation->assessment->description)
                                                                                    <small class="text-muted">{{ Str::limit($allocation->assessment->description, 100) }}</small>
                                                                                @endif
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="d-flex flex-column">
                                                                                <span class="badge bg-{{ 
                                                                                    $allocation->assessment->type === 'exam' ? 'danger' : 
                                                                                    ($allocation->assessment->type === 'test' ? 'warning' : 
                                                                                    ($allocation->assessment->type === 'practical' ? 'info' : 'primary')) 
                                                                                }} mb-1">
                                                                                    {{ ucfirst($allocation->assessment->type) }}
                                                                                </span>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="d-flex flex-column">
                                                                                <span class="badge bg-info mb-1">
                                                                                    @if($allocation->submission_type === 'online' && $allocation->is_timed)
                                                                                        Online ({{ $allocation->duration_minutes }} min)
                                                                                    @else
                                                                                        {{ ucfirst($allocation->submission_type) }}
                                                                                    @endif
                                                                                </span>
                                                                                @if($allocation->submission_type === 'group')
                                                                                    <small class="text-muted">
                                                                                        <i class="fas fa-users me-1"></i>Group Submission
                                                                                    </small>
                                                                                @endif
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="d-flex flex-column">
                                                                                <span class="fw-medium">
                                                                                    <i class="far fa-calendar-alt text-muted me-1"></i>
                                                                                    {{ date('M d, Y', strtotime($allocation->due_date)) }}
                                                                                </span>
                                                                                <small class="text-muted">
                                                                                    {{ date('h:i A', strtotime($allocation->due_date)) }}
                                                                                </small>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="d-flex flex-column">
                                                                                <span class="badge bg-{{ 
                                                                                    $allocation->status === 'closed' ? 'success' : 
                                                                                    ($allocation->status === 'open' ? 'warning' : 'secondary') 
                                                                                }} mb-1">
                                                                                    <i class="fas fa-circle me-1 small"></i>
                                                                                    {{ ucfirst($allocation->status) }}
                                                                                </span>
                                                                                @if($allocation->grade)
                                                                                    <small class="text-success fw-medium">
                                                                                        <i class="fas fa-check-circle me-1"></i>{{ $allocation->grade }}%
                                                                                    </small>
                                                                                @endif
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="d-flex flex-column gap-2">
                                                                                @if($isSubmitted)
                                                                                    <a href="{{ route('students.submissions.view-answers', $allocation) }}" 
                                                                                       class="btn btn-primary">
                                                                                        <i class="fas fa-eye me-1"></i> View Answers
                                                                                    </a>
                                                                                @else
                                                                                    @if($allocation->submission_type === 'group')
                                                                                        <a href="{{ route('students.submissions.create', ['allocation' => $allocation, 'group' => 1]) }}" 
                                                                                           class="btn btn-success">
                                                                                            <i class="fas fa-users me-1"></i> Submit Group Assignment
                                                                                        </a>
                                                                                    @else
                                                                                        <a href="{{ route('students.submissions.create', ['allocation' => $allocation]) }}" 
                                                                                           class="btn btn-success">
                                                                                            <i class="fas fa-paper-plane me-1"></i> Submit Assessment
                                                                                        </a>
                                                                                    @endif
                                                                                @endif

                                                                                @if($allocation->assessment->description)
                                                                                    <button type="button" 
                                                                                            class="btn btn-outline-secondary btn-sm w-100"
                                                                                            data-bs-toggle="modal" 
                                                                                            data-bs-target="#infoModal{{ $allocation->id }}">
                                                                                        <i class="fas fa-info-circle me-1"></i>Details
                                                                                    </button>
                                                                                @endif
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="ms-4 ps-3 border-start">
                                            <div class="text-center py-3">
                                                <p class="text-muted mb-0">No assessments found for this module</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Progress Tab (UI Only) --}}
            <div class="tab-pane fade" id="progress" role="tabpanel">
                <div class="row g-4">
                    {{-- Overall Progress Card --}}
                    <div class="col-md-4">
                        <div class="card border-0 bg-primary bg-opacity-10">
                            <div class="card-body">
                                <h6 class="text-primary mb-3">Overall Progress</h6>
                                <div class="d-flex align-items-center">
                                    <div class="display-4 fw-bold text-primary me-2">75%</div>
                                    <div class="text-muted">
                                        <div>15 of 20</div>
                                        <div>Assessments Completed</div>
                                    </div>
                                </div>
                                <div class="progress mt-3" style="height: 8px;">
                                    <div class="progress-bar" style="width: 75%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Assessment Summary Cards --}}
                    <div class="col-md-8">
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <div class="card border-0 bg-success bg-opacity-10">
                                    <div class="card-body">
                                        <h6 class="text-success">Submitted</h6>
                                        <div class="display-6 fw-bold text-success">15</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="card border-0 bg-warning bg-opacity-10">
                                    <div class="card-body">
                                        <h6 class="text-warning">Pending</h6>
                                        <div class="display-6 fw-bold text-warning">5</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="card border-0 bg-info bg-opacity-10">
                                    <div class="card-body">
                                        <h6 class="text-info">Average Score</h6>
                                        <div class="display-6 fw-bold text-info">78%</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="card border-0 bg-danger bg-opacity-10">
                                    <div class="card-body">
                                        <h6 class="text-danger">Overdue</h6>
                                        <div class="display-6 fw-bold text-danger">2</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Assessment List --}}
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Assessment Submissions</h6>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Assessment</th>
                                            <th>Subject</th>
                                            <th>Due Date</th>
                                            <th>Score</th>
                                            <th>Max Score</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Mid-Term Exam</td>
                                            <td>Agriculture Basics</td>
                                            <td>Mar 15, 2024</td>
                                            <td>85</td>
                                            <td>100</td>
                                            <td>
                                                <span class="badge bg-success">Completed</span>
                                            </td>
                                        </tr>
                                        {{-- Add more dummy rows for UI demonstration --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Assessment Info Modals --}}
@foreach($subjects as $subject)
    @foreach($subject->modules as $module)
        @php
            $moduleAssessments = collect();
            foreach ($subjectAssessments[$subject->id] as $semesterAllocations) {
                $moduleAssessments = $moduleAssessments->concat(
                    $semesterAllocations->filter(function($allocation) use ($module) {
                        return $allocation->assessment->module_id === $module->id;
                    })
                );
            }
        @endphp
        @foreach($moduleAssessments as $allocation)
            @if($allocation->assessment->description)
                <div class="modal fade" id="infoModal{{ $allocation->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <i class="fas fa-info-circle text-info me-2"></i>
                                    {{ $allocation->assessment->name }} - Instructions
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="p-3 bg-light rounded">
                                    {!! nl2br(e($allocation->assessment->description)) !!}
                                </div>
                                @if($allocation->is_timed)
                                    <div class="alert alert-warning mt-3">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        <strong>Time Limit:</strong> This is a timed assessment. You will have 
                                        {{ $allocation->duration_minutes }} minutes to complete it once started.
                                    </div>
                                @endif
                                @if($allocation->due_date)
                                    <div class="alert alert-info mt-3">
                                        <i class="fas fa-calendar-alt me-2"></i>
                                        <strong>Due Date:</strong> 
                                        {{ date('M d, Y H:i', strtotime($allocation->due_date)) }}
                                    </div>
                                @endif
                            </div>
                            <div class="modal-footer">
                                @php
                                    // Check if student has a completed submission for this allocation
                                    $submission = \App\Models\AssessmentAllocationSubmission::where([
                                        'assessment_allocation_id' => $allocation->id,
                                        'student_id' => $enrollment->student_id
                                    ])->first();
                                    
                                    $isSubmitted = $submission && ($submission->status === 'submitted' || $submission->status === 'graded');
                                @endphp

                                @if($isSubmitted)
                                    <a href="{{ route('students.submissions.view-answers', $allocation) }}" 
                                       class="btn btn-primary">
                                        <i class="fas fa-eye me-1"></i> View Answers
                                    </a>
                                @else
                                    @if($allocation->submission_type === 'group')
                                        <a href="{{ route('students.submissions.create', ['allocation' => $allocation, 'group' => 1]) }}" 
                                           class="btn btn-success">
                                            <i class="fas fa-users me-1"></i> Submit Group Assignment
                                        </a>
                                    @else
                                        <a href="{{ route('students.submissions.create', ['allocation' => $allocation]) }}" 
                                           class="btn btn-success">
                                            <i class="fas fa-paper-plane me-1"></i> Submit Assessment
                                        </a>
                                    @endif
                                @endif
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    @endforeach
@endforeach
@endsection 