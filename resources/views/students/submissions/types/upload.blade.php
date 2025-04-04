@extends('students.submissions.layouts.base')

@section('submission_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0 text-brand-primary">
        <i class="fas fa-file-upload text-brand-primary me-2"></i>{{ $allocation->assessment->name }}
        @if($group)
        <span class="badge badge-brand-primary ms-2">Group Submission</span>
        @endif
    </h5>
</div>

@if($group)
<!-- Group Information -->
<div class="card mb-4 border-brand-primary">
    <div class="card-header bg-brand-primary text-white">
        <h6 class="mb-0">
            <i class="fas fa-users me-2"></i>Group Submission Information
        </h6>
    </div>
    <div class="card-body">
        <div class="alert bg-brand-secondary-light">
            <div class="d-flex">
                <div class="me-3">
                    <i class="fas fa-info-circle fa-2x text-brand-primary"></i>
                </div>
                <div>
                    <h6 class="alert-heading text-brand-primary">You are submitting on behalf of your group</h6>
                    <p class="mb-0">This submission will count for all group members. All members will receive the same grade.</p>
                </div>
            </div>
        </div>
        
        <p class="mb-3">Group: <strong>{{ $group->name }}</strong></p>
        
        <h6 class="mb-2 text-brand-primary">Group Members:</h6>
        <div class="list-group mb-0">
            @foreach($group->students as $groupMember)
                <div class="list-group-item d-flex align-items-center py-2">
                    <i class="fas fa-user text-brand-primary me-3"></i>
                    <div>
                        <strong>{{ $groupMember->first_name }} {{ $groupMember->last_name }}</strong>
                        @if($groupMember->id === $student->id)
                            <span class="badge badge-brand-primary ms-2">You (Submitter)</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<div class="card mb-4">
    <div class="card-header bg-light">
        <h6 class="mb-0 text-brand-primary">
            <i class="fas fa-info-circle text-brand-primary me-2"></i>Instructions
        </h6>
    </div>
    <div class="card-body">
        <p>Please upload your {{ $group ? 'group' : '' }} submission file below. Accepted file types: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, ZIP.</p>
        
        @if($allocation->due_date)
            <div class="alert bg-brand-secondary-light">
                <div class="d-flex align-items-center">
                    <i class="fas fa-calendar-alt text-brand-primary me-3"></i>
                    <div>
                        <strong class="text-brand-primary">Due Date:</strong> {{ date('F j, Y, g:i a', strtotime($allocation->due_date)) }}
                        @php
                            $now = new DateTime();
                            $due = new DateTime($allocation->due_date);
                            $diff = $now->diff($due);
                            $isOverdue = $now > $due;
                        @endphp
                        
                        @if($isOverdue)
                            <span class="badge badge-brand-primary ms-2">Overdue</span>
                        @elseif($diff->days < 1)
                            <span class="badge badge-brand-secondary ms-2">Due Soon</span>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<div class="card">
    <div class="card-header bg-light">
        <h6 class="mb-0 text-brand-primary">
            <i class="fas fa-upload text-brand-primary me-2"></i>Upload {{ $group ? 'Group' : '' }} Submission
        </h6>
    </div>
    <div class="card-body">
        @if($submission->file_path)
            <div class="alert bg-brand-primary-light mb-4">
                <div class="d-flex">
                    <div class="me-3">
                        <i class="fas fa-check-circle fa-2x text-brand-primary"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="alert-heading text-brand-primary">File Already Submitted{{ $group ? ' for Your Group' : '' }}</h6>
                        <p class="mb-1">You have already uploaded a file for this assessment{{ $group ? ' on behalf of your group' : '' }}.</p>
                        <p class="mb-0 small">Uploaded {{ $submission->submitted_at ? $submission->submitted_at->diffForHumans() : 'recently' }}</p>
                    </div>
                    <div>
                        <a href="{{ route('students.submissions.download', $allocation) }}" 
                           class="btn btn-sm btn-brand-primary"
                           target="_blank"
                           rel="noopener noreferrer">
                            <i class="fas fa-download me-1"></i>Download
                        </a>
                    </div>
                </div>
            </div>
        @endif
        
        <form id="submissionForm" action="{{ route('students.submissions.store', $allocation) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="removeFile" id="removeFile" value="0">
            @if(isset($isGroupSubmission) && $isGroupSubmission)
                <input type="hidden" name="group_submission" value="1">
            @endif
            
            <div class="mb-4">
                <label for="file" class="form-label text-brand-primary">Select File to Upload{{ $group ? ' for Your Group' : '' }}</label>
                <input type="file" class="form-control @error('file') is-invalid @enderror" id="file" name="file">
                @error('file')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-file-alt text-brand-primary me-2"></i>
                        <span class="text-brand-primary">Accepted File Types</span>
                    </div>
                    <p class="small text-muted">PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, ZIP</p>
                </div>
                <div class="col-md-4">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-weight-hanging text-brand-primary me-2"></i>
                        <span class="text-brand-primary">Maximum File Size</span>
                    </div>
                    <p class="small text-muted">10 MB</p>
                </div>
                <div class="col-md-4">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-file-upload text-brand-primary me-2"></i>
                        <span class="text-brand-primary">Files Per Submission</span>
                    </div>
                    <p class="small text-muted">1 file only</p>
                </div>
            </div>
            
            <div class="d-flex justify-content-between align-items-center">
                @if($submission->file_path)
                    <button type="button" class="btn btn-outline-danger" onclick="confirmFileRemoval()">
                        <i class="fas fa-trash-alt me-2"></i>Remove Current File
                    </button>
                @else
                    <div></div>
                @endif
                
                <button type="submit" class="btn btn-brand-primary btn-lg">
                    @if($group)
                    <i class="fas fa-users me-2"></i>Submit Group Assignment
                    @else
                    <i class="fas fa-paper-plane me-2"></i>Submit Work
                    @endif
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function confirmFileRemoval() {
        if (confirm('Are you sure you want to remove this file? This action cannot be undone.')) {
            document.getElementById('removeFile').value = '1';
            document.getElementById('submissionForm').submit();
        }
    }
</script>
@endpush
@endsection 
