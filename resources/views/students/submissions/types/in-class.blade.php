@extends('students.submissions.layouts.base')

@section('submission_content')
<div class="card">
    <div class="card-body">
        <div class="text-center py-4">
            <div class="rounded-circle bg-light mx-auto mb-3" style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-chalkboard-teacher text-primary fa-2x"></i>
            </div>
            <h5 class="mb-3">In-Class Assessment</h5>
            <p class="text-muted mb-4">
                This assessment will be conducted in class. Please attend your scheduled class session.
                @if($allocation->due_date)
                    <br>
                    <strong>Date:</strong> {{ $allocation->due_date->format('l, F j, Y') }}
                    <br>
                    <strong>Time:</strong> {{ $allocation->due_date->format('g:i A') }}
                @endif
            </p>

            @if($allocation->content)
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>Additional Information:
                    <div class="mt-2">
                        {!! nl2br(e($allocation->content)) !!}
                    </div>
                </div>
            @endif

            <div class="d-flex justify-content-center gap-2">
                <a href="{{ route('students.enrollments.show', ['student' => $student, 'enrollment' => $student->enrollments()->first()]) }}" 
                   class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Assessments
                </a>
                @if($allocation->file_path)
                    <a href="{{ route('students.submissions.download', $allocation) }}" 
                       class="btn btn-primary">
                        <i class="fas fa-download me-2"></i>Download Materials
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 