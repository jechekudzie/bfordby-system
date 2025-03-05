@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">
                    <i class="fas fa-question-circle text-primary me-2"></i>Create New Question
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
            <div>
                <a href="{{ route('admin.assessment-allocation-questions.index', $allocation) }}" 
                   class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Questions
                </a>
            </div>
        </div>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.assessment-allocation-questions.store', $allocation) }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-12 mb-4">
                    <label for="question_text" class="form-label fw-bold">Question Text</label>
                    <textarea 
                        name="question_text" 
                        id="question_text" 
                        class="form-control @error('question_text') is-invalid @enderror" 
                        rows="4" 
                        placeholder="Enter your question here..."
                        required
                    >{{ old('question_text') }}</textarea>
                    @error('question_text')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-4">
                    <label for="weight" class="form-label">Question Weight</label>
                    <input type="number" 
                           class="form-control @error('weight') is-invalid @enderror" 
                           id="weight" 
                           name="weight" 
                           value="{{ old('weight', 1) }}"
                           min="1"
                           required>
                    <div class="form-text">Enter the weight/marks for this question</div>
                    @error('weight')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-4">
                    <label for="question_type" class="form-label fw-bold">Question Type</label>
                    <select 
                        name="question_type" 
                        id="question_type" 
                        class="form-select @error('question_type') is-invalid @enderror" 
                        required
                    >
                        <option value="">Select Question Type</option>
                        <option value="text" {{ old('question_type') == 'text' ? 'selected' : '' }}>
                            Text Answer
                        </option>
                        <option value="multiple_choice" {{ old('question_type') == 'multiple_choice' ? 'selected' : '' }}>
                            Multiple Choice
                        </option>
                    </select>
                    @error('question_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div id="options-container" class="mb-4" style="display: none;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <label class="form-label fw-bold">Answer Options</label>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="addOption()">
                        <i class="fas fa-plus me-1"></i>Add Option
                    </button>
                </div>
                <div id="options-list">
                    <!-- Options will be added here dynamically -->
                </div>
                @error('options')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
                @error('correct_option')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div class="border-top pt-4 mt-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Create Question
                </button>
                <a href="{{ route('admin.assessment-allocation-questions.index', $allocation) }}" 
                   class="btn btn-outline-secondary ms-2">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    let optionCount = 0;

    function addOption() {
        const optionsList = document.getElementById('options-list');
        const optionDiv = document.createElement('div');
        optionDiv.className = 'option-item mb-3 d-flex align-items-start gap-2';
        optionDiv.innerHTML = `
            <div class="flex-grow-1">
                <div class="input-group">
                    <div class="input-group-text">
                        <input type="radio" name="correct_option" value="${optionCount}" 
                               class="form-check-input mt-0" required>
                    </div>
                    <input type="text" 
                           name="options[${optionCount}][option_text]" 
                           class="form-control" 
                           placeholder="Enter option text" 
                           required>
                </div>
            </div>
            <button type="button" 
                    class="btn btn-outline-danger btn-sm" 
                    onclick="removeOption(this)">
                <i class="fas fa-times"></i>
            </button>
        `;
        optionsList.appendChild(optionDiv);
        optionCount++;
    }

    function removeOption(button) {
        if (document.querySelectorAll('.option-item').length > 2) {
            button.closest('.option-item').remove();
        } else {
            alert('A multiple choice question must have at least 2 options.');
        }
    }

    // Add initial options and show/hide options container based on question type
    document.getElementById('question_type').addEventListener('change', function() {
        const optionsContainer = document.getElementById('options-container');
        const optionsList = document.getElementById('options-list');
        
        if (this.value === 'multiple_choice') {
            optionsContainer.style.display = 'block';
            if (optionsList.children.length === 0) {
                // Add initial two options
                addOption();
                addOption();
            }
        } else {
            optionsContainer.style.display = 'none';
        }
    });

    // Trigger change event on page load if multiple_choice is selected
    if (document.getElementById('question_type').value === 'multiple_choice') {
        document.getElementById('question_type').dispatchEvent(new Event('change'));
    }
</script>
@endpush
@endsection