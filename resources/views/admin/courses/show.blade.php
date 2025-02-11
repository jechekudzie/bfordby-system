@extends('layouts.admin')

@section('content')
<div class="row">
    <!-- Course Overview Card -->
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">
                            <i class="fas fa-graduation-cap text-primary me-2"></i>Course Details
                        </h5>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Courses
                        </a>
                        <a href="{{ route('admin.courses.edit', $course) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Edit Course
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-12">
                        <h4 class="text-dark mb-1">{{ $course->name }}</h4>
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <span class="badge bg-primary">Code: {{ $course->code }}</span>
                            <span class="badge bg-{{ $course->status === 'active' ? 'success' : 'danger' }}">
                                {{ ucfirst($course->status) }}
                            </span>
                        </div>
                        @if($course->description)
                            <p class="text-muted mb-4">{{ $course->description }}</p>
                        @endif
                    </div>

                    <div class="col-md-6">
                        <div class="card bg-light border-0">
                            <div class="card-body">
                                <h6 class="card-title text-dark">
                                    <i class="fas fa-clock text-primary me-2"></i>Duration
                                </h6>
                                <p class="card-text">
                                    {{ $course->duration_months }} Months
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card bg-light border-0">
                            <div class="card-body">
                                <h6 class="card-title text-dark">
                                    <i class="fas fa-money-bill text-primary me-2"></i>Course Fee
                                </h6>
                                <p class="card-text">
                                    {{ number_format($course->fee, 2) }} USD
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Subjects List -->
        <div class="card">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-book text-primary me-2"></i>Course Subjects
                    </h5>
                    <a href="{{ route('admin.courses.subjects.create', $course) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-2"></i>Add Subject
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($course->subjects->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Subject</th>
                                    <th>Code</th>
                                    <th>Credit Hours</th>
                                    <th>Modules</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($course->subjects as $subject)
                                    <tr>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="fw-medium text-dark">{{ $subject->name }}</span>
                                                @if($subject->description)
                                                    <small class="text-muted">{{ Str::limit($subject->description, 50) }}</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $subject->code }}</span>
                                        </td>
                                        <td>{{ number_format($subject->credit_hours, 2) }}</td>
                                        <td>
                                            <a href="{{ route('admin.courses.subjects.modules.index', [$course, $subject]) }}" 
                                               class="btn btn-link btn-sm p-0">
                                                <span class="badge bg-info">
                                                    {{ $subject->modules_count ?? 0 }} Modules
                                                </span>
                                            </a>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('admin.courses.subjects.edit', [$course, $subject]) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.courses.subjects.destroy', [$course, $subject]) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('Are you sure you want to delete this subject?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
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
                @else
                    <div class="text-center py-4">
                        <img src="{{ asset('images/illustrations/empty.svg') }}" alt="No Subjects" 
                             class="img-fluid mb-3" style="max-width: 200px;">
                        <h5 class="text-muted mb-2">No Subjects Added Yet</h5>
                        <p class="text-muted mb-3">Start by adding subjects to this course</p>
                        <a href="{{ route('admin.courses.subjects.create', $course) }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Add First Subject
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-md-4">
        <!-- Study Modes -->
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-alt text-primary me-2"></i>Study Modes
                </h5>
            </div>
            <div class="card-body">
                @if($course->studyModes->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($course->studyModes as $mode)
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span>{{ $mode->name }}</span>
                                <span class="badge bg-primary rounded-pill">
                                    {{ $mode->enrollments_count ?? 0 }} Students
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted mb-0">No study modes assigned</p>
                @endif
            </div>
        </div>

        <!-- Course Statistics -->
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar text-primary me-2"></i>Course Statistics
                </h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span>Total Subjects</span>
                        <span class="badge bg-primary rounded-pill">
                            {{ $course->subjects->count() }}
                        </span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span>Total Modules</span>
                        <span class="badge bg-info rounded-pill">
                            {{ $course->subjects->sum(function($subject) {
                                return $subject->modules->count();
                            }) }}
                        </span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span>Total Credit Hours</span>
                        <span class="badge bg-success rounded-pill">
                            {{ number_format($course->subjects->sum('credit_hours'), 2) }}
                        </span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span>Active Enrollments</span>
                        <span class="badge bg-warning rounded-pill">
                            {{ $course->enrollments->where('status', 'active')->count() }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 