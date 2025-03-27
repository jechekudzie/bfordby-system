@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-tasks text-primary me-2"></i>Assessments
                </h5>
                <a href="{{ url()->previous() }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Back
                </a>
            </div>
        </div>

        <div class="card-body">
            @forelse($assessments as $semesterName => $semesterAssessments)
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
                                        <th class="border-0 py-3">Due Date</th>
                                        <th class="border-0 py-3">Status</th>
                                        <th class="border-0 py-3">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($semesterAssessments as $allocation)
                                        @php
                                            $submission = $allocation->submissions->first();
                                            $isSubmitted = $submission && ($submission->status === 'submitted' || $submission->status === 'graded');
                                        @endphp
                                        <tr>
                                            <td class="border-0 py-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="assessment-icon me-3 rounded-circle p-2 d-flex align-items-center justify-content-center" 
                                                         style="width: 40px; height: 40px;"
                                                         class="bg-{{ 
                                                            $allocation->assessment->type === 'exam' ? 'danger' : 
                                                            ($allocation->assessment->type === 'test' ? 'warning' : 
                                                            ($allocation->assessment->type === 'practical' ? 'info' : 'primary')) 
                                                         }} bg-opacity-10">
                                                        <i class="fas fa-file-alt text-{{ 
                                                            $allocation->assessment->type === 'exam' ? 'danger' : 
                                                            ($allocation->assessment->type === 'test' ? 'warning' : 
                                                            ($allocation->assessment->type === 'practical' ? 'info' : 'primary')) 
                                                        }}"></i>
                                                    </div>
                                                    <div>
                                                        <strong>{{ $allocation->assessment->name }}</strong>
                                                        <div class="text-muted small">
                                                            {{ $allocation->assessment->module->subject->name }} - 
                                                            {{ $allocation->assessment->module->name }}
                                                        </div>
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
                                                <div class="d-flex align-items-center">
                                                    <i class="far fa-calendar-alt text-muted me-2"></i>
                                                    @if($allocation->due_date)
                                                        {{ date('M d, Y', strtotime($allocation->due_date)) }}
                                                    @else
                                                        <span class="text-muted">Not set</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="border-0 py-3">
                                                @if($isSubmitted)
                                                    <div class="d-flex align-items-center text-success">
                                                        <i class="fas fa-check-circle me-2"></i>
                                                        <div>
                                                            <div>File Already Submitted</div>
                                                            <small class="text-muted">
                                                                Uploaded {{ $submission->submitted_at->diffForHumans() }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-2">
                                                        Pending
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="border-0 py-3">
                                                @if($isSubmitted)
                                                    <a href="{{ route('students.submissions.view-answers', $allocation) }}" 
                                                       class="btn btn-primary btn-sm rounded-pill">
                                                        <i class="fas fa-eye me-1"></i>View
                                                    </a>
                                                @else
                                                    <a href="{{ route('students.submissions.create', ['allocation' => $allocation]) }}" 
                                                       class="btn btn-success btn-sm rounded-pill">
                                                        <i class="fas fa-paper-plane me-1"></i>Submit
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-4">
                    <div class="empty-state">
                        <div class="empty-state-icon mb-3">
                            <i class="fas fa-tasks fa-3x text-muted"></i>
                        </div>
                        <h5>No Assessments Found</h5>
                        <p class="text-muted">There are no assessments assigned to you at this time.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>

<style>
.empty-state {
    padding: 2rem 1rem;
}

.empty-state-icon {
    opacity: 0.5;
}
</style>
@endsection
