@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">
                    <i class="fas fa-question-circle text-primary me-2"></i>Question Details
                </h5>
                <div class="text-muted">
                    <div class="mb-1">
                        Assessment: <span class="fw-bold text-dark">{{ $allocation->assessment->name }}</span>
                        <span class="badge bg-{{ 
                            $allocation->assessment->type === 'exam' ? 'danger' : 
                            ($allocation->assessment->type === 'test' ? 'warning' : 
                            ($allocation->assessment->type === 'practical' ? 'info' : 'primary')) 
                        }} ms-2">{{ ucfirst($allocation->assessment->type) }}</span>
                    </div>
                    <div>
                        Enrollment Code: <span class="fw-bold text-dark">{{ $allocation->enrollmentCode->base_code }}</span>
                        <span class="mx-2">•</span>
                        Semester: <span class="fw-bold text-dark">{{ $allocation->semester->name }}</span>
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.assessment-allocation-questions.index', $allocation) }}" 
                   class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Questions
                </a>
                <a href="{{ route('admin.assessment-allocation-questions.edit', [$allocation, $question]) }}" 
                   class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i>Edit Question
                </a>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <div class="mb-4">
                    <h6 class="fw-bold text-muted mb-2">Question Type</h6>
                    <span class="badge bg-{{ $question->question_type === 'multiple_choice' ? 'primary' : 'info' }} fs-6">
                        {{ $question->question_type === 'multiple_choice' ? 'Multiple Choice' : 'Text Answer' }}
                    </span>
                </div>

                <div class="mb-4">
                    <h6 class="fw-bold text-muted mb-2">Question Text</h6>
                    <div class="p-3 bg-light rounded">
                        {!! nl2br(e($question->question_text)) !!}
                    </div>
                </div>

                @if($question->question_type === 'multiple_choice' && $question->options->count() > 0)
                    <div>
                        <h6 class="fw-bold text-muted mb-3">Answer Options</h6>
                        <div class="list-group">
                            @foreach($question->options as $option)
                                <div class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" disabled 
                                               {{ $option->is_correct ? 'checked' : '' }}>
                                    </div>
                                    <div class="flex-grow-1">
                                        {{ $option->option_text }}
                                    </div>
                                    @if($option->is_correct)
                                        <span class="badge bg-success">Correct Answer</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="card-footer bg-white border-top">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-muted">
                <small>Created: {{ $question->created_at->format('M d, Y H:i A') }}</small>
                @if($question->updated_at != $question->created_at)
                    <span class="mx-1">•</span>
                    <small>Last Updated: {{ $question->updated_at->format('M d, Y H:i A') }}</small>
                @endif
            </div>
            <form action="{{ route('admin.assessment-allocation-questions.destroy', [$allocation, $question]) }}" 
                  method="POST" 
                  onsubmit="return confirm('Are you sure you want to delete this question?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm">
                    <i class="fas fa-trash me-2"></i>Delete Question
                </button>
            </form>
        </div>
    </div>
</div>
@endsection 