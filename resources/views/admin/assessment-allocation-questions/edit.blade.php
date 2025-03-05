@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">
                    <i class="fas fa-edit text-primary me-2"></i>Edit Question
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
            <a href="{{ route('admin.assessment-allocation-questions.index', $allocation) }}" 
               class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i>Back to Questions
            </a>
        </div>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.assessment-allocation-questions.update', [$allocation, $question]) }}" 
              method="POST" 
              id="questionForm">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="question_text" class="form-label">Question Text</label>
                <textarea class="form-control @error('question_text') is-invalid @enderror" 
                          id="question_text" 
                          name="question_text" 
                          rows="3" 
                          required>{{ old('question_text', $question->question_text) }}</textarea>
                @error('question_text')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="weight" class="form-label">Question Weight</label>
                <input type="number" 
                       class="form-control @error('weight') is-invalid @enderror" 
                       id="weight" 
                       name="weight" 
                       value="{{ old('weight', $question->weight) }}"
                       min="1"
                       required>
                <div class="form-text">Enter the weight/marks for this question</div>
                @error('weight')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="question_type" class="form-label">Question Type</label>
                <select class="form-select @error('question_type') is-invalid @enderror" 
                        id="question_type" 
                        name="question_type" 
                        required>
                    <option value="text" {{ old('question_type', $question->question_type) === 'text' ? 'selected' : '' }}>Text Answer</option>
                    <option value="multiple_choice" {{ old('question_type', $question->question_type) === 'multiple_choice' ? 'selected' : '' }}>Multiple Choice</option>
                </select>
                @error('question_type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div id="optionsContainer" class="mb-4" style="display: none;">
                <label class="form-label">Answer Options</label>
                <div id="optionsList">
                    @if($question->question_type === 'multiple_choice')
                        @foreach($question->options as $index => $option)
                            <div class="option-item mb-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" 
                                               name="correct_option" 
                                               value="{{ $index }}"
                                               {{ $option->is_correct ? 'checked' : '' }}>
                                    </div>
                                    <div class="flex-grow-1">
                                        <input type="text" 
                                               class="form-control" 
                                               name="options[{{ $index }}][option_text]" 
                                               value="{{ $option->option_text }}" 
                                               required>
                                    </div>
                                    @if(!$option->is_correct)
                                        <button type="button" 
                                                class="btn btn-outline-danger btn-sm remove-option">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="option-item mb-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="correct_option" value="0" checked>
                                </div>
                                <div class="flex-grow-1">
                                    <input type="text" 
                                           class="form-control" 
                                           name="options[0][option_text]" 
                                           required>
                                </div>
                            </div>
                        </div>
                        <div class="option-item mb-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="correct_option" value="1">
                                </div>
                                <div class="flex-grow-1">
                                    <input type="text" 
                                           class="form-control" 
                                           name="options[1][option_text]" 
                                           required>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="mt-3">
                    <button type="button" class="btn btn-outline-primary btn-sm" id="addOption">
                        <i class="fas fa-plus me-2"></i>Add Option
                    </button>
                </div>

                @error('options')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
                @error('correct_option')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Update Question
                </button>
                <a href="{{ route('admin.assessment-allocation-questions.index', $allocation) }}" 
                   class="btn btn-outline-secondary">
                    <i class="fas fa-times me-2"></i>Cancel
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const questionType = document.getElementById('question_type');
    const optionsContainer = document.getElementById('optionsContainer');
    const optionsList = document.getElementById('optionsList');
    const addOptionBtn = document.getElementById('addOption');

    // Show/hide options based on question type
    function toggleOptions() {
        if (questionType.value === 'multiple_choice') {
            optionsContainer.style.display = 'block';
        } else {
            optionsContainer.style.display = 'none';
        }
    }

    // Update delete buttons visibility
    function updateDeleteButtons() {
        const options = optionsList.querySelectorAll('.option-item');
        options.forEach((option) => {
            const radio = option.querySelector('input[type="radio"]');
            const deleteBtn = option.querySelector('.remove-option');
            if (deleteBtn) {
                if (radio.checked) {
                    deleteBtn.style.display = 'none';
                } else {
                    deleteBtn.style.display = 'block';
                    deleteBtn.disabled = false;
                }
            } else if (!radio.checked && options.length > 2) {
                // Add delete button if it doesn't exist and it's not the correct answer
                const newDeleteBtn = document.createElement('button');
                newDeleteBtn.type = 'button';
                newDeleteBtn.className = 'btn btn-outline-danger btn-sm remove-option';
                newDeleteBtn.innerHTML = '<i class="fas fa-times"></i>';
                option.querySelector('.d-flex').appendChild(newDeleteBtn);
            }
        });
    }

    // Initialize options visibility
    toggleOptions();
    if (questionType.value === 'multiple_choice') {
        updateDeleteButtons();
    }

    // Add event listener for question type change
    questionType.addEventListener('change', toggleOptions);

    // Add new option
    addOptionBtn.addEventListener('click', function() {
        const optionCount = optionsList.children.length;
        const newOption = document.createElement('div');
        newOption.className = 'option-item mb-3';
        newOption.innerHTML = `
            <div class="d-flex align-items-center gap-3">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="correct_option" value="${optionCount}">
                </div>
                <div class="flex-grow-1">
                    <input type="text" class="form-control" name="options[${optionCount}][option_text]" required>
                </div>
                <button type="button" class="btn btn-outline-danger btn-sm remove-option">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        optionsList.appendChild(newOption);
    });

    // Remove option
    optionsList.addEventListener('click', function(e) {
        if (e.target.closest('.remove-option')) {
            const optionItem = e.target.closest('.option-item');
            const isCorrect = optionItem.querySelector('input[type="radio"]').checked;
            
            if (optionsList.children.length > 2 && !isCorrect) {
                optionItem.remove();
                // Update radio button values
                const options = optionsList.querySelectorAll('.option-item');
                options.forEach((option, index) => {
                    option.querySelector('input[type="radio"]').value = index;
                });
            } else {
                alert('Must maintain at least two options and cannot delete the correct answer.');
            }
        }
    });

    // Handle correct option change
    optionsList.addEventListener('change', function(e) {
        if (e.target.matches('input[type="radio"]')) {
            updateDeleteButtons();
        }
    });
});
</script>
@endpush
@endsection 