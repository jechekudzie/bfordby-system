@extends('layouts.backend')

@section('content')
<div class="card mb-3">
    <div class="card-header">
        <div class="row flex-between-end">
            <div class="col-auto align-self-center">
                <h5 class="mb-0">Student Details</h5>
            </div>
            <div class="col-auto ms-auto">
                <div class="btn-group btn-group-sm">
                    <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-falcon-primary">
                        <span class="fas fa-edit"></span> Edit
                    </a>
                    <a href="{{ route('admin.students.index') }}" class="btn btn-falcon-default">
                        <span class="fas fa-list"></span> Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body bg-light">
        <div class="row g-3">
            <!-- Personal Information Card -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <span class="fas fa-user me-2"></span>Personal Information
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <strong>Full Name:</strong>
                                    <p class="mb-0">{{ $student->first_name }} {{ $student->last_name }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <strong>Email:</strong>
                                    <p class="mb-0">{{ $student->email }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <strong>Phone:</strong>
                                    <p class="mb-0">{{ $student->phone ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <strong>Date of Birth:</strong>
                                    <p class="mb-0">{{ $student->date_of_birth->format('M d, Y') }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <strong>Gender:</strong>
                                    <p class="mb-0">{{ $student->gender->name }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <strong>Status:</strong>
                                    <p class="mb-0">
                                        <span class="badge bg-{{ $student->status === 'active' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($student->status) }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Academic Information Card -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <span class="fas fa-graduation-cap me-2"></span>Academic Information
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <strong>Enrollment Date:</strong>
                                    <p class="mb-0">{{ $student->enrollment_date->format('M d, Y') }}</p>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-2">
                                    <strong>Enrolled Courses:</strong>
                                    <div class="mt-2">
                                        @forelse($student->courses as $course)
                                            <div class="card mb-2">
                                                <div class="card-body">
                                                    <h6 class="card-title">{{ $course->name }}</h6>
                                                    <p class="card-text">
                                                        <span class="badge bg-info">{{ ucfirst($course->study_mode) }}</span>
                                                        <small class="text-muted ms-2">{{ $course->description }}</small>
                                                    </p>
                                                </div>
                                            </div>
                                        @empty
                                            <p class="text-muted">No courses enrolled</p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Notes Card -->
            @if($student->notes)
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <span class="fas fa-sticky-note me-2"></span>Additional Notes
                        </h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ $student->notes }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- System Information Card -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <span class="fas fa-info-circle me-2"></span>System Information
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <strong>Created At:</strong>
                                    <p class="mb-0">{{ $student->created_at->format('M d, Y H:i A') }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <strong>Last Updated:</strong>
                                    <p class="mb-0">{{ $student->updated_at->format('M d, Y H:i A') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
