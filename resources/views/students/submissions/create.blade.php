@extends('students.submissions.layouts.base')

@section('submission_content')
    @if($allocation->is_timed && !$submission->start_time)
        <!-- Timed Assessment Warning -->
        <div class="alert alert-warning">
            <div class="d-flex">
                <div class="me-3">
                    <i class="fas fa-stopwatch fa-2x"></i>
                </div>
                <div>
                    <h5 class="alert-heading">Timed Assessment</h5>
                    <p>This is a timed assessment. Once you start, you will have <strong>{{ $allocation->duration_minutes }} minutes</strong> to complete it.</p>
                    <p class="mb-0">Make sure you have enough time to complete the assessment before starting.</p>
                </div>
            </div>
            
            <div class="mt-3 text-center">
                <form action="{{ route('students.submissions.start-timed', $allocation) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-play me-2"></i>Start Assessment
                    </button>
                </form>
            </div>
        </div>
    @endif
    
    @if($allocation->submission_type === 'group' || (isset($isGroupSubmission) && $isGroupSubmission))
        <!-- Group Submission Section -->
        <div class="card mb-4 border-primary">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-users me-2"></i>Group Submission
                </h5>
            </div>
            <div class="card-body">
                @if($group)
                    <div class="alert alert-info">
                        <div class="d-flex">
                            <div class="me-3">
                                <i class="fas fa-info-circle fa-2x"></i>
                            </div>
                            <div>
                                <h5 class="alert-heading">Group Information</h5>
                                <p>You are submitting as part of group: <strong>{{ $group->name }}</strong></p>
                                <p class="mb-0">All members of your group will receive the same grade for this submission.</p>
                            </div>
                        </div>
                    </div>
                    
                    <h6 class="mb-3">Group Members:</h6>
                    <div class="list-group mb-4">
                        @foreach($group->students as $groupMember)
                            <div class="list-group-item d-flex align-items-center">
                                <i class="fas fa-user text-primary me-3"></i>
                                <div>
                                    <strong>{{ $groupMember->first_name }} {{ $groupMember->last_name }}</strong>
                                    @if($groupMember->id === $student->id)
                                        <span class="badge bg-primary ms-2">You (Submitter)</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="d-grid gap-2">
                        @if($allocation->questions->isNotEmpty())
                            <a href="{{ route('students.submissions.show', ['allocation' => $allocation, 'group' => 1]) }}" class="btn btn-success btn-lg">
                                <i class="fas fa-users me-2"></i>Submit Group Assignment
                            </a>
                        @else
                            <a href="{{ route('students.submissions.types.upload', ['allocation' => $allocation, 'group' => 1]) }}" class="btn btn-success btn-lg">
                                <i class="fas fa-users me-2"></i>Submit Group Assignment
                            </a>
                        @endif
                    </div>
                @else
                    <div class="alert alert-warning">
                        <div class="d-flex">
                            <div class="me-3">
                                <i class="fas fa-exclamation-triangle fa-2x"></i>
                            </div>
                            <div>
                                <h5 class="alert-heading">Group Required</h5>
                                <p>This is a group assessment. You need to join or create a group before you can submit.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('assessment-allocation-groups.index', $allocation) }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-users me-2"></i>Join or Create a Group
                        </a>
                    </div>
                @endif
            </div>
        </div>
    @endif
    
    @if($allocation->assessment->description)
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="fas fa-info-circle text-primary me-2"></i>Assessment Instructions
                </h6>
            </div>
            <div class="card-body">
                <div class="p-3 bg-light rounded">
                    {!! nl2br(e($allocation->assessment->description)) !!}
                </div>
            </div>
        </div>
    @endif
    
    @if($allocation->due_date)
        <div class="alert alert-info mb-4">
            <div class="d-flex align-items-center">
                <i class="fas fa-calendar-alt text-info me-3 fa-lg"></i>
                <div>
                    <strong>Due Date:</strong> {{ date('F j, Y, g:i a', strtotime($allocation->due_date)) }}
                    @php
                        $now = new DateTime();
                        $due = new DateTime($allocation->due_date);
                        $diff = $now->diff($due);
                        $isOverdue = $now > $due;
                    @endphp
                    
                    @if($isOverdue)
                        <span class="badge bg-danger ms-2">Overdue</span>
                    @elseif($diff->days < 1)
                        <span class="badge bg-warning ms-2">Due Soon</span>
                    @endif
                </div>
            </div>
        </div>
    @endif
    
    @if(!$allocation->is_timed || $submission->start_time)
        @if($allocation->submission_type !== 'group' || $group)
            <div class="d-grid gap-2">
                @if($allocation->submission_type === 'upload')
                    <a href="{{ route('students.submissions.types.upload', $allocation) }}" class="btn btn-success btn-lg">
                        <i class="fas fa-file-upload me-2"></i>Upload and Submit File
                    </a>
                @elseif($allocation->submission_type === 'online')
                    <a href="{{ route('students.submissions.show', $allocation) }}" class="btn btn-success btn-lg">
                        <i class="fas fa-edit me-2"></i>Take and Submit Online Assessment
                    </a>
                @elseif($allocation->submission_type === 'in-class')
                    <div class="alert alert-info">
                        <i class="fas fa-chalkboard-teacher me-2"></i>
                        This is an in-class assessment. Please attend your scheduled class session.
                    </div>
                @endif
            </div>
        @endif
    @endif
@endsection 