@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Left Column - Grade Summary -->
        <div class="col-md-3">
            <div class="position-sticky" style="top: 1rem;">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-user-graduate text-primary me-2"></i>Student Details
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-light rounded-circle p-3 me-3">
                                <i class="fas fa-user fa-2x text-primary"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">{{ $submission->student->first_name }} {{ $submission->student->last_name }}</h6>
                                <small class="text-muted">Student ID: {{ $submission->student->id }}</small>
                            </div>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <label class="text-muted small mb-1">Assessment</label>
                            <p class="mb-0 fw-medium">{{ $allocation->assessment->name }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small mb-1">Submission Date</label>
                            <p class="mb-0">{{ $submission->submitted_at ? $submission->submitted_at->format('M d, Y - h:i A') : 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-calculator text-primary me-2"></i>Grade Summary
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="progress mb-3" style="height: 10px;">
                            <div class="progress-bar bg-primary" id="grade-progress" role="progressbar" style="width: {{ $percentageGrade }}%"></div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted">Current Score:</span>
                            <span class="fw-bold" id="current-score">0/{{ array_sum(array_column($gradeData, 'max_score')) }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Percentage:</span>
                            <span class="fw-bold text-primary" id="percentage-grade">{{ number_format($percentageGrade, 2) }}%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Grading Form -->
        <div class="col-md-9">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-graduation-cap text-primary me-2"></i>Grade Submission
                    </h5>
                    <a href="{{ route('admin.assessment-allocations.submissions.index', $allocation) }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-2"></i>Back to Submissions
                    </a>
                </div>
                
                <div class="card-body">
                    <form action="{{ route('admin.assessment-allocations.submissions.store-grades', ['allocation' => $allocation, 'submission' => $submission]) }}" method="POST">
                        @csrf
                        
                        <!-- Questions and Answers -->
                        <div class="questions-container">
                            @foreach($allocation->questions as $question)
                                @php
                                    $data = $gradeData[$question->id];
                                    $answer = $submission->answers[$question->id] ?? null;
                                @endphp
                                <div class="card mb-4 border-0 shadow-sm">
                                    <div class="card-header bg-light border-start border-4 {{ $question->question_type === 'multiple_choice' ? 'border-info' : 'border-primary' }}">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <span class="badge bg-{{ $question->question_type === 'multiple_choice' ? 'info' : 'primary' }} me-2">Q{{ $loop->iteration }}</span>
                                                <span class="fw-medium">{{ $question->question_text }}</span>
                                            </div>
                                            <span class="badge bg-light text-dark border">Max Score: {{ $data['max_score'] }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="card-body">
                                        <!-- Student's Answer -->
                                        <div class="mb-4">
                                            <h6 class="text-muted mb-3">
                                                <i class="fas fa-pen me-2"></i>Student's Answer
                                            </h6>
                                            
                                            @if($question->question_type === 'multiple_choice')
                                                @php
                                                    $selectedOption = $answer ? $question->options->firstWhere('id', $answer) : null;
                                                    $correctOption = $question->options->firstWhere('is_correct', true);
                                                    $isCorrect = $selectedOption && $selectedOption->is_correct;
                                                @endphp
                                                
                                                @if($selectedOption)
                                                    <div class="alert {{ $isCorrect ? 'alert-success' : 'alert-danger' }} d-flex align-items-center py-2 px-3">
                                                        <i class="fas fa-{{ $isCorrect ? 'check' : 'times' }}-circle me-2 text-white"></i>
                                                        <div class="small text-white fw-medium">
                                                            {{ $selectedOption->option_text }}
                                                            <span class="ms-1 opacity-75">
                                                                ({{ $isCorrect ? 'Correct Answer' : 'Incorrect Answer' }})
                                                            </span>
                                                        </div>
                                                    </div>
                                                    
                                                    @if(!$isCorrect && $correctOption)
                                                        <div class="alert alert-success mt-2 py-2 px-3 d-flex align-items-center">
                                                            <i class="fas fa-info-circle me-2 text-white"></i>
                                                            <div class="small text-white fw-medium">
                                                                <span class="opacity-75">Correct Answer:</span>
                                                                <span class="ms-1">{{ $correctOption->option_text }}</span>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @else
                                                    <div class="alert alert-warning py-2 px-3 small text-white fw-medium">
                                                        <i class="fas fa-exclamation-triangle me-2"></i>No answer provided
                                                    </div>
                                                @endif
                                            @else
                                                @if($answer)
                                                    <div class="p-2 bg-light rounded border">
                                                        <div class="small">{!! nl2br(e($answer)) !!}</div>
                                                    </div>
                                                @else
                                                    <div class="alert alert-warning py-2 px-3 small text-white fw-medium">
                                                        <i class="fas fa-exclamation-triangle me-2"></i>No answer provided
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                        
                                        <!-- Grading Section -->
                                        <div class="grading-section p-3 rounded">
                                            <div class="row align-items-start g-3">
                                                <div class="col-md-3">
                                                    <div class="d-flex align-items-center mb-2">
                                                        <i class="fas fa-star text-warning me-2"></i>
                                                        <span class="text-muted">SCORE</span>
                                                    </div>
                                                    <div class="input-group">
                                                        <input type="number" 
                                                            class="form-control grade-input" 
                                                            id="grade-{{ $question->id }}" 
                                                            name="grades[{{ $question->id }}]" 
                                                            value="{{ $data['score'] }}" 
                                                            min="0" 
                                                            max="{{ $data['max_score'] }}" 
                                                            step="0.1"
                                                            data-max="{{ $data['max_score'] }}"
                                                            {{ $question->question_type === 'multiple_choice' ? 'readonly' : '' }}>
                                                        <span class="input-group-text">/ {{ $data['max_score'] }}</span>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-9">
                                                    <div class="d-flex align-items-center mb-2">
                                                        <i class="fas fa-comment text-info me-2"></i>
                                                        <span class="text-muted">FEEDBACK</span>
                                                    </div>
                                                    <textarea class="form-control" 
                                                            id="feedback-{{ $question->id }}" 
                                                            name="feedback[{{ $question->id }}]" 
                                                            rows="1"
                                                            placeholder="Provide feedback for this answer...">{{ $data['feedback'] }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- General Feedback -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-light border-start border-4 border-success">
                                <h6 class="mb-0">
                                    <i class="fas fa-comment-alt text-success me-2"></i>Overall Feedback
                                </h6>
                            </div>
                            <div class="card-body">
                                <textarea class="form-control" 
                                          name="general_feedback" 
                                          rows="4" 
                                          placeholder="Provide overall feedback on this submission...">{{ $submission->feedback['general'] ?? '' }}</textarea>
                            </div>
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i>Save Grades
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .questions-container {
        max-width: 100%;
    }
    .grade-input:read-only {
        background-color: #f8f9fa;
        cursor: not-allowed;
    }
    .progress {
        border-radius: 1rem;
    }
    .progress-bar {
        transition: width 0.3s ease;
    }
    .grading-section {
        background-color: #f8f9fa;
    }
    .grading-section .text-muted {
        font-size: 0.8125rem;
        font-weight: 500;
        letter-spacing: 0.5px;
    }
    .grading-section .input-group {
        background-color: white;
    }
    .grading-section .form-control {
        border: 1px solid #dee2e6;
        padding: 0.5rem 0.75rem;
    }
    .grading-section .input-group-text {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        color: #6c757d;
    }
    .grading-section textarea {
        resize: none;
        background-color: white;
    }
    .grading-section .form-control:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13,110,253,.25);
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const gradeInputs = document.querySelectorAll('.grade-input');
    const currentScoreElement = document.getElementById('current-score');
    const percentageGradeElement = document.getElementById('percentage-grade');
    const progressBar = document.getElementById('grade-progress');
    
    function updateTotals() {
        let totalScore = 0;
        let totalMaxScore = 0;
        
        gradeInputs.forEach(input => {
            const score = parseFloat(input.value) || 0;
            const maxScore = parseFloat(input.dataset.max) || 0;
            
            if (!isNaN(score) && !isNaN(maxScore)) {
                totalScore += score;
                totalMaxScore += maxScore;
            }
        });
        
        const percentageGrade = totalMaxScore > 0 ? (totalScore / totalMaxScore) * 100 : 0;
        
        // Update the display with the new values
        currentScoreElement.textContent = `${totalScore.toFixed(1)}/${totalMaxScore.toFixed(1)}`;
        percentageGradeElement.textContent = `${percentageGrade.toFixed(2)}%`;
        progressBar.style.width = `${percentageGrade}%`;
    }
    
    // Add event listeners for both input and change events
    gradeInputs.forEach(input => {
        ['input', 'change', 'blur'].forEach(eventType => {
            input.addEventListener(eventType, updateTotals);
        });
    });
    
    // Initial update
    updateTotals();
});
</script>
@endpush
@endsection