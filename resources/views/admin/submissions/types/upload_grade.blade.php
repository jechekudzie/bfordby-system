@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Left Column - Student Details & Grade Summary -->
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
                        
                        @if($group)
                        <hr>
                        <div class="mb-3">
                            <label class="text-muted small mb-1">Group Submission</label>
                            <p class="mb-0 fw-medium">{{ $group->name }}</p>
                            
                            <div class="mt-2">
                                <label class="text-muted small mb-1">Group Members</label>
                                <ul class="list-group list-group-flush">
                                    @foreach($groupMembers as $member)
                                        <li class="list-group-item px-0 py-1 border-0">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-user text-primary me-2"></i>
                                                <span>{{ $member->first_name }} {{ $member->last_name }}</span>
                                                @if($member->id === $submission->student_id)
                                                    <span class="badge bg-primary ms-2">Submitter</span>
                                                @endif
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        @endif
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
                            <span class="fw-bold" id="current-score">0/0</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted">Percentage:</span>
                            <span class="fw-bold text-primary" id="percentage-grade">{{ number_format($percentageGrade, 2) }}%</span>
                        </div>
                        <hr>
                        <div class="contribution-info">
                            <h6 class="text-muted mb-3">Grade Contribution</h6>
                            <div class="mb-2">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <small class="text-muted">Assessment Type Weight:</small>
                                    <span class="badge bg-info">{{ $contributionWeight }}%</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">Trimester Weight:</small>
                                    <span class="badge bg-secondary">{{ $trimesterWeight }}%</span>
                                </div>
                            </div>
                            <div class="alert alert-light border p-2 mb-0">
                                <small class="d-block text-muted mb-1">Contribution to Final Grade:</small>
                                <span class="fw-bold text-primary">
                                    {{ number_format(($percentageGrade * $contributionWeight * $trimesterWeight) / 10000, 2) }}%
                                </span>
                                <small class="d-block text-muted mt-1">
                                    (Grade × Type Weight × Trimester Weight)
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Column - Grading Form -->
        <div class="col-md-9">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-graduation-cap text-primary me-2"></i>Grade Submission
                    </h5>
                    <a href="{{ route('admin.assessment-allocations.submissions.index', $allocation) }}" 
                       class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-2"></i>Back to Submissions
                    </a>
                </div>
                
                <div class="card-body">
                    <!-- File Preview -->
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-header bg-light border-start border-4 border-primary">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">
                                    <i class="fas fa-file-alt text-primary me-2"></i>Submitted File
                                </h6>
                                <a href="{{ route('admin.assessment-allocations.submissions.download', ['allocation' => $allocation, 'submission' => $submission]) }}" 
                                   class="btn btn-primary btn-sm"
                                   target="_blank"
                                   rel="noopener noreferrer">
                                    <i class="fas fa-download me-2"></i>Download Submission
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info mb-0">
                                <div class="d-flex">
                                    <div class="me-3">
                                        <i class="fas fa-info-circle fa-2x"></i>
                                    </div>
                                    <div>
                                        <h6 class="alert-heading">Grading Instructions</h6>
                                        <p class="mb-0">
                                            Please download and review the student's submission file. Score each question based on the content of the submitted document.
                                            @if($group)
                                            <strong class="d-block mt-2">This is a group submission. All members of the group will receive the same grade.</strong>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('admin.assessment-allocations.submissions.store-grades', ['allocation' => $allocation, 'submission' => $submission]) }}" 
                          method="POST" 
                          id="gradeForm">
                        @csrf
                        
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-list-ol text-primary me-2"></i>
                            <h5 class="mb-0">Question Scores</h5>
                        </div>
                        
                        <!-- Questions Container -->
                        <div class="questions-container" id="questionsContainer">
                            @php
                                // Initialize with existing grades or create default questions
                                $questionData = [];
                                
                                if (!empty($submission->grades)) {
                                    foreach ($submission->grades as $index => $score) {
                                        // Get weight from the weights array or use default 10
                                        $maxScore = $weights[$index] ?? 10;
                                        
                                        $questionData[] = [
                                            'index' => $index,
                                            'score' => $score,
                                            'max_score' => $maxScore,
                                            'feedback' => $submission->feedback[$index] ?? ''
                                        ];
                                    }
                                } else {
                                    // Create a default question if none exist
                                    $questionData[] = [
                                        'index' => 0,
                                        'score' => 0,
                                        'max_score' => 10,
                                        'feedback' => ''
                                    ];
                                }
                            @endphp
                            
                            @foreach($questionData as $question)
                                <div class="card mb-4 border-0 shadow-sm question-card">
                                    <div class="card-header bg-primary text-white">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <span class="badge bg-white text-primary me-2">Q{{ $loop->iteration }}</span>
                                                <span class="fw-medium">Question {{ $loop->iteration }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="card-body">
                                        <div class="row g-4">
                                            <div class="col-md-4">
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="fas fa-balance-scale text-warning me-2"></i>
                                                    <span class="text-muted fw-medium">WEIGHT</span>
                                                </div>
                                                <div class="input-group">
                                                    <input type="number" 
                                                        class="form-control weight-input" 
                                                        name="weights[{{ $question['index'] }}]" 
                                                        value="{{ $question['max_score'] }}" 
                                                        min="1" 
                                                        max="100" 
                                                        step="1"
                                                        data-index="{{ $question['index'] }}">
                                                    <span class="input-group-text">points</span>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-4">
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="fas fa-star text-warning me-2"></i>
                                                    <span class="text-muted fw-medium">SCORE</span>
                                                </div>
                                                <div class="input-group">
                                                    <input type="number" 
                                                        class="form-control grade-input" 
                                                        name="grades[{{ $question['index'] }}]" 
                                                        value="{{ $question['score'] }}" 
                                                        min="0" 
                                                        max="{{ $question['max_score'] }}" 
                                                        step="0.1"
                                                        data-index="{{ $question['index'] }}">
                                                    <span class="input-group-text score-max">/ {{ $question['max_score'] }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-4">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-comment text-info me-2"></i>
                                                <span class="text-muted fw-medium">FEEDBACK</span>
                                            </div>
                                            <textarea class="form-control" 
                                                    name="feedback[{{ $question['index'] }}]" 
                                                    rows="2"
                                                    placeholder="Provide feedback for this question...">{{ $question['feedback'] }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Add Question Button -->
                        <div class="mb-4">
                            <button type="button" class="btn btn-primary" onclick="addQuestion()">
                                <i class="fas fa-plus me-2"></i>Add Question
                            </button>
                            <small class="text-muted ms-2">Click to add another question if needed</small>
                        </div>
                        
                        <!-- General Feedback -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0">
                                    <i class="fas fa-comment-alt me-2"></i>Overall Feedback
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const gradeInputs = document.querySelectorAll('.grade-input');
    const weightInputs = document.querySelectorAll('.weight-input');
    const currentScoreElement = document.getElementById('current-score');
    const percentageGradeElement = document.getElementById('percentage-grade');
    const progressBar = document.getElementById('grade-progress');
    
    function updateTotals() {
        let totalScore = 0;
        let totalMaxScore = 0;
        
        // Get all question rows
        const questions = document.querySelectorAll('.question-card');
        
        questions.forEach(question => {
            const weightInput = question.querySelector('.weight-input');
            const gradeInput = question.querySelector('.grade-input');
            const scoreMax = question.querySelector('.score-max');
            
            if (weightInput && gradeInput) {
                const weight = parseFloat(weightInput.value) || 0;
                const score = parseFloat(gradeInput.value) || 0;
                
                // Update max score display
                if (scoreMax) {
                    scoreMax.textContent = `/ ${weight}`;
                }
                
                // Ensure score doesn't exceed weight
                if (score > weight) {
                    gradeInput.value = weight;
                }
                
                // Update max attribute
                gradeInput.setAttribute('max', weight);
                
                totalScore += score;
                totalMaxScore += weight;
            }
        });
        
        const percentageGrade = totalMaxScore > 0 ? (totalScore / totalMaxScore) * 100 : 0;
        
        // Get contribution weights
        const contributionWeight = parseFloat('{{ $contributionWeight }}');
        const trimesterWeight = parseFloat('{{ $trimesterWeight }}');
        
        // Calculate final contribution
        const finalContribution = (percentageGrade * contributionWeight * trimesterWeight) / 10000;
        
        // Update the display with the new values
        currentScoreElement.textContent = `${totalScore.toFixed(1)}/${totalMaxScore.toFixed(1)}`;
        percentageGradeElement.textContent = `${percentageGrade.toFixed(2)}%`;
        progressBar.style.width = `${percentageGrade}%`;
        
        // Update contribution display
        document.querySelector('.contribution-info .fw-bold.text-primary').textContent = 
            `${finalContribution.toFixed(2)}%`;
    }
    
    // Add event listeners for both input and change events
    function addInputListeners(input) {
        ['input', 'change', 'blur'].forEach(eventType => {
            input.addEventListener(eventType, updateTotals);
        });
    }
    
    gradeInputs.forEach(addInputListeners);
    weightInputs.forEach(addInputListeners);
    
    // Initial update
    updateTotals();
    
    // Make the addQuestion function globally available
    window.addQuestion = function() {
        const container = document.getElementById('questionsContainer');
        const questionCount = container.querySelectorAll('.card').length + 1;
        const questionIndex = new Date().getTime(); // Use timestamp as unique index
        const defaultWeight = 10;
        
        const questionHtml = `
            <div class="card mb-4 border-0 shadow-sm question-card">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="badge bg-white text-primary me-2">Q${questionCount}</span>
                            <span class="fw-medium">Question ${questionCount}</span>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-balance-scale text-warning me-2"></i>
                                <span class="text-muted fw-medium">WEIGHT</span>
                            </div>
                            <div class="input-group">
                                <input type="number" 
                                    class="form-control weight-input" 
                                    name="weights[${questionIndex}]" 
                                    value="${defaultWeight}" 
                                    min="1" 
                                    max="100" 
                                    step="1"
                                    data-index="${questionIndex}">
                                <span class="input-group-text">points</span>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-star text-warning me-2"></i>
                                <span class="text-muted fw-medium">SCORE</span>
                            </div>
                            <div class="input-group">
                                <input type="number" 
                                    class="form-control grade-input" 
                                    name="grades[${questionIndex}]" 
                                    value="0" 
                                    min="0" 
                                    max="${defaultWeight}" 
                                    step="0.1"
                                    data-index="${questionIndex}">
                                <span class="input-group-text score-max">/ ${defaultWeight}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-comment text-info me-2"></i>
                            <span class="text-muted fw-medium">FEEDBACK</span>
                        </div>
                        <textarea class="form-control" 
                                name="feedback[${questionIndex}]" 
                                rows="2"
                                placeholder="Provide feedback for this question..."></textarea>
                    </div>
                </div>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', questionHtml);
        
        // Add event listeners to the new inputs
        const newGradeInput = container.lastElementChild.querySelector('.grade-input');
        const newWeightInput = container.lastElementChild.querySelector('.weight-input');
        
        addInputListeners(newGradeInput);
        addInputListeners(newWeightInput);
        
        // Trigger update
        updateTotals();
    };
});
</script>
@endpush

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
    .question-card {
        border-radius: 0.5rem;
        overflow: hidden;
    }
    .question-card .card-header {
        border-bottom: none;
    }
    .input-group {
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    textarea {
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    .form-control:focus {
        border-color: #8bbafe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
    }
</style>
@endpush
@endsection
