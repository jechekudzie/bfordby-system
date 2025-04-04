@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">
                    <i class="fas fa-cubes text-primary me-2"></i>Modules
                </h5>
                <div class="text-muted">
                    <div class="mb-1">
                        Course: <span class="fw-bold text-dark">{{ $subject->course->name }}</span>
                    </div>
                    <div>
                        Discipline: <span class="fw-bold text-dark">{{ $subject->name }}</span>
                        <span class="mx-2">•</span>
                        Code: <span class="fw-bold text-dark">{{ $subject->code }}</span>
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.courses.subjects.index', $subject->course) }}" 
                   class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Disciplines
                </a>
                <a href="{{ route('admin.courses.subjects.modules.create', $subject) }}" 
                   class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add Module
                </a>
            </div>
        </div>
    </div>

    <div class="card-body">
        @if($modules->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Module Details</th>
                            <th>Assessments</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($modules as $module)
                            <tr>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-medium text-dark">{{ $module->name }}</span>
                                        @if($module->description)
                                            <small class="text-muted">{{ $module->description }}</small>
                                        @endif
                                        <div class="mt-2">
                                            <a href="{{ route('admin.courses.subjects.modules.assessments.index', [$subject, $module]) }}" 
                                               class="btn btn-link btn-sm text-primary p-0">
                                                <i class="fas fa-tasks me-1"></i>
                                                <span>Manage Assessments</span>
                                            </a>
                                            <a href="{{ route('admin.courses.subjects.modules.contents.index', [$subject, $module]) }}" 
                                               class="btn btn-link btn-sm text-primary p-0 ms-3">
                                                <i class="fas fa-folder-open me-1"></i>
                                                <span>Manage Content</span>
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge rounded-pill bg-info">
                                        <i class="fas fa-tasks me-1"></i>
                                        {{ $module->assessments->count() ?? 0 }} Assessments
                                    </span>
                                    <span class="badge rounded-pill bg-success ms-1">
                                        <i class="fas fa-folder-open me-1"></i>
                                        {{ $module->contents->count() ?? 0 }} Content Items
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.courses.subjects.modules.edit', [$subject, $module]) }}" 
                                           class="btn btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.courses.subjects.modules.destroy', [$subject, $module]) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this module?');">
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
                {{ $modules->links() }}
            </div>
        @else
            <div class="text-center py-4">
                <img src="{{ asset('images/illustrations/empty.svg') }}" alt="No Modules" 
                     class="img-fluid mb-3" style="max-width: 200px;">
                <h5 class="text-muted mb-2">No Modules Found</h5>
                <p class="text-muted mb-3">Start by adding your first module to this subject</p>
                <a href="{{ route('admin.courses.subjects.modules.create', $subject) }}" 
                   class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add First Module
                </a>
            </div>
        @endif
    </div>
</div>
@endsection 