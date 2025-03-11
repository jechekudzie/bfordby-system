@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <!-- Simple Modern Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-0">
                    <div class="row g-0">
                        <!-- Left side - Course Information -->
                        <div class="col-md-8 p-4">
                            <div class="d-flex flex-column">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                        <i class="fas fa-graduation-cap text-primary"></i>
                                    </div>
                                    <h4 class="mb-0">{{ $enrollment->course->name }}</h4>
                                </div>
                                
                                <div class="mt-2 mb-4">
                                    <span class="badge bg-info text-white rounded-pill px-3 py-2">
                                        <i class="fas fa-clock me-1"></i> {{ $enrollment->studyMode->name }}
                                    </span>
                                </div>
                                
                                <div class="row mt-3">
                                    <div class="col-md-6 mb-3">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-calendar-alt text-primary me-2"></i>
                                            <div>
                                                <div class="text-muted small">Enrollment Date</div>
                                                <div>{{ date('M d, Y', strtotime($enrollment->created_at)) }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-hourglass-half text-primary me-2"></i>
                                            <div>
                                                <div class="text-muted small">Duration</div>
                                                <div>{{ $enrollment->course->duration ?? 'Not specified' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right side - Student Information -->
                        <div class="col-md-4 bg-light p-4">
                            <div class="d-flex flex-column h-100">
                                <div class="mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="bg-success bg-opacity-10 rounded-circle p-1 me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                            <i class="fas fa-user text-success"></i>
                                        </div>
                                        <span class="text-uppercase text-muted small fw-bold">Student Information</span>
                                    </div>
                                    
                                    <h4 class="mb-4">{{ $enrollment->student->first_name }} {{ $enrollment->student->last_name }}</h4>
                                    
                                    <div class="student-details">
                                        @if($enrollment->enrollmentCode)
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-hashtag text-muted me-2"></i>
                                                <span class="text-muted">Enrollment Code: <span class="text-dark">{{ $enrollment->enrollmentCode->base_code }}</span></span>
                                            </div>
                                        @endif
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="fas fa-id-card text-muted me-2"></i>
                                            <span class="text-muted">Student ID: <span class="text-dark">{{ $enrollment->student->id }}</span></span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="d-flex align-items-center justify-content-between mt-auto">
                                    <span class="badge bg-{{ 
                                        $enrollment->status === 'active' ? 'success' : 
                                        ($enrollment->status === 'completed' ? 'primary' : 
                                        ($enrollment->status === 'withdrawn' ? 'danger' : 'warning')) 
                                    }} rounded-pill px-3 py-2">
                                        <i class="fas fa-circle me-1 small"></i>{{ ucfirst($enrollment->status) }}
                                    </span>
                                    <a href="{{ route('students.show', $enrollment->student) }}" 
                                       class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                        <i class="fas fa-arrow-left me-1"></i>Back
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modern Tabs -->
    <div class="row">
        <div class="col-12">
            <ul class="nav nav-tabs border-0 mb-4" id="enrollmentTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active px-4 py-3 rounded-0 border-0 border-bottom border-3 border-primary" 
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
                    <button class="nav-link px-4 py-3 rounded-0 border-0" 
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

            <div class="tab-content" id="enrollmentTabsContent">
                <div class="tab-pane fade show active" id="subjects" role="tabpanel">
                    <div class="row">
                        <!-- Left Sidebar - Subjects List -->
                        <div class="col-md-3 mb-4">
                            <div class="card border-0 shadow-sm rounded-3">
                                <div class="card-header bg-white py-3 border-0 border-bottom">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-book text-primary me-2"></i>
                                        <h5 class="mb-0">Subjects</h5>
                                    </div>
                                </div>
                                <div class="list-group list-group-flush rounded-bottom">
                                    @foreach($subjects as $subject)
                                        <button type="button" 
                                                class="list-group-item list-group-item-action border-0 py-3 px-3 subject-item {{ $loop->first ? 'active' : '' }}"
                                                data-subject-id="{{ $subject->id }}">
                                            <div class="d-flex align-items-center">
                                                <div class="subject-icon me-2 d-flex align-items-center justify-content-center">
                                                    <i class="fas fa-book text-primary"></i>
                                                </div>
                                                <span>{{ $subject->name }}</span>
                                            </div>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right Content Area -->
                        <div class="col-md-9">
                            <!-- Modules List -->
                            <div class="card border-0 shadow-sm rounded-3 mb-4">
                                <div class="card-header bg-white py-3 border-0 border-bottom">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-cube text-info me-2"></i>
                                        <h5 class="mb-0">Modules</h5>
                                    </div>
                                </div>
                                <div class="card-body p-3">
                                    @foreach($subjects as $subject)
                                        <div class="module-container {{ $loop->first ? '' : 'd-none' }}" id="modules-{{ $subject->id }}">
                                            <div class="d-flex flex-nowrap overflow-auto module-scroll pb-2">
                                                @foreach($subject->modules as $module)
                                                    <button type="button" 
                                                            class="btn {{ $loop->first && $loop->parent->first ? 'btn-primary' : 'btn-outline-primary' }} me-2 module-item rounded-pill px-3 py-2"
                                                            data-subject-id="{{ $subject->id }}"
                                                            data-module-id="{{ $module->id }}">
                                                        <i class="fas fa-cube me-2"></i>{{ $module->name }}
                                                    </button>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            
                            <!-- Assessment Content -->
                            @foreach($subjects as $subject)
                                @foreach($subject->modules as $module)
                                    <div class="module-content {{ ($loop->parent->first && $loop->first) ? '' : 'd-none' }}" id="content-{{ $subject->id }}-{{ $module->id }}">
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
                                            @foreach($moduleAssessments->groupBy(function($allocation) {
                                                return $allocation->semester->name;
                                            }) as $semesterName => $semesterAllocations)
                                                <div class="card border-0 shadow-sm rounded-3 mb-4">
                                                    <div class="card-header bg-white py-3 border-0 border-bottom">
                                                        <div class="d-flex align-items-center">
                                                            <i class="fas fa-calendar-alt text-primary me-2"></i>
                                                            <h5 class="mb-0">{{ $semesterName }}</h5>
                                                        </div>
                                                    </div>
                                                    <div class="card-body p-0">
                                                        <div class="table-responsive">
                                                            <table class="table table-hover mb-0">
                                                                <thead class="table-light">
                                                                    <tr>
                                                                        <th class="border-0 py-3">Assessment</th>
                                                                        <th class="border-0 py-3">Type</th>
                                                                        <th class="border-0 py-3">Submission</th>
                                                                        <th class="border-0 py-3">Due Date</th>
                                                                        <th class="border-0 py-3">Status</th>
                                                                        <th class="border-0 py-3">Actions</th>
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
                                                                            
                                                                            // Get submission details from allocation
                                                                            $submissionType = $allocation->submission_type ?? '';
                                                                            $isTimed = $allocation->is_timed ?? false;
                                                                            $durationMinutes = $allocation->duration_minutes ?? null;
                                                                        @endphp
                                                                        <tr>
                                                                            <td class="border-0 py-3">
                                                                                <div class="d-flex align-items-center">
                                                                                    <div class="assessment-icon me-3 rounded-circle p-2 d-flex align-items-center justify-content-center" 
                                                                                        style="width: 40px; height: 40px;"
                                                                                        class="{{ $allocation->assessment->type === 'exam' ? 'bg-danger bg-opacity-10' : 
                                                                                        ($allocation->assessment->type === 'test' ? 'bg-warning bg-opacity-10' : 
                                                                                        ($allocation->assessment->type === 'practical' ? 'bg-info bg-opacity-10' : 'bg-primary bg-opacity-10')) }}">
                                                                                        <i class="fas 
                                                                                            {{ $allocation->assessment->type === 'exam' ? 'fa-file-alt text-danger' : 
                                                                                            ($allocation->assessment->type === 'test' ? 'fa-tasks text-warning' : 
                                                                                            ($allocation->assessment->type === 'practical' ? 'fa-flask text-info' : 'fa-file-signature text-primary')) }}">
                                                                                        </i>
                                                                                    </div>
                                                                                    <div>
                                                                                        <strong>{{ $allocation->assessment->name }}</strong>
                                                                                        @if($allocation->assessment->description)
                                                                                            <p class="text-muted small mb-0">{{ Str::limit($allocation->assessment->description, 60) }}</p>
                                                                                        @endif
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                            <td class="border-0 py-3">
                                                                                <span class="badge bg-{{ 
                                                                                    $allocation->assessment->type === 'exam' ? 'danger' : 
                                                                                    ($allocation->assessment->type === 'test' ? 'warning' : 
                                                                                    ($allocation->assessment->type === 'practical' ? 'info' : 'primary')) 
                                                                                }} bg-opacity-10 text-{{ 
                                                                                    $allocation->assessment->type === 'exam' ? 'danger' : 
                                                                                    ($allocation->assessment->type === 'test' ? 'warning' : 
                                                                                    ($allocation->assessment->type === 'practical' ? 'info' : 'primary')) 
                                                                                }} rounded-pill px-3 py-2">
                                                                                    {{ ucfirst($allocation->assessment->type) }}
                                                                                </span>
                                                                            </td>
                                                                            <td class="border-0 py-3">
                                                                                @if($submissionType == 'online')
                                                                                    <span class="badge bg-primary text-white rounded-pill px-3 py-2">
                                                                                        <i class="fas fa-globe me-1"></i> Online
                                                                                        @if($isTimed)
                                                                                            <span class="ms-1 small">
                                                                                                <i class="fas fa-clock"></i> {{ $durationMinutes }} minutes
                                                                                            </span>
                                                                                        @endif
                                                                                    </span>
                                                                                @elseif($submissionType == 'upload')
                                                                                    <span class="badge bg-info text-white rounded-pill px-3 py-2">
                                                                                        <i class="fas fa-upload me-1"></i> Upload
                                                                                    </span>
                                                                                @elseif($submissionType == 'in-class')
                                                                                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2">
                                                                                        <i class="fas fa-chalkboard-teacher me-1"></i> In-class
                                                                                    </span>
                                                                                @elseif($submissionType == 'group')
                                                                                    <span class="badge bg-success text-white rounded-pill px-3 py-2">
                                                                                        <i class="fas fa-users me-1"></i> Group
                                                                                    </span>
                                                                                @else
                                                                                    <span class="badge bg-secondary text-white rounded-pill px-3 py-2">
                                                                                        <i class="fas fa-file me-1"></i> {{ ucfirst($submissionType ?: 'Standard') }}
                                                                                    </span>
                                                                                @endif
                                                                            </td>
                                                                            <td class="border-0 py-3">
                                                                                <div class="d-flex align-items-center">
                                                                                    <i class="far fa-calendar-alt text-muted me-2"></i>
                                                                                    <div>
                                                                                        @if($allocation->due_date)
                                                                                            <div>{{ date('d M Y', strtotime($allocation->due_date)) }}</div>
                                                                                        @else
                                                                                            <div class="text-muted">Not set</div>
                                                                                        @endif
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                            <td class="border-0 py-3">
                                                                                <span class="badge bg-{{ 
                                                                                    $allocation->status === 'open' ? 'success' : 
                                                                                    ($allocation->status === 'closed' ? 'secondary' : 'warning') 
                                                                                }} bg-opacity-10 text-{{ 
                                                                                    $allocation->status === 'open' ? 'success' : 
                                                                                    ($allocation->status === 'closed' ? 'secondary' : 'warning') 
                                                                                }} rounded-pill px-3 py-2">
                                                                                    {{ ucfirst($allocation->status) }}
                                                                                </span>
                                                                                @if($allocation->grade)
                                                                                    <div class="mt-2">
                                                                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2">
                                                                                            <i class="fas fa-check-circle me-1"></i>{{ $allocation->grade }}%
                                                                                        </span>
                                                                                    </div>
                                                                                @endif
                                                                            </td>
                                                                            <td class="border-0 py-3">
                                                                                <div class="d-flex">
                                                                                    @if($isSubmitted)
                                                                                        <a href="{{ route('students.submissions.view-answers', $allocation) }}" 
                                                                                           class="btn btn-primary btn-sm rounded-pill me-2">
                                                                                            <i class="fas fa-eye me-1"></i> View
                                                                                        </a>
                                                                                    @else
                                                                                        <a href="{{ route('students.submissions.create', ['allocation' => $allocation]) }}" 
                                                                                           class="btn btn-success btn-sm rounded-pill me-2">
                                                                                            <i class="fas fa-paper-plane me-1"></i> Submit
                                                                                        </a>
                                                                                    @endif
                                                                                    @if($allocation->assessment->description)
                                                                                        <button type="button" 
                                                                                                class="btn btn-light btn-sm rounded-pill"
                                                                                                data-bs-toggle="modal" 
                                                                                                data-bs-target="#infoModal{{ $allocation->id }}">
                                                                                            <i class="fas fa-info-circle"></i>
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
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="card border-0 shadow-sm rounded-3 mb-4">
                                                <div class="card-body text-center py-5">
                                                    <div class="empty-state">
                                                        <div class="empty-state-icon mb-3">
                                                            <i class="fas fa-info-circle fa-3x text-muted"></i>
                                                        </div>
                                                        <h5>No assessments found for this module</h5>
                                                        <p class="text-muted">There are no assessments assigned to this module yet.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Progress Tab (UI Only) --}}
                <div class="tab-pane fade" id="progress" role="tabpanel">
                    <!-- Progress content here -->
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-body text-center py-5">
                            <div class="empty-state">
                                <div class="empty-state-icon mb-3">
                                    <i class="fas fa-chart-line fa-3x text-muted"></i>
                                </div>
                                <h5>Assessment progress will be displayed here</h5>
                                <p class="text-muted">Track your progress across all subjects and modules.</p>
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
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow">
                            <div class="modal-header border-0">
                                <h5 class="modal-title">{{ $allocation->assessment->name }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <span class="badge bg-{{ 
                                        $allocation->assessment->type === 'exam' ? 'danger' : 
                                        ($allocation->assessment->type === 'test' ? 'warning' : 
                                        ($allocation->assessment->type === 'practical' ? 'info' : 'primary')) 
                                    }} bg-opacity-10 text-{{ 
                                        $allocation->assessment->type === 'exam' ? 'danger' : 
                                        ($allocation->assessment->type === 'test' ? 'warning' : 
                                        ($allocation->assessment->type === 'practical' ? 'info' : 'primary')) 
                                    }} rounded-pill px-3 py-2">
                                        {{ ucfirst($allocation->assessment->type) }}
                                    </span>
                                </div>
                                <p>{{ $allocation->assessment->description }}</p>
                                <div class="d-flex align-items-center mt-3">
                                    <i class="far fa-calendar-alt text-muted me-2"></i>
                                    <div>
                                        <div class="text-muted small">Due Date</div>
                                        <div>{{ date('M d, Y', strtotime($allocation->due_date)) }} at {{ date('h:i A', strtotime($allocation->due_date)) }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer border-0">
                                <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Close</button>
                                @if($submission && ($submission->status === 'submitted' || $submission->status === 'graded'))
                                    <a href="{{ route('students.submissions.view-answers', $allocation) }}" class="btn btn-primary rounded-pill">
                                        <i class="fas fa-eye me-1"></i> View Submission
                                    </a>
                                @else
                                    <a href="{{ route('students.submissions.create', ['allocation' => $allocation]) }}" class="btn btn-success rounded-pill">
                                        <i class="fas fa-paper-plane me-1"></i> Submit
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    @endforeach
@endforeach

<style>
/* Custom styles for the enrollment view */
.nav-tabs .nav-link {
    color: #6c757d;
    font-weight: 500;
    transition: all 0.2s ease;
}

.nav-tabs .nav-link.active {
    color: #0d6efd;
    font-weight: 600;
    background-color: transparent;
}

.module-scroll {
    scrollbar-width: thin;
    scrollbar-color: #dee2e6 #f8f9fa;
}

.module-scroll::-webkit-scrollbar {
    height: 6px;
}

.module-scroll::-webkit-scrollbar-track {
    background: #f8f9fa;
    border-radius: 10px;
}

.module-scroll::-webkit-scrollbar-thumb {
    background-color: #dee2e6;
    border-radius: 10px;
}

.subject-item.active {
    background-color: rgba(13, 110, 253, 0.1);
    color: #0d6efd;
    font-weight: 500;
}

.subject-item:hover:not(.active) {
    background-color: rgba(0, 0, 0, 0.03);
}

.empty-state {
    padding: 2rem 1rem;
}

.empty-state-icon {
    opacity: 0.5;
}

.table th {
    font-weight: 600;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.card-header {
    background-color: transparent;
}

/* Custom colors for badges */
.text-purple {
    color: #6f42c1;
}

.bg-purple {
    background-color: #6f42c1;
}

.text-orange {
    color: #fd7e14;
}

.bg-orange {
    background-color: #fd7e14;
}

.text-teal {
    color: #20c997;
}

.bg-teal {
    background-color: #20c997;
}

.text-pink {
    color: #e83e8c;
}

.bg-pink {
    background-color: #e83e8c;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize first subject and module
    const firstSubject = document.querySelector('.subject-item');
    const firstModule = document.querySelector('.module-item');
    
    if (firstSubject) {
        firstSubject.classList.add('active');
    }
    
    if (firstModule) {
        firstModule.classList.add('active');
    }
    
    // Add event listeners for subject items
    document.querySelectorAll('.subject-item').forEach(item => {
        item.addEventListener('click', function() {
            const subjectId = this.dataset.subjectId;
            selectSubject(subjectId);
        });
    });
    
    // Add event listeners for module items
    document.querySelectorAll('.module-item').forEach(item => {
        item.addEventListener('click', function() {
            const subjectId = this.dataset.subjectId;
            const moduleId = this.dataset.moduleId;
            selectModule(subjectId, moduleId);
        });
    });
    
    // Function to select a subject
    function selectSubject(subjectId) {
        // Update active subject
        document.querySelectorAll('.subject-item').forEach(item => {
            item.classList.remove('active');
        });
        document.querySelector(`.subject-item[data-subject-id="${subjectId}"]`).classList.add('active');
        
        // Show the correct module container
        document.querySelectorAll('.module-container').forEach(container => {
            container.classList.add('d-none');
        });
        document.getElementById(`modules-${subjectId}`).classList.remove('d-none');
        
        // Select the first module of this subject
        const firstModuleOfSubject = document.querySelector(`.module-item[data-subject-id="${subjectId}"]`);
        if (firstModuleOfSubject) {
            const moduleId = firstModuleOfSubject.dataset.moduleId;
            selectModule(subjectId, moduleId);
        }
    }
    
    // Function to select a module
    function selectModule(subjectId, moduleId) {
        // Update active module
        document.querySelectorAll('.module-item').forEach(item => {
            item.classList.remove('btn-primary');
            item.classList.add('btn-outline-primary');
        });
        const activeModule = document.querySelector(`.module-item[data-subject-id="${subjectId}"][data-module-id="${moduleId}"]`);
        activeModule.classList.remove('btn-outline-primary');
        activeModule.classList.add('btn-primary');
        
        // Show the correct content
        document.querySelectorAll('.module-content').forEach(content => {
            content.classList.add('d-none');
        });
        document.getElementById(`content-${subjectId}-${moduleId}`).classList.remove('d-none');
    }
});
</script>
@endsection