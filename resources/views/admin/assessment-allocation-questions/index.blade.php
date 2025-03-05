@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">
                    <i class="fas fa-question-circle text-primary me-2"></i>Assessment Questions
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
                <a href="{{ route('admin.modules.assessments.allocations.index', [$allocation->assessment->module, $allocation->assessment]) }}" 
                   class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Allocations
                </a>
                <a href="{{ route('admin.assessment-allocation-questions.create', $allocation) }}" 
                   class="btn btn-primary">
                    <i class="fas fa-plus-circle me-2"></i>Add Question
                </a>
            </div>
        </div>
    </div>

    <div class="card-body">
        @if($questions->isEmpty())
            <div class="text-center py-4">
                <img src="{{ asset('images/illustrations/empty.svg') }}" alt="No Questions" 
                     class="img-fluid mb-3" style="max-width: 200px;">
                <h5 class="text-muted mb-2">No Questions Found</h5>
                <p class="text-muted mb-3">Start by adding your first question</p>
                <a href="{{ route('admin.assessment-allocation-questions.create', $allocation) }}" 
                   class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add First Question
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px"></th>
                            <th style="width: 50px">#</th>
                            <th>Question</th>
                            <th style="width: 100px">Weight</th>
                            <th style="width: 150px">Type</th>
                            <th style="width: 120px">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="questionsList">
                        @foreach($questions as $index => $question)
                            <tr data-id="{{ $question->id }}" class="question-row">
                                <td>
                                    <div class="drag-handle cursor-move">
                                        <i class="fas fa-grip-vertical text-muted"></i>
                                    </div>
                                </td>
                                <td>{{ $questions->firstItem() + $index }}</td>
                                <td>{{ Str::limit($question->question_text, 100) }}</td>
                                <td>{{ $question->weight }}</td>
                                <td>
                                    <span class="badge bg-{{ $question->question_type === 'multiple_choice' ? 'primary' : 'info' }}">
                                        {{ $question->question_type === 'multiple_choice' ? 'Multiple Choice' : 'Text Answer' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.assessment-allocation-questions.show', [$allocation, $question]) }}" 
                                           class="btn btn-outline-info" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.assessment-allocation-questions.edit', [$allocation, $question]) }}" 
                                           class="btn btn-outline-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.assessment-allocation-questions.destroy', [$allocation, $question]) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this question?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Delete">
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

            <div class="d-flex justify-content-center mt-4">
                {{ $questions->links() }}
            </div>
        @endif
    </div>
</div>

@push('styles')
<style>
.cursor-move {
    cursor: move;
}
.question-row.dragging {
    opacity: 0.5;
    background: #f8f9fa;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const questionsList = document.getElementById('questionsList');
    if (questionsList) {
        new Sortable(questionsList, {
            handle: '.drag-handle',
            animation: 150,
            onEnd: function(evt) {
                const questions = Array.from(questionsList.querySelectorAll('tr')).map((row, index) => ({
                    id: row.dataset.id,
                    order: index
                }));
                
                // Send the new order to the server
                fetch('{{ route("admin.assessment-allocation-questions.reorder", $allocation) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ questions })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Optional: Show success message
                    }
                })
                .catch(error => {
                    console.error('Error updating question order:', error);
                });
            }
        });
    }
});
</script>
@endpush
@endsection 