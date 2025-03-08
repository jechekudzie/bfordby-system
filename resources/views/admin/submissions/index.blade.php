@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <div class="card">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">
                        <i class="fas fa-clipboard-check text-primary me-2"></i>Assessment Submissions
                    </h5>
                    <p class="text-muted mb-0">
                        Assessment: <span class="fw-medium">{{ $allocation->assessment->name }}</span>
                        <span class="mx-2">•</span>
                        Type: <span class="fw-medium">{{ ucfirst($allocation->submission_type) }}</span>
                    </p>
                </div>
                <a href="{{ route('admin.modules.assessments.allocations.index', [$allocation->assessment->module, $allocation->assessment]) }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-2"></i>Back
                </a>
            </div>
        </div>
        
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Submission Date</th>
                            <th>Status</th>
                            <th>Grade</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($submissions as $submission)
                            <tr>
                                <td>
                                    {{ $submission->student->first_name }} {{ $submission->student->last_name }}
                                </td>
                                <td>
                                    {{ $submission->submitted_at ? $submission->submitted_at->format('M d, Y - h:i A') : 'N/A' }}
                                </td>
                                <td>
                                    <span class="badge bg-{{ 
                                        $submission->status === 'graded' ? 'success' : 
                                        ($submission->status === 'submitted' ? 'info' : 'warning') 
                                    }}">
                                        {{ ucfirst($submission->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if($submission->status === 'graded')
                                        <div class="fw-medium">{{ $submission->grade }}%</div>
                                        <small class="text-muted">Graded: {{ $submission->graded_at ? $submission->graded_at->format('M d, Y') : 'N/A' }}</small>
                                    @else
                                        <span class="text-muted">Not graded</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.assessment-allocations.submissions.grade', ['allocation' => $allocation, 'submission' => $submission]) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-graduation-cap me-1"></i> {{ $submission->status === 'graded' ? 'Edit Grades' : 'Grade' }}
                                    </a>
                                    @if($submission->file_path)
                                        <a href="{{ route('admin.assessment-allocations.submissions.download', ['allocation' => $allocation, 'submission' => $submission]) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-download me-1"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-2x mb-3"></i>
                                        <p>No submissions found for this assessment.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{ $submissions->links() }}
        </div>
    </div>
</div>
@endsection 