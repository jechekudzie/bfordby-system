@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">
                    <i class="fas fa-book-open me-1 text-primary"></i>Course Subjects
                </h5>
                <p class="mb-0 text-muted">
                    Course: <span class="fw-bold text-dark">{{ $course->name }}</span>
                    <span class="mx-2">•</span>
                    Code: <span class="fw-bold text-dark">{{ $course->code }}</span>
                </p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-1"></i>Back to Courses
                </a>
                <a href="{{ route('admin.courses.subjects.create', $course) }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-1"></i>Add Subject
                </a>
            </div>
        </div>
    </div>

    <div class="card-body">
        @if($subjects->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="subjectsTable">
                    <thead class="table-light">
                        <tr>
                            <th>Subject Details</th>
                            <th>Credit Hours</th>
                            <th>Modules</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subjects as $subject)
                            <tr>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-medium text-dark">{{ $subject->name }}</span>
                                        <div class="text-muted small">Code: {{ $subject->code }}</div>
                                        @if($subject->description)
                                            <small class="text-muted">{{ $subject->description }}</small>
                                        @endif
                                        <div class="mt-2 d-flex gap-2">
                                            <a href="{{ route('admin.courses.subjects.modules.index', $subject) }}" 
                                               class="btn btn-link btn-sm text-primary p-0">
                                                <i class="fas fa-cubes me-1"></i>
                                                <span>View Modules</span>
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info">
                                        {{ number_format($subject->credit_hours, 2) }} Hours
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">
                                        {{ $subject->modules->count() ?? 0 }} Modules
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.courses.subjects.edit', [$course, $subject]) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit me-2"></i>
                                        </a>
                                        <form action="{{ route('admin.courses.subjects.destroy', [$course, $subject]) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this subject?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash me-2"></i>
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
                {{ $subjects->links() }}
            </div>
        @else
            <div class="text-center py-4">
                <img src="{{ asset('images/illustrations/empty.svg') }}" alt="No Subjects" 
                     class="img-fluid mb-3" style="max-width: 200px;">
                <h5 class="text-muted mb-2">No Subjects Found</h5>
                <p class="text-muted mb-3">Start by adding your first subject to this course</p>
                <a href="{{ route('admin.courses.subjects.create', $course) }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add First Subject
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
