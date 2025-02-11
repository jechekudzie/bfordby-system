@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">
                    <i class="fas fa-tasks text-primary me-2"></i>Subject Assessments
                </h5>
                <p class="text-muted mb-0 mt-2">
                    <span class="h5 fw-bold text-dark">
                        Subject: {{ $subject->name }}
                        <span class="mx-2">•</span>
                        Course: {{ $subject->course->name }}
                    </span>
                </p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.subjects.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Subjects
                </a>
                <a href="{{ route('admin.assessments.create', ['subject' => $subject->id]) }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add Assessment
                </a>
            </div>
        </div>
    </div>

    <div class="card-body">
        @if($assessments->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Assessment Details</th>
                            <th>Type</th>
                            <th>Max Score</th>
                            <th>Status</th>
                            <th>Allocations</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assessments as $assessment)
                            <tr>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-medium text-dark">{{ $assessment->name }}</span>
                                        @if($assessment->description)
                                            <small class="text-muted">{{ $assessment->description }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="badge rounded-pill bg-{{ 
                                        $assessment->type === 'exam' ? 'danger' : 
                                        ($assessment->type === 'test' ? 'warning' : 
                                        ($assessment->type === 'practical' ? 'info' : 
                                        ($assessment->type === 'theory' ? 'primary' : 'success'))) 
                                    }} bg-opacity-75">
                                        <i class="fas fa-{{ 
                                            $assessment->type === 'exam' ? 'file-alt' : 
                                            ($assessment->type === 'test' ? 'pencil-alt' : 
                                            ($assessment->type === 'practical' ? 'flask' : 
                                            ($assessment->type === 'theory' ? 'book' : 'clipboard'))) 
                                        }} me-1"></i>
                                        {{ ucfirst($assessment->type) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-dark bg-opacity-10 text-dark">
                                        {{ number_format($assessment->max_score, 2) }} points
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ 
                                        $assessment->status === 'published' ? 'success' : 
                                        ($assessment->status === 'archived' ? 'secondary' : 'warning') 
                                    }}">
                                        {{ ucfirst($assessment->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.assessment-allocations.index', $assessment) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        View Allocations
                                    </a>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.assessments.edit', $assessment) }}" 
                                           class="btn btn-outline-primary"
                                           title="Edit Assessment">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-outline-danger"
                                                onclick="confirmDelete('{{ route('admin.assessments.destroy', $assessment) }}')"
                                                title="Delete Assessment">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $assessments->links() }}
            </div>
        @else
            <div class="text-center py-4">
                <img src="{{ asset('images/illustrations/empty.svg') }}" alt="No Assessments" 
                     class="img-fluid mb-3" style="max-width: 200px;">
                <h5 class="text-muted mb-2">No Assessments Found</h5>
                <p class="text-muted mb-3">Start by adding your first assessment for this subject</p>
                <a href="{{ route('admin.assessments.create', ['subject' => $subject->id]) }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add First Assessment
                </a>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(deleteUrl) {
    if (confirm('Are you sure you want to delete this assessment?')) {
        window.location.href = deleteUrl;
    }
}
</script>
@endpush
@endsection 