@extends('students.submissions.layouts.base')

@section('submission_content')
<div class="card">
    <div class="card-body">
        <div class="text-center py-4">
            <div class="rounded-circle bg-brand-primary-light mx-auto mb-3" style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-chalkboard-teacher text-brand-primary fa-2x"></i>
            </div>
            <h5 class="mb-3 text-brand-primary">In-Class Assessment</h5>
            <p class="text-muted mb-4">
                This assessment will be conducted in class. Please attend your scheduled class session.
                @if($allocation->due_date)
                    <br>
                    <strong class="text-brand-primary">Date:</strong> {{ date('l, F j, Y', strtotime($allocation->due_date)) }}
                    <br>
                    <strong class="text-brand-primary">Time:</strong> {{ date('g:i A', strtotime($allocation->due_date)) }}
                @endif
            </p>

            @if($allocation->content)
                <div class="alert bg-brand-secondary-light">
                    <i class="fas fa-info-circle me-2 text-brand-primary"></i>Additional Information:
                    <div class="mt-2">
                        {!! nl2br(e($allocation->content)) !!}
                    </div>
                </div>
            @endif

            <div class="d-flex justify-content-center gap-2">
                @if($allocation->file_path)
                    <a href="{{ route('students.submissions.download', $allocation) }}" 
                       class="btn btn-brand-primary">
                        <i class="fas fa-download me-2"></i>Download Materials
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 