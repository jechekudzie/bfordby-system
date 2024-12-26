<div class="modal fade" id="enrollCourseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Enroll in Course</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.student-courses.store') }}" method="POST" id="enrollCourseForm">
                @csrf
                <input type="hidden" name="student_id" value="{{ $student->id }}">
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" for="course_id">Course</label>
                        <select class="form-select" name="course_id" id="course_id" required>
                            <option value="">Select Course</option>
                            @foreach($availableCourses as $course)
                                <option value="{{ $course->id }}">
                                    {{ $course->name }} ({{ $course->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="enrollment_date">Enrollment Date</label>
                        <input type="date" class="form-control" name="enrollment_date" 
                               id="enrollment_date" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Enroll</button>
                </div>
            </form>
        </div>
    </div>
</div>
