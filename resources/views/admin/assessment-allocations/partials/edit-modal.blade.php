<div class="modal fade" id="editModal{{ $allocation->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Assessment Allocation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.assessment-allocations.update', [$assessment, $allocation]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="enrollment_code_id{{ $allocation->id }}" class="form-label">Enrollment Code</label>
                        <select name="enrollment_code_id" id="enrollment_code_id{{ $allocation->id }}" class="form-select" required>
                            <option value="">Select Enrollment Code</option>
                            @foreach($enrollmentCodes as $code)
                                <option value="{{ $code->id }}" {{ $allocation->enrollment_code_id == $code->id ? 'selected' : '' }}>
                                    {{ $code->base_code }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="semester_id{{ $allocation->id }}" class="form-label">Semester</label>
                        <select name="semester_id" id="semester_id{{ $allocation->id }}" class="form-select" required>
                            <option value="">Select Semester</option>
                            @foreach($semesters as $semester)
                                <option value="{{ $semester->id }}" {{ $allocation->semester_id == $semester->id ? 'selected' : '' }}>
                                    {{ $semester->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="due_date{{ $allocation->id }}" class="form-label">Due Date</label>
                        <input type="date" class="form-control" id="due_date{{ $allocation->id }}" 
                               name="due_date" value="{{ date('Y-m-d', strtotime($allocation->due_date)) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="status{{ $allocation->id }}" class="form-label">Status</label>
                        <select name="status" id="status{{ $allocation->id }}" class="form-select" required>
                            @foreach(['pending', 'submitted', 'graded'] as $status)
                                <option value="{{ $status }}" {{ $allocation->status == $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="content{{ $allocation->id }}" class="form-label">Content</label>
                        <textarea class="form-control" id="content{{ $allocation->id }}" 
                                  name="content" rows="3">{{ $allocation->content }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="file_path{{ $allocation->id }}" class="form-label">File Attachment</label>
                        @if($allocation->file_path)
                            <div class="mb-2">
                                <a href="{{ Storage::url($allocation->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-file-download me-1"></i>Current File
                                </a>
                            </div>
                        @endif
                        <input type="file" class="form-control" id="file_path{{ $allocation->id }}" name="file_path">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Update Allocation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div> 