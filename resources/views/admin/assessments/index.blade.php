@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">
                    <i class="fas fa-tasks text-primary me-2"></i>Module Assessments
                </h5>
                <div class="text-muted">
                    <div class="mb-1">
                        Module: <span class="fw-bold text-dark">{{ $module->name }}</span>
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.courses.subjects.modules.index', [$subject->slug, $module]) }}" 
                   class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Modules
                </a>
                <a href="{{ route('admin.courses.subjects.modules.assessments.create', [$subject->slug, $module]) }}" 
                   class="btn btn-primary">
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
                                        <div class="mt-2">
                                            <a href="{{ route('admin.modules.assessments.allocations.index', [$module, $assessment]) }}" 
                                               class="btn btn-link btn-sm text-primary p-0">
                                                <i class="fas fa-calendar-alt me-1"></i>
                                                <span>Manage Allocations</span>
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge rounded-pill bg-{{ 
                                        $assessment->type === 'exam' ? 'danger' : 
                                        ($assessment->type === 'test' ? 'warning' : 
                                        ($assessment->type === 'practical' ? 'info' : 
                                        ($assessment->type === 'theory' ? 'primary' : 'success'))) 
                                    }}">
                                        {{ ucfirst($assessment->type) }}
                                    </span>
                                </td>
                                <td>{{ $assessment->max_score }}</td>
                                <td>
                                    <span class="badge bg-{{ 
                                        $assessment->status === 'published' ? 'success' : 
                                        ($assessment->status === 'draft' ? 'warning' : 'secondary') 
                                    }}">
                                        {{ ucfirst($assessment->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.courses.subjects.modules.assessments.edit', [$subject->slug, $module, $assessment]) }}" 
                                           class="btn btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.courses.subjects.modules.assessments.destroy', [$subject->slug, $module, $assessment]) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this assessment?');">
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
                {{ $assessments->links() }}
            </div>
        @else
            <div class="text-center py-4">
                <img src="{{ asset('images/illustrations/empty.svg') }}" alt="No Assessments" 
                     class="img-fluid mb-3" style="max-width: 200px;">
                <h5 class="text-muted mb-2">No Assessments Found</h5>
                <p class="text-muted mb-3">Start by adding your first assessment to this module</p>
                <a href="{{ route('admin.courses.subjects.modules.assessments.create', [$subject->slug, $module]) }}" 
                   class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add First Assessment
                </a>
            </div>
        @endif
    </div>
</div>
@endsection 