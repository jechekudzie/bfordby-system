@extends('students.submissions.layouts.base')

@section('submission_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center">
        <i class="fas fa-check-circle text-success me-3 fa-2x"></i>
        <div>
            <h5 class="mb-0">{{ $allocation->assessment->name }}</h5>
            <div class="text-muted small">Your Answers</div>
        </div>
    </div>
    <div>
        @php
            $student = $submission->student ?? null;
            $enrollment = null;
            
            if ($student) {
                $enrollment = $student->enrollments()
                    ->where('course_id', $allocation->assessment->module->subject->course_id)
                    ->first();
            }
            
            $enrollmentUrl = $enrollment 
                ? route('students.enrollments.show', ['student' => $student, 'enrollment' => $enrollment]) 
                : route('dashboard');
        @endphp
        <a href="{{ $enrollmentUrl }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Assessments
        </a>
    </div>
</div>

{{-- Submission Status Card --}}
<div class="card border-0 bg-primary text-white mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h6 class="mb-2"><i class="fas fa-info-circle me-2"></i>Submission Status</h6>
                <p class="mb-0">Your answers have been submitted successfully.</p>
            </div>
            <div class="text-end">
                <div class="small mb-1">Submitted</div>
                <div class="fw-bold">{{ date('M d, Y - h:i A', strtotime($submission->submitted_at)) }}</div>
            </div>
        </div>
    </div>
</div>

{{-- Uploaded File Section (if applicable) --}}
@if($submission->file_path)
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <i class="fas fa-file-alt text-primary fa-2x me-3"></i>
                    <div>
                        <h6 class="mb-1">Uploaded File</h6>
                        <div class="text-muted small">{{ basename($submission->file_path) }}</div>
                    </div>
                </div>
                <a href="{{ route('students.submissions.download', $allocation) }}" 
                   class="btn btn-primary"
                   target="_blank"
                   rel="noopener noreferrer"
                   title="Download {{ basename($submission->file_path) }}">
                    <i class="fas fa-download me-2"></i>Download
                </a>
            </div>
        </div>
    </div>
@endif

{{-- Online Answers Section (if applicable) --}}
@if($allocation->questions->isNotEmpty() && $submission->answers)
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h6 class="mb-0"><i class="fas fa-list-alt me-2"></i>Your Answers</h6>
        </div>
        <div class="card-body">
            @if(empty($submission->answers) || count($submission->answers) === 0)
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    No answers found in your submission.
                </div>
                
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Submission Data:</strong><br>
                    Status: {{ $submission->status }}<br>
                    Submitted: {{ $submission->submitted_at ? date('Y-m-d H:i:s', strtotime($submission->submitted_at)) : 'Not submitted' }}<br>
                    Answers Type: {{ gettype($submission->answers) }}<br>
                    Answers Count: {{ is_array($submission->answers) || is_object($submission->answers) ? count($submission->answers) : 0 }}
                </div>
            @endif
            
            @foreach($allocation->questions as $question)
                <div class="mb-4">
                    <div class="fw-medium mb-2">Question {{ $loop->iteration }}</div>
                    <div class="text-muted mb-2">{!! nl2br(e($question->content)) !!}</div>
                    
                    @if($question->question_type === 'multiple_choice')
                        @php
                            $answer = $submission->answers[$question->id] ?? null;
                            $selectedOption = null;
                            
                            // Try to find the option by ID first
                            if (is_numeric($answer)) {
                                $selectedOption = $question->options->where('id', $answer)->first();
                            }
                            
                            // If not found, try to find by option_text
                            if (!$selectedOption && $answer) {
                                $selectedOption = $question->options->where('option_text', $answer)->first();
                            }
                        @endphp
                        <div class="bg-light rounded p-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle text-primary me-2"></i>
                                <div>
                                    @if($selectedOption)
                                        {{ $selectedOption->option_text }}
                                    @elseif($answer)
                                        {{ $answer }}
                                    @else
                                        No answer provided
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-light rounded p-3">
                            @if(isset($submission->answers[$question->id]) && $submission->answers[$question->id])
                                {!! nl2br(e($submission->answers[$question->id])) !!}
                            @else
                                <span class="text-muted">No answer provided</span>
                            @endif
                        </div>
                    @endif
                </div>
                @if(!$loop->last)
                    <hr class="my-4">
                @endif
            @endforeach
        </div>
    </div>
@endif

{{-- Grade Section (if graded) --}}
@if($submission->status === 'graded')
    <div class="card shadow-sm border-success mt-4">
        <div class="card-body">
            <h6 class="text-success mb-3">
                <i class="fas fa-award me-2"></i>Grade
            </h6>
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="bg-light rounded p-3 text-center">
                        <div class="h3 mb-0 text-success">{{ number_format($submission->grade, 1) }}%</div>
                        <div class="small text-muted">Overall Grade</div>
                    </div>
                </div>
                @if(isset($submission->feedback['general']))
                    <div class="col-md-8">
                        <div class="bg-light rounded p-3">
                            <div class="small text-muted mb-1">General Feedback</div>
                            <div>{!! nl2br(e($submission->feedback['general'])) !!}</div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endif

{{-- Debug Section --}}
@if(isset($isAdmin) && $isAdmin)
    <div class="card shadow-sm mt-4">
        <div class="card-header bg-light">
            <h6 class="mb-0"><i class="fas fa-bug me-2"></i>Debug Information</h6>
        </div>
        <div class="card-body">
            <h6>Raw Answers Data:</h6>
            <pre class="bg-light p-3 rounded">{{ json_encode($submission->answers, JSON_PRETTY_PRINT) }}</pre>
            
            <h6>Questions:</h6>
            <ul>
                @foreach($allocation->questions as $question)
                    <li>
                        <strong>ID:</strong> {{ $question->id }}<br>
                        <strong>Content:</strong> {{ $question->content }}<br>
                        <strong>Type:</strong> {{ $question->question_type }}<br>
                        @if($question->question_type === 'multiple_choice')
                            <strong>Options:</strong>
                            <ul>
                                @foreach($question->options as $option)
                                    <li>ID: {{ $option->id }} - {{ $option->option_text }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

@endsection 