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
                            <div class="d-flex flex-column">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="bg-brand-primary-light rounded-circle p-2 me-3 rounded-circle-icon">
                                        <i class="fas fa-graduation-cap text-brand-primary"></i>
                                    </div>
                                    <h4 class="mb-0">{{ $enrollment->course->name }}</h4>
                                </div>

                                <div class="mt-2 mb-4">
                                    <span class="badge badge-brand-primary rounded-pill px-3 py-2">
                                        <i class="fas fa-clock me-1"></i> {{ $enrollment->studyMode->name }}
                                    </span>
                                </div>
                                
                                <div class="row mt-3">
                                    <div class="col-md-6 mb-3">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-calendar-alt text-brand-primary me-2"></i>
                                            <div>
                                                <div class="text-muted small">Enrollment Date</div>
                                                <div>{{ date('M d, Y', strtotime($enrollment->created_at)) }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-hourglass-half text-brand-primary me-2"></i>
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
                        <div class="col-md-4 p-4 bg-brand-primary-light">
                            <div class="d-flex flex-column h-100">
                                <div class="mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="bg-brand-secondary-light rounded-circle p-1 me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                            <i class="fas fa-user text-brand-primary"></i>
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
                                    @php
                                        $statusClass = $enrollment->status === 'active' || $enrollment->status === 'completed' 
                                            ? 'badge-brand-primary' 
                                            : ($enrollment->status === 'withdrawn' ? 'bg-danger' : 'badge-brand-secondary');
                                    @endphp
                                    <span class="badge {{ $statusClass }} rounded-pill px-3 py-2">
                                        <i class="fas fa-circle me-1 small"></i>{{ ucfirst($enrollment->status) }}
                                    </span>
                                    <a href="{{ route('students.show', $enrollment->student) }}" 
                                       class="btn btn-sm px-3 border border-brand-primary text-brand-primary rounded-pill">
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

    <!-- Simple Tabs -->
    <div class="row">
        <div class="col-12">
            <ul class="nav nav-pills mb-4" id="enrollmentTabs">
                <li class="nav-item">
                    <a class="nav-link active px-4 py-2" href="#subjects" data-bs-toggle="pill">
                        <i class="fas fa-book-open me-2"></i>Subjects & Assessments
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-4 py-2" href="#progress" data-bs-toggle="pill">
                        <i class="fas fa-chart-line me-2"></i>Assessment Progress
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <!-- Subjects Tab -->
                <div class="tab-pane fade show active" id="subjects">
                    <!-- Subject Selector -->
                    <div class="card border-0 shadow-sm rounded-3 mb-4">
                        <div class="card-header py-3 bg-brand-primary">
                            <h5 class="mb-0 text-white"><i class="fas fa-book me-2"></i>Subjects</h5>
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
                    
                    <!-- Subject Content Area -->
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
                                            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" 
                                                 id="module-{{ $subject->id }}-{{ $module->id }}">
                                                
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
                                                                                                            <div class="modal-header bg-brand-primary">
                                                                                                                <h5 class="modal-title">{{ $allocation->assessment->name }}</h5>
                                                                                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                                                            </div>
                                                                                                            <div class="modal-body">
                                                                                                                <div class="mb-3">
                                                                                                                    <strong>Weight:</strong> {{ $allocation->assessment->weight }}%
                                                                                                                </div>
                                                                                                                <div class="mb-3">
                                                                                                                    <strong>Due Date:</strong> {{ date('d M Y, H:i', strtotime($allocation->due_date)) }}
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
                
                <!-- Progress Tab -->
                <div class="tab-pane fade" id="progress">
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-body text-center py-5">
                            <div class="empty-state">
                                <div class="empty-state-icon mb-3">
                                    <i class="fas fa-chart-line fa-3x text-brand-primary" style="opacity: 0.5;"></i>
                                </div>
                                <h5 class="text-brand-primary">Assessment progress will be displayed here</h5>
                                <p class="text-muted">Track your progress across all subjects and modules.</p>
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
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab behavior using Bootstrap's API
    var triggerTabList = [].slice.call(document.querySelectorAll('#enrollmentTabs a'))
    triggerTabList.forEach(function (triggerEl) {
        var tabTrigger = new bootstrap.Tab(triggerEl)
        triggerEl.addEventListener('click', function (event) {
            event.preventDefault()
            tabTrigger.show()
        })
    })
    
    // Subject card selection
    const subjectCards = document.querySelectorAll('.subject-card');
    const subjectContents = document.querySelectorAll('.subject-content');
    
    // Set first subject content as active by default
    if (subjectContents.length > 0) {
        subjectContents[0].classList.add('active');
    }
    
    subjectCards.forEach(card => {
        card.addEventListener('click', function() {
            // Remove active class from all cards
            subjectCards.forEach(c => c.classList.remove('active'));
            
            // Add active class to clicked card
            this.classList.add('active');
            
            // Get subject ID
            const subjectId = this.getAttribute('data-subject-id');
            
            // Hide all subject content
            subjectContents.forEach(content => {
                content.classList.remove('active');
            });
            
            // Show selected subject content
            const subjectContent = document.getElementById('subject-content-' + subjectId);
            if (subjectContent) {
                subjectContent.classList.add('active');
            }
        });
    });
    
    // Activate first subject card by default
    if (subjectCards.length > 0) {
        subjectCards[0].classList.add('active');
    }
    
    // Update the module tab handler to use classes instead of inline styles
    document.querySelectorAll('.module-tabs .nav-link').forEach(function(tab) {
        tab.addEventListener('click', function() {
            // Remove white text class from all tabs
            document.querySelectorAll('.module-tabs .nav-link').forEach(function(t) {
                t.classList.remove('text-white-force');
            });
            
            // Add white text class to active tab
            this.classList.add('text-white-force');
        });
    });
    
    // Ensure first module tab has white text initially
    document.querySelectorAll('.module-tabs').forEach(function(moduleTabSet) {
        const firstTab = moduleTabSet.querySelector('.nav-link.active');
        if (firstTab) {
            firstTab.classList.add('text-white-force');
        }
    });
});
</script>
@endsection 
