@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">
                    <i class="fas fa-calendar-alt text-primary me-2"></i>Assessment Allocations
                </h5>
                <div class="text-muted">
                    <div class="mb-1">
                        Course: <span class="fw-bold text-dark">{{ $module->subject->course->name }}</span>
                    </div>
                    <div class="mb-1">
                        Subject: <span class="fw-bold text-dark">{{ $module->subject->name }}</span>
                        <span class="mx-2">•</span>
                        Code: <span class="fw-bold text-dark">{{ $module->subject->code }}</span>
                    </div>
                    <div class="mb-1">
                        Module: <span class="fw-bold text-dark">{{ $module->name }}</span>
                    </div>
                    <div>
                        Assessment: <span class="fw-bold text-dark">{{ $assessment->name }}</span>
                        <span class="badge bg-{{ 
                            $assessment->type === 'exam' ? 'danger' : 
                            ($assessment->type === 'test' ? 'warning' : 
                            ($assessment->type === 'practical' ? 'info' : 
                            ($assessment->type === 'theory' ? 'primary' : 'success'))) 
                        }} ms-2">{{ ucfirst($assessment->type) }}</span>
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.courses.subjects.modules.assessments.index', [$module->subject->slug, $module]) }}" 
                   class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Assessments
                </a>
                <a href="{{ route('admin.modules.assessments.allocations.create', [$module, $assessment]) }}" 
                   class="btn btn-primary">
                    <i class="fas fa-plus-circle me-2"></i>Add Allocation
                </a>
            </div>
        </div>
    </div>

    <div class="card-body">
        @if($allocations->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Enrollment Code</th>
                            <th>Semester</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th>Files/Content</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allocations as $allocation)
                            <tr>
                                <td>{{ $allocation->enrollmentCode->base_code }}</td>
                                <td>{{ $allocation->semester->name }}</td>
                                <td>{{ date('M d, Y', strtotime($allocation->due_date)) }}</td>
                                <td>
                                    <span class="badge bg-{{ 
                                        $allocation->status === 'graded' ? 'success' : 
                                        ($allocation->status === 'submitted' ? 'info' : 'warning') 
                                    }}">
                                        {{ ucfirst($allocation->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if($allocation->file_path)
                                        <a href="{{ asset('storage/' . $allocation->file_path) }}" 
                                           class="btn btn-sm btn-outline-primary"
                                           target="_blank">
                                            <i class="fas fa-file-download me-1"></i>Download
                                        </a>
                                    @endif
                                    @if($allocation->content)
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-info"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#contentModal{{ $allocation->id }}">
                                            <i class="fas fa-file-alt me-1"></i>View Content
                                        </button>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('modules.assessments.allocations.edit', 
                                                 [$module, $assessment, $allocation]) }}" 
                                           class="btn btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('modules.assessments.allocations.destroy', 
                                                     [$module, $assessment, $allocation]) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this allocation?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $allocations->links() }}
            </div>
        @else
            <div class="text-center py-4">
                <img src="{{ asset('images/illustrations/empty.svg') }}" alt="No Allocations" 
                     class="img-fluid mb-3" style="max-width: 200px;">
                <h5 class="text-muted mb-2">No Allocations Found</h5>
                <p class="text-muted mb-3">Start by adding your first allocation</p>
                <a href="{{ route('modules.assessments.allocations.create', [$module, $assessment]) }}" 
                   class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add First Allocation
                </a>
            </div>
        @endif
    </div>
</div>

{{-- Content Modals --}}
@foreach($allocations as $allocation)
    <div class="modal fade" id="contentModal{{ $allocation->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Assessment Content</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="p-3 bg-light rounded">
                        {!! nl2br(e($allocation->content)) !!}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endforeach
@endsection 