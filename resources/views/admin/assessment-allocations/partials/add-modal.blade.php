<div class="modal fade" id="allocationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Assessment Allocation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.assessment-allocations.store', $assessment) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="enrollment_code_id" class="form-label">Enrollment Code</label>
                        <select name="enrollment_code_id" id="enrollment_code_id" class="form-select" required>
                            <option value="">Select Enrollment Code</option>
                            @foreach($enrollmentCodes as $code)
                                <option value="{{ $code->id }}">
                                    {{ $code->base_code }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="semester_id" class="form-label">Semester</label>
                        <select name="semester_id" id="semester_id" class="form-select" required>
                            <option value="">Select Semester</option>
                            @foreach($semesters as $semester)
                                <option value="{{ $semester->id }}">{{ $semester->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="due_date" class="form-label">Due Date</label>
                        <input type="date" class="form-control datetimepicker" id="due_date" name="due_date" required>
                    </div>

                    <div class="mb-3">
                        <label for="content" class="form-label">Content</label>
                        <textarea class="form-control" id="content" name="content" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="file_path" class="form-label">File Attachment</label>
                        <input type="file" class="form-control" id="file_path" name="file_path">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Save Allocation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div> 