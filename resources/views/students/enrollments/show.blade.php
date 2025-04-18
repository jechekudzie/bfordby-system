@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <!-- Course Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-0">
                    <div class="row g-0">
                        <!-- Left side - Course Information -->
                        <div class="col-md-8 p-4">
                            <div class="d-flex flex-column h-100">
                                <div class="mb-3">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="bg-success rounded-circle p-3 me-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                            <i class="fas fa-check text-white fs-5"></i>
                                        </div>
                                        <h3 class="mb-0 fw-bold">{{ $enrollment->course->name }}</h3>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-6 mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="fas fa-clock text-secondary"></i>
                                            </div>
                                            <div>
                                                <div class="text-muted small">Study Mode</div>
                                                <div class="fw-bold">{{ $enrollment->studyMode->name }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="fas fa-calendar-alt text-secondary"></i>
                                            </div>
                                            <div>
                                                <div class="text-muted small">Enrolled</div>
                                                <div class="fw-bold">{{ date('M d, Y', strtotime($enrollment->enrollment_date)) }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="fas fa-qrcode text-secondary"></i>
                                            </div>
                                            <div>
                                                <div class="text-muted small">Code</div>
                                                <div class="fw-bold">{{ $enrollment->enrollmentCode->base_code ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="fas fa-info-circle text-secondary"></i>
                                            </div>
                                            <div>
                                                <div class="text-muted small">Status</div>
                                                <div>
                                                    @php
                                                        $statusClass = $enrollment->status === 'active' ? 'bg-success' : 
                                                            ($enrollment->status === 'completed' ? 'bg-brand-primary' : 
                                                            ($enrollment->status === 'withdrawn' ? 'bg-danger' : 'bg-warning'));
                                                    @endphp
                                                    <span class="badge {{ $statusClass }} rounded-pill px-3 py-2">
                                                        {{ ucfirst($enrollment->status) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right side - Student Information -->
                        <div class="col-md-4 p-4 bg-light">
                            <div class="d-flex flex-column h-100">
                                <div class="mb-3">
                                    <h5 class="mb-3 text-muted fw-bold"><i class="fas fa-user me-2"></i>Student Information</h5>
                                    
                                    <div class="d-flex align-items-center mb-4">
                                        <div class="bg-secondary text-white rounded-circle p-3 me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                            <span class="fw-bold fs-5">{{ strtoupper(substr($enrollment->student->first_name, 0, 1)) }}{{ strtoupper(substr($enrollment->student->last_name, 0, 1)) }}</span>
                                        </div>
                                        <div>
                                            <h5 class="mb-0 fw-bold">{{ $enrollment->student->first_name }} {{ $enrollment->student->last_name }}</h5>
                                            <p class="mb-0 text-muted">ID: {{ $enrollment->student->id }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="student-details">
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="fas fa-door-open text-muted me-2"></i>
                                            <span class="text-muted">Entry Type: <span class="fw-semibold text-dark">{{ ucfirst($enrollment->entry_type) }}</span></span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-auto">
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('students.show', $enrollment->student) }}" class="btn btn-outline-secondary">
                                            <i class="fas fa-arrow-left me-1"></i>Back to Student
                                        </a>
                                        <a href="{{ route('students.enrollments.edit', [$enrollment->student, $enrollment]) }}" class="btn btn-primary">
                                            <i class="fas fa-edit me-1"></i>Edit
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Simple Tabs -->
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <ul class="nav nav-pills" id="enrollmentTabs">
                    <li class="nav-item">
                        <a class="nav-link active px-4 py-2" href="#assessments" data-bs-toggle="pill">
                            <i class="fas fa-tasks me-2"></i>Assessments
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-4 py-2" href="#learning-materials" data-bs-toggle="pill">
                            <i class="fas fa-book-open me-2"></i>Learning Materials
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-4 py-2" href="#progress" data-bs-toggle="pill">
                            <i class="fas fa-chart-line me-2"></i>Academic Performance
                        </a>
                    </li>
                </ul>
                
                <div class="d-flex gap-2">
                    <a href="{{ route('students.transcript.simplified', $enrollment->student) }}" class="btn btn-brand-secondary">
                        <i class="fas fa-file-alt me-2"></i>Simplified Transcript
                    </a>
                    <a href="{{ route('students.transcript.show', $enrollment->student) }}" class="btn btn-brand-primary">
                        <i class="fas fa-file-alt me-2"></i>Academic Transcript
                    </a>
                </div>
            </div>

            <div class="tab-content">
                <!-- Assessments Tab -->
                <div class="tab-pane fade show active" id="assessments">
                    <!-- Subject Selector -->
                    <div class="card border-0 shadow-sm rounded-3 mb-4">
                        <div class="card-header py-3 bg-brand-primary">
                            <h5 class="mb-0 text-white"><i class="fas fa-book me-2"></i>Disciplines</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($subjects as $subject)
                                    <div class="col-md-4 mb-3">
                                        <div class="card h-100 subject-card" data-subject-id="{{ $subject->id }}">
                                            <div class="card-body p-3">
                                                <h6 class="mb-2">{{ $subject->name }}</h6>
                                                <p class="text-muted small mb-0">{{ count($subject->modules) }} modules</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    
                    <!-- Subject Assessments Area -->
                    @foreach($subjects as $subject)
                        <div class="subject-content {{ $loop->first ? 'active' : '' }}" id="subject-content-{{ $subject->id }}">
                            <!-- Module Selector -->
                            <div class="card border-0 shadow-sm rounded-3 mb-4">
                                <div class="card-header py-3 d-flex justify-content-between align-items-center bg-brand-primary">
                                    <h5 class="mb-0 text-white"><i class="fas fa-cube me-2"></i>{{ $subject->name }} - Modules</h5>
                                </div>
                                <div class="card-body">
                                    <div class="nav nav-pills module-tabs mb-3">
                                        @foreach($subject->modules as $module)
                                            <button class="nav-link {{ $loop->first ? 'active text-white-force' : '' }} me-2 mb-2" 
                                                    data-bs-toggle="pill" 
                                                    data-bs-target="#module-{{ $subject->id }}-{{ $module->id }}" 
                                                    type="button">
                                                {{ $module->name }}
                                            </button>
                                        @endforeach
                                    </div>
                                    
                                    <div class="tab-content">
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
                                                $hasAssessments = $moduleAssessments->isNotEmpty();
                                            @endphp

                                            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" 
                                                id="module-{{ $subject->id }}-{{ $module->id }}">
                                                @if($hasAssessments)
                                                    @foreach($moduleAssessments->groupBy(function($allocation) {
                                                        return $allocation->semester->name;
                                                    }) as $semesterName => $semesterAllocations)
                                                        <div class="card mb-4">
                                                            <div class="card-header bg-brand-secondary">
                                                                <h6 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>{{ $semesterName }}</h6>
                                                            </div>
                                                            <div class="card-body p-0">
                                                                <div class="table-responsive">
                                                                    <table class="table table-hover mb-0">
                                                                        <thead>
                                                                            <tr class="bg-brand-primary-light">
                                                                                <th>Assessment</th>
                                                                                <th>Type</th>
                                                                                <th>Due Date</th>
                                                                                <th>Status</th>
                                                                                <th>Actions</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach($semesterAllocations as $allocation)
                                                                                @php
                                                                                    $submission = \App\Models\AssessmentAllocationSubmission::where([
                                                                                        'assessment_allocation_id' => $allocation->id,
                                                                                        'student_id' => $enrollment->student_id
                                                                                    ])->first();
                                                                                    
                                                                                    $isSubmitted = $submission && ($submission->status === 'submitted' || $submission->status === 'graded');
                                                                                    $isInProgress = $submission && $submission->status === 'in_progress';
                                                                                    $isGraded = $submission && $submission->status === 'graded';
                                                                                    
                                                                                    $rowClass = $isSubmitted ? 'bg-success bg-opacity-10' : ($isInProgress ? 'bg-warning bg-opacity-10' : '');
                                                                                    
                                                                                    // Badge classes
                                                                                    $badgeClass = '';
                                                                                    if ($allocation->assessment->type === 'exam') {
                                                                                        $badgeClass = 'bg-danger';
                                                                                    } elseif ($allocation->assessment->type === 'test') {
                                                                                        $badgeClass = 'badge-brand-secondary';
                                                                                    } elseif ($allocation->assessment->type === 'practical') {
                                                                                        $badgeClass = 'bg-info';
                                                                                    } else {
                                                                                        $badgeClass = 'badge-brand-primary';
                                                                                    }
                                                                                    
                                                                                    // Status badge
                                                                                    $statusBadgeClass = '';
                                                                                    if ($isSubmitted) {
                                                                                        $statusBadgeClass = 'badge-brand-primary';
                                                                                    } elseif ($isInProgress) {
                                                                                        $statusBadgeClass = 'badge-brand-secondary';
                                                                                    } else {
                                                                                        $statusBadgeClass = $allocation->status === 'open' ? 'badge-brand-primary' : 
                                                                                           ($allocation->status === 'closed' ? 'bg-secondary' : 'badge-brand-secondary');
                                                                                    }
                                                                                @endphp
                                                                                <tr class="{{ $rowClass }}">
                                                                                    <td>
                                                                                        <strong>{{ $allocation->assessment->name }}</strong>
                                                                                    </td>
                                                                                    <td>
                                                                                        <span class="badge {{ $badgeClass }}">
                                                                                            {{ ucfirst($allocation->assessment->type) }}
                                                                                        </span>
                                                                                    </td>
                                                                                    <td>
                                                                                        @if($allocation->due_date)
                                                                                            {{ date('d M Y, H:i', strtotime($allocation->due_date)) }}
                                                                                        @else
                                                                                            <span class="text-muted">Not set</span>
                                                                                        @endif
                                                                                    </td>
                                                                                    <td>
                                                                                        <span class="badge {{ $statusBadgeClass }}">
                                                                                            @if($isSubmitted)
                                                                                                {{ $isGraded ? 'Graded' : 'Submitted' }}
                                                                                            @elseif($isInProgress)
                                                                                                In Progress
                                                                                            @else
                                                                                                {{ ucfirst($allocation->status) }}
                                                                                            @endif
                                                                                        </span>
                                                                                    </td>
                                                                                    <td>
                                                                                        <div class="btn-group">
                                                                                            @if($isSubmitted)
                                                                                                <a href="{{ route('students.submissions.view-answers', $allocation) }}" 
                                                                                                   class="btn btn-sm btn-brand-primary">
                                                                                                    <i class="fas fa-eye"></i> View
                                                                                                </a>
                                                                                            @else
                                                                                                <a href="{{ route('students.submissions.create', $allocation) }}" 
                                                                                                   class="btn btn-sm btn-brand-secondary">
                                                                                                    <i class="fas fa-pencil-alt"></i> Submit
                                                                                                </a>
                                                                                            @endif
                                                                                            
                                                                                            @if($allocation->assessment->description)
                                                                                                <button type="button" 
                                                                                                        class="btn btn-sm btn-light"
                                                                                                        data-bs-toggle="modal" 
                                                                                                        data-bs-target="#infoModal{{ $allocation->id }}">
                                                                                                    <i class="fas fa-info-circle"></i>
                                                                                                </button>
                                                                                                
                                                                                                <!-- Modal for Assessment Description -->
                                                                                                <div class="modal fade" id="infoModal{{ $allocation->id }}" tabindex="-1" aria-hidden="true">
                                                                                                    <div class="modal-dialog">
                                                                                                        <div class="modal-content">
                                                                                                            <div class="modal-header bg-brand-primary text-white">
                                                                                                                <h5 class="modal-title">{{ $allocation->assessment->name }}</h5>
                                                                                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                                                            </div>
                                                                                                            <div class="modal-body">
                                                                                                                <div class="mb-3">
                                                                                                                    <strong>Type:</strong> {{ ucfirst($allocation->assessment->type) }}<br>
                                                                                                                    <strong>Due Date:</strong> 
                                                                                                                    @if($allocation->due_date)
                                                                                                                        {{ date('d M Y, H:i', strtotime($allocation->due_date)) }}
                                                                                                                    @else
                                                                                                                        Not set
                                                                                                                    @endif
                                                                                                                </div>
                                                                                                                <div class="mb-0">
                                                                                                                    <strong>Description:</strong>
                                                                                                                    <p class="mt-2">{!! nl2br(e($allocation->assessment->description)) !!}</p>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                            <div class="modal-footer">
                                                                                                                <button type="button" class="btn btn-brand-primary" data-bs-dismiss="modal">Close</button>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            @endif
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <div class="alert bg-brand-secondary-light border-0">
                                                        <i class="fas fa-info-circle me-2"></i>
                                                        No assessments found for this module.
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Learning Materials Tab -->
                <div class="tab-pane fade" id="learning-materials">
                    <!-- Subject Selector -->
                    <div class="card border-0 shadow-sm rounded-3 mb-4">
                        <div class="card-header py-3 bg-brand-primary">
                            <h5 class="mb-0 text-white"><i class="fas fa-book me-2"></i>Disciplines</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($subjects as $subject)
                                    <div class="col-md-4 mb-3">
                                        <div class="card h-100 materials-subject-card" data-subject-id="{{ $subject->id }}">
                                            <div class="card-body p-3">
                                                <h6 class="mb-2">{{ $subject->name }}</h6>
                                                <p class="text-muted small mb-0">{{ count($subject->modules) }} modules</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    
                    <!-- Subject Content Area -->
                    @foreach($subjects as $subject)
                        <div class="materials-subject-content {{ $loop->first ? 'active' : '' }}" id="materials-subject-content-{{ $subject->id }}">
                            <!-- Module Selector -->
                            <div class="card border-0 shadow-sm rounded-3 mb-4">
                                <div class="card-header py-3 d-flex justify-content-between align-items-center bg-brand-primary">
                                    <h5 class="mb-0 text-white"><i class="fas fa-cube me-2"></i>{{ $subject->name }} - Modules</h5>
                                </div>
                                <div class="card-body">
                                    <div class="nav nav-pills materials-module-tabs mb-3">
                                        @foreach($subject->modules as $module)
                                            <button class="nav-link {{ $loop->first ? 'active text-white-force' : '' }} me-2 mb-2" 
                                                    data-bs-toggle="pill" 
                                                    data-bs-target="#materials-module-{{ $subject->id }}-{{ $module->id }}" 
                                                    type="button">
                                                {{ $module->name }}
                                            </button>
                                        @endforeach
                                    </div>
                                    
                                    <div class="tab-content">
                                        @foreach($subject->modules as $module)
                                            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" 
                                                id="materials-module-{{ $subject->id }}-{{ $module->id }}">
                                                @if($module->contents->count() > 0)
                                                    <div class="list-group">
                                                        @foreach($module->contents as $content)
                                                            <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center p-3 {{ $content->is_required ? 'border-start border-3 border-warning' : '' }}">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="me-3">
                                                                        <div class="rounded-circle bg-light p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                                            <i class="{{ $content->getIconClass() }} text-{{ $content->is_required ? 'warning' : 'secondary' }}"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div>
                                                                        <h6 class="mb-1">{{ $content->title }}</h6>
                                                                        @if($content->description)
                                                                            <p class="text-muted small mb-1">{{ $content->description }}</p>
                                                                        @endif
                                                                        <span class="badge {{ $content->is_required ? 'bg-warning text-dark' : 'bg-secondary' }} small">
                                                                            {{ $content->is_required ? 'Required' : 'Optional' }}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div>
                                                                    @if($content->isFile() && $content->getFileUrl())
                                                                        <a href="{{ $content->getFileUrl() }}" target="_blank" class="btn btn-sm btn-primary">
                                                                            <i class="fas fa-download me-1"></i> Download
                                                                        </a>
                                                                    @elseif($content->isExternal())
                                                                        <a href="{{ $content->external_url }}" target="_blank" class="btn btn-sm btn-info">
                                                                            <i class="fas fa-external-link-alt me-1"></i> Open
                                                                        </a>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <div class="alert alert-info">
                                                        <i class="fas fa-info-circle me-2"></i>
                                                        No learning materials have been added to this module yet.
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Progress Tab -->
                <div class="tab-pane fade" id="progress">
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-body py-4">
                            <h4 class="mb-4 text-brand-primary"><i class="fas fa-calculator me-2"></i>Understanding Your Grades</h4>
                            
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <div class="card-header bg-brand-primary text-white py-3">
                                            <h5 class="mb-0 text-white"><i class="fas fa-percentage me-2"></i>How Grades Are Calculated</h5>
                                        </div>
                                        <div class="card-body">
                                            <p>Your final grade for each module is calculated as a weighted average of all assessments within that module:</p>
                                            
                                            <ol class="mb-4">
                                                <li class="mb-2">Each assessment is assigned a weight (percentage) that reflects its importance in the module.</li>
                                                <li class="mb-2">Your score for each assessment is multiplied by its weight.</li>
                                                <li class="mb-2">These weighted scores are added together to form your final module score.</li>
                                                <li>Your final score is then converted to a letter grade using the grading scale.</li>
                                            </ol>
                                            
                                            <div class="alert bg-brand-primary-light border-0">
                                                <div class="d-flex">
                                                    <div class="me-3">
                                                        <i class="fas fa-formula text-brand-primary"></i>
                                                    </div>
                                                    <div>
                                                        <strong>Formula:</strong><br>
                                                        Module Grade = Σ (Assessment Score × Assessment Weight)
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <div class="card-header bg-brand-primary text-white py-3">
                                            <h5 class="mb-0 text-white"><i class="fas fa-chart-bar me-2"></i>Grading Scale</h5>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="table-responsive">
                                                <table class="table table-hover mb-0">
                                                    <thead>
                                                        <tr class="bg-brand-primary-light">
                                                            <th class="px-3">Grade</th>
                                                            <th class="px-3">Percentage Range</th>
                                                            <th class="px-3">Classification</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="px-3"><span class="badge bg-success">A</span></td>
                                                            <td class="px-3">85% - 100%</td>
                                                            <td class="px-3">Distinction</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="px-3"><span class="badge bg-success">B+</span></td>
                                                            <td class="px-3">75% - 84%</td>
                                                            <td class="px-3">Merit Plus</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="px-3"><span class="badge badge-brand-secondary">B</span></td>
                                                            <td class="px-3">65% - 74%</td>
                                                            <td class="px-3">Merit</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="px-3"><span class="badge badge-brand-secondary">C+</span></td>
                                                            <td class="px-3">60% - 64%</td>
                                                            <td class="px-3">Credit Plus</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="px-3"><span class="badge bg-secondary">C</span></td>
                                                            <td class="px-3">50% - 59%</td>
                                                            <td class="px-3">Credit</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="px-3"><span class="badge bg-danger">F</span></td>
                                                            <td class="px-3">0% - 49%</td>
                                                            <td class="px-3">Fail</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-4">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-header bg-brand-primary text-white py-3">
                                            <h5 class="mb-0 text-white"><i class="fas fa-puzzle-piece me-2"></i>Understanding Course Components</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6 class="border-bottom pb-2 mb-3"><i class="fas fa-layer-group text-brand-primary me-2"></i>Course Structure Hierarchy</h6>
                                                    <div class="d-flex mb-3">
                                                        <div class="bg-brand-primary rounded-circle p-2 d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                            <i class="fas fa-graduation-cap text-white"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-1">Course</h6>
                                                            <p class="text-muted small mb-0">The complete program you're enrolled in (e.g., Bachelor of Business)</p>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex mb-3 ms-4">
                                                        <div class="bg-brand-secondary rounded-circle p-2 d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px;">
                                                            <i class="fas fa-book text-brand-primary"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-1">Discipline</h6>
                                                            <p class="text-muted small mb-0">Main area of study within your course (e.g., Accounting)</p>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex mb-3 ms-5">
                                                        <div class="bg-light rounded-circle p-2 d-flex align-items-center justify-content-center me-3" style="width: 32px; height: 32px;">
                                                            <i class="fas fa-cube text-brand-primary"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-1">Module</h6>
                                                            <p class="text-muted small mb-0">Specific topic or unit within a discipline (e.g., Financial Reporting)</p>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex mb-3 ms-5">
                                                        <div class="bg-light rounded-circle p-2 d-flex align-items-center justify-content-center me-3" style="width: 32px; height: 32px;">
                                                            <i class="fas fa-tasks text-brand-primary"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-1">Assessment</h6>
                                                            <p class="text-muted small mb-0">Individual tasks that evaluate your knowledge (e.g., exams, assignments)</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6 class="border-bottom pb-2 mb-3"><i class="fas fa-balance-scale text-brand-primary me-2"></i>Assessment Structure & Weights</h6>
                                                    <p>Each module has its own assessment structure that defines how your performance will be measured:</p>
                                                    
                                                    <div class="d-flex mb-3 align-items-start">
                                                        <div class="bg-brand-primary-light rounded p-2 d-flex align-items-center justify-content-center me-3" style="min-width: 36px; height: 36px;">
                                                            <i class="fas fa-sitemap text-brand-primary"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-1">Module Assessment Structure</h6>
                                                            <p class="text-muted small mb-0">Defines which assessment types (exams, projects, etc.) are included in a module</p>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="d-flex mb-3 align-items-start">
                                                        <div class="bg-brand-primary-light rounded p-2 d-flex align-items-center justify-content-center me-3" style="min-width: 36px; height: 36px;">
                                                            <i class="fas fa-balance-scale-left text-brand-primary"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-1">Module Assessment Weight</h6>
                                                            <p class="text-muted small mb-0">Determines how much each assessment contributes to your final module grade</p>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="d-flex mb-3 align-items-start">
                                                        <div class="bg-brand-primary-light rounded p-2 d-flex align-items-center justify-content-center me-3" style="min-width: 36px; height: 36px;">
                                                            <i class="fas fa-percentage text-brand-primary"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-1">Discipline Contribution</h6>
                                                            <p class="text-muted small mb-0">How much each discipline contributes to your overall course GPA</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12 mb-4">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-header bg-brand-primary text-white py-3">
                                            <h5 class="mb-0 text-white"><i class="fas fa-lightbulb me-2"></i>Example Calculation</h5>
                                        </div>
                                        <div class="card-body">
                                            <p>For a module with 3 assessments:</p>
                                            
                                            <div class="table-responsive mb-3">
                                                <table class="table table-bordered">
                                                    <thead class="bg-brand-primary-light">
                                                        <tr>
                                                            <th>Assessment</th>
                                                            <th>Weight</th>
                                                            <th>Your Score</th>
                                                            <th>Weighted Score</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>Midterm Exam</td>
                                                            <td>30%</td>
                                                            <td>80%</td>
                                                            <td>24% (80 × 0.3)</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Practical Project</td>
                                                            <td>50%</td>
                                                            <td>75%</td>
                                                            <td>37.5% (75 × 0.5)</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Final Assignment</td>
                                                            <td>20%</td>
                                                            <td>90%</td>
                                                            <td>18% (90 × 0.2)</td>
                                                        </tr>
                                                    </tbody>
                                                    <tfoot class="bg-brand-primary-light">
                                                        <tr>
                                                            <th colspan="3" class="text-end">Final Module Score:</th>
                                                            <th>79.5% (B+)</th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                            
                                            <div class="alert bg-brand-secondary-light border-0">
                                                <div class="d-flex">
                                                    <div class="me-3">
                                                        <i class="fas fa-info-circle text-brand-primary"></i>
                                                    </div>
                                                    <div>
                                                        <strong>Note:</strong> You must pass each individual assessment to pass the module. A passing grade is typically 50% or higher, depending on the specific requirements of each module.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom styles */
.subject-card {
    transition: all 0.2s ease;
    cursor: pointer;
    border: 2px solid transparent;
}

.subject-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.1);
}

.subject-card.active {
    border-color: #154832;
    background-color: rgba(21, 72, 50, 0.05);
}

.subject-content {
    display: none;
}

.subject-content.active {
    display: block;
}

/* Navigation Pills */
.nav-pills .nav-link {
    color: #154832;
    background-color: #f8f9fa;
    margin-right: 0.5rem;
    border-radius: 0.5rem;
    transition: all 0.2s ease;
}

.nav-pills .nav-link:hover {
    background-color: rgba(21, 72, 50, 0.1);
}

.nav-pills .nav-link.active, 
.nav-pills .show > .nav-link,
.module-tabs .nav-link.active {
    color: white !important;
    background-color: #154832 !important;
    font-weight: 500;
}

#enrollmentTabs .nav-link.active {
    color: white !important;
}

.empty-state-icon {
    opacity: 0.5;
}

/* Table styles */
.table th {
    font-weight: 600;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.table td {
    vertical-align: middle;
}

/* Button group styles */
.btn-group .btn {
    margin-right: 0.25rem;
}

/* Custom theme colors */
.bg-brand-primary {
    background-color: #154832;
    color: white;
}

.bg-brand-secondary {
    background-color: #FBD801;
    color: #154832;
}

.bg-brand-primary-light {
    background-color: rgba(21, 72, 50, 0.05);
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

.rounded-circle-icon {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.rounded-pill {
    border-radius: 50px;
}

.btn-close-white {
    filter: invert(1) grayscale(100%) brightness(200%);
}

/* Add to the CSS */
.module-tabs .nav-link.active {
    color: white !important;
}

.text-white-force {
    color: white !important;
}

.materials-subject-card {
    transition: all 0.2s ease;
    cursor: pointer;
    border: 2px solid transparent;
}

.materials-subject-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.1);
}

.materials-subject-card.active {
    border-color: #154832;
    background-color: rgba(21, 72, 50, 0.05);
}

.materials-subject-content {
    display: none;
}

.materials-subject-content.active {
    display: block;
}
</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tab behavior using Bootstrap's API
        var triggerTabList = [].slice.call(document.querySelectorAll('#enrollmentTabs a'));
        triggerTabList.forEach(function (triggerEl) {
            var tabTrigger = new bootstrap.Tab(triggerEl);
            triggerEl.addEventListener('click', function (event) {
                event.preventDefault();
                tabTrigger.show();
            });
        });
        
        // Subject cards for assessments tab
        const subjectCards = document.querySelectorAll('.subject-card');
        const subjectContents = document.querySelectorAll('.subject-content');
        
        // Subject cards for learning materials tab
        const materialsSubjectCards = document.querySelectorAll('.materials-subject-card');
        const materialsSubjectContents = document.querySelectorAll('.materials-subject-content');
        
        // Add click event listeners to assessment subject cards
        subjectCards.forEach(card => {
            card.addEventListener('click', function() {
                const subjectId = this.dataset.subjectId;
                
                // Remove active class from all subject cards
                subjectCards.forEach(c => {
                    c.classList.remove('active');
                });
                
                // Add active class to clicked card
                this.classList.add('active');
                
                // Hide all subject content
                subjectContents.forEach(content => {
                    content.classList.remove('active');
                });
                
                // Show selected subject content
                const subjectContent = document.getElementById('subject-content-' + subjectId);
                if (subjectContent) {
                    subjectContent.classList.add('active');
                    
                    // Activate first module tab for the selected subject
                    const firstModuleTab = subjectContent.querySelector('.module-tabs .nav-link');
                    if (firstModuleTab) {
                        firstModuleTab.click();
                    }
                }
            });
        });
        
        // Add click event listeners to learning materials subject cards
        materialsSubjectCards.forEach(card => {
            card.addEventListener('click', function() {
                const subjectId = this.dataset.subjectId;
                
                // Remove active class from all subject cards
                materialsSubjectCards.forEach(c => {
                    c.classList.remove('active');
                });
                
                // Add active class to clicked card
                this.classList.add('active');
                
                // Hide all subject content
                materialsSubjectContents.forEach(content => {
                    content.classList.remove('active');
                });
                
                // Show selected subject content
                const subjectContent = document.getElementById('materials-subject-content-' + subjectId);
                if (subjectContent) {
                    subjectContent.classList.add('active');
                    
                    // Activate first module tab for the selected subject
                    const firstModuleTab = subjectContent.querySelector('.materials-module-tabs .nav-link');
                    if (firstModuleTab) {
                        firstModuleTab.click();
                    }
                }
            });
        });
        
        // Activate first subject card by default for both tabs
        if (subjectCards.length > 0) {
            subjectCards[0].classList.add('active');
        }
        
        if (materialsSubjectCards.length > 0) {
            materialsSubjectCards[0].classList.add('active');
        }
        
        // Module tab selection handling for assessments
        const moduleTabs = document.querySelectorAll('.module-tabs .nav-link');
        
        // Add click event listeners to module tabs
        moduleTabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const target = this.dataset.bsTarget;
                const targetId = target.substring(1); // Remove the # from the target
                
                // Find the parent subject content
                const subjectContent = this.closest('.subject-content');
                
                // Remove active class from all module tabs in this subject
                const allTabs = subjectContent.querySelectorAll('.module-tabs .nav-link');
                allTabs.forEach(t => {
                    t.classList.remove('active', 'text-white-force');
                });
                
                // Add active class to this tab
                this.classList.add('active', 'text-white-force');
                
                // Hide all module content panes in this subject
                const allModulePanes = subjectContent.querySelectorAll('.tab-content .tab-pane');
                allModulePanes.forEach(pane => {
                    pane.classList.remove('show', 'active');
                });
                
                // Show the target module pane
                const targetPane = document.getElementById(targetId);
                if (targetPane) {
                    targetPane.classList.add('show', 'active');
                }
            });
        });
        
        // Module tab selection handling for materials
        const materialsModuleTabs = document.querySelectorAll('.materials-module-tabs .nav-link');
        
        // Add click event listeners to module tabs
        materialsModuleTabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const target = this.dataset.bsTarget;
                const targetId = target.substring(1); // Remove the # from the target
                
                // Find the parent subject content
                const subjectContent = this.closest('.materials-subject-content');
                
                // Remove active class from all module tabs in this subject
                const allTabs = subjectContent.querySelectorAll('.materials-module-tabs .nav-link');
                allTabs.forEach(t => {
                    t.classList.remove('active', 'text-white-force');
                });
                
                // Add active class to this tab
                this.classList.add('active', 'text-white-force');
                
                // Hide all module content panes in this subject
                const allModulePanes = subjectContent.querySelectorAll('.tab-content .tab-pane');
                allModulePanes.forEach(pane => {
                    pane.classList.remove('show', 'active');
                });
                
                // Show the target module pane
                const targetPane = document.getElementById(targetId);
                if (targetPane) {
                    targetPane.classList.add('show', 'active');
                }
            });
        });
    });
</script>
@endpush
@endsection
