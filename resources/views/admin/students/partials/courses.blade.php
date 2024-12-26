<div class="card mb-3">
    <div class="card-header">
        <div class="row flex-between-end">
            <div class="col-auto align-self-center">
                <h5 class="mb-0">Enrolled Courses</h5>
            </div>
            <div class="col-auto ms-auto">
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#enrollCourseModal">
                    <span class="fas fa-plus me-2"></span>Enroll in Course
                </button>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0">
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>Code</th>
                        <th>Enrollment Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($student->studentCourses as $enrollment)
                        <tr>
                            <td>{{ $enrollment->course->name }}</td>
                            <td>{{ $enrollment->course->code }}</td>
                            <td>{{ $enrollment->enrollment_date->format('M d, Y') }}</td>
                            <td>
                                <span class="badge bg-{{ $enrollment->status_color }}">
                                    {{ $enrollment->status }}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-danger delete-enrollment" 
                                        data-id="{{ $enrollment->id }}">
                                    <span class="fas fa-trash"></span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-3">No courses enrolled yet</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
