@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">
                        <i class="fas fa-file-alt text-primary me-2"></i>{{ $allocation->assessment->name }}
                    </h5>
                    @if($submission->status === 'submitted')
                        <div class="mt-2 text-success">
                            <i class="fas fa-check-circle me-1"></i>
                            File Already Submitted
                            <div class="small text-muted">
                                Uploaded {{ $submission->submitted_at->diffForHumans() }}
                            </div>
                        </div>
                    @endif
                </div>
                <a href="{{ route('students.assessments.list') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Back to List
                </a>
            </div>
        </div>

        <div class="card-body">
            {{-- Assessment Details --}}
            <div class="row g-4 mb-4">
                @if($submission->status === 'pending')
                    <div class="col-12">
                        <div class="alert alert-primary d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1"><i class="fas fa-info-circle me-2"></i>Ready to Submit?</h5>
                                <p class="mb-0">You can now submit your assessment. Make sure to review all requirements before submitting.</p>
                            </div>
                            @if($allocation->submission_type === 'online' && $allocation->is_timed)
                                <form action="{{ route('students.submissions.start-timed', $allocation) }}" method="POST" class="ms-3">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-clock me-2"></i>Start Timed Assessment
                                    </button>
                                </form>
                            @else
                                <a href="#submissionForm" class="btn btn-primary btn-lg">
                                    @if($allocation->submission_type === 'file')
                                        <i class="fas fa-upload me-2"></i>Upload Submission
                                    @else
                                        <i class="fas fa-paper-plane me-2"></i>Submit Assessment
                                    @endif
                                </a>
                            @endif
                        </div>
                    </div>
                @endif

                <div class="col-md-6">
                    <div class="p-3 bg-light rounded-3">
                        <h6 class="text-primary mb-3">Assessment Details</h6>
                        <div class="mb-2">
                            <span class="text-muted">Type:</span>
                            <span class="badge bg-{{ 
                                $allocation->assessment->type === 'exam' ? 'danger' : 
                                ($allocation->assessment->type === 'test' ? 'warning' : 
                                ($allocation->assessment->type === 'practical' ? 'info' : 'primary')) 
                            }} ms-2">
                                {{ ucfirst($allocation->assessment->type) }}
                            </span>
                        </div>
                        <div class="mb-2">
                            <span class="text-muted">Due Date:</span>
                            <span class="fw-medium ms-2">
                                {{ $allocation->due_date ? date('M d, Y H:i', strtotime($allocation->due_date)) : 'Not set' }}
                            </span>
                        </div>
                        <div>
                            <span class="text-muted">Submission Type:</span>
                            <span class="badge bg-info ms-2">{{ ucfirst($allocation->submission_type) }}</span>
                        </div>
                        @if($allocation->is_timed)
                            <div class="mt-2">
                                <span class="text-muted">Time Limit:</span>
                                <span class="badge bg-warning ms-2">
                                    <i class="fas fa-clock me-1"></i>{{ $allocation->duration_minutes }} minutes
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="p-3 bg-light rounded-3">
                        <h6 class="text-primary mb-3">Submission Status</h6>
                        <div class="mb-2">
                            <span class="text-muted">Status:</span>
                            <span class="badge bg-{{ 
                                $submission->status === 'graded' ? 'success' : 
                                ($submission->status === 'submitted' ? 'info' : 
                                ($submission->status === 'in_progress' ? 'warning' : 'secondary')) 
                            }} ms-2">
                                {{ ucfirst($submission->status) }}
                            </span>
                        </div>
                        @if($submission->submitted_at)
                            <div class="mb-2">
                                <span class="text-muted">Submitted:</span>
                                <span class="fw-medium ms-2">{{ date('M d, Y H:i', strtotime($submission->submitted_at)) }}</span>
                            </div>
                        @endif
                        @if($submission->grade)
                            <div class="mb-2">
                                <span class="text-muted">Grade:</span>
                                <span class="fw-medium ms-2">{{ $submission->grade }}%</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Assessment Instructions --}}
            @if($allocation->assessment->description)
                <div class="mb-4">
                    <h6 class="text-primary mb-3">Instructions</h6>
                    <div class="p-3 bg-light rounded-3">
                        {!! nl2br(e($allocation->assessment->description)) !!}
                    </div>
                </div>
            @endif

            @if($submission->status === 'pending' || $submission->status === 'in_progress')
                @if($allocation->submission_type === 'online' && $allocation->is_timed && !$submission->start_time)
                    {{-- Start Timed Assessment Button --}}
                    <div class="alert alert-warning">
                        <h5><i class="fas fa-exclamation-triangle me-2"></i>Timed Assessment</h5>
                        <p>This is a timed online assessment. Once you start, you will have {{ $allocation->duration_minutes }} minutes to complete it.
                           The timer cannot be paused, and your answers will be automatically submitted when the time is up.</p>
                        <form action="{{ route('students.submissions.start-timed', $allocation) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-clock me-2"></i>Start Assessment
                            </button>
                        </form>
                    </div>
                @else
                    @if($allocation->is_timed && $submission->start_time)
                        {{-- Timer Display --}}
                        <div class="alert alert-info mb-4">
                            <h5><i class="fas fa-clock me-2"></i>Time Remaining</h5>
                            <p>Time started: {{ date('Y-m-d H:i:s', strtotime($submission->start_time)) }}</p>
                            <p>Time remaining: <span id="timer" class="fw-bold">Calculating...</span></p>
                        </div>
                    @endif

                    {{-- Submission Form --}}
                    <form action="{{ $submission->id ? route('students.submissions.update', $allocation) : route('students.submissions.store', $allocation) }}"
                          method="POST"
                          enctype="multipart/form-data"
                          id="submissionForm">
                        @csrf
                        @if($submission->id)
                            @method('PUT')
                        @endif

                        @if($allocation->submission_type === 'file')
                            <div class="mb-3">
                                <label for="file" class="form-label">Upload File</label>
                                <input type="file" 
                                       class="form-control @error('file') is-invalid @enderror" 
                                       id="file" 
                                       name="file"
                                       accept=".pdf,.doc,.docx,.txt">
                                @error('file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Maximum file size: 10MB</div>
                                @if($submission->file_path)
                                    <div class="mt-2">
                                        <p>Current file: 
                                            <a href="{{ route('students.submissions.download', $allocation) }}" class="text-primary">
                                                <i class="fas fa-download me-1"></i>Download
                                            </a>
                                        </p>
                                    </div>
                                @endif
                            </div>
                        @elseif($allocation->submission_type === 'text')
                            <div class="mb-3">
                                <label for="content" class="form-label">Your Answer</label>
                                <textarea class="form-control @error('content') is-invalid @enderror" 
                                          id="content" 
                                          name="content" 
                                          rows="10"
                                          placeholder="Type your answer here...">{{ old('content', $submission->content) }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @elseif($allocation->submission_type === 'online')
                            @foreach($allocation->questions as $index => $question)
                                <div class="mb-4">
                                    <label class="form-label fw-medium">Question {{ $index + 1 }}</label>
                                    <p class="mb-2">{{ $question->content }}</p>
                                    @if($question->type === 'multiple_choice')
                                        @foreach($question->options as $option)
                                            <div class="form-check">
                                                <input type="radio" 
                                                       class="form-check-input @error('answers.'.$question->id) is-invalid @enderror"
                                                       name="answers[{{ $question->id }}]"
                                                       id="option_{{ $question->id }}_{{ $loop->index }}"
                                                       value="{{ $option }}"
                                                       {{ old("answers.{$question->id}", $submission->answers[$question->id] ?? '') === $option ? 'checked' : '' }}>
                                                <label class="form-check-label" for="option_{{ $question->id }}_{{ $loop->index }}">
                                                    {{ $option }}
                                                </label>
                                            </div>
                                        @endforeach
                                    @else
                                        <textarea class="form-control @error('answers.'.$question->id) is-invalid @enderror"
                                                  name="answers[{{ $question->id }}]"
                                                  rows="3"
                                                  placeholder="Type your answer here...">{{ old("answers.{$question->id}", $submission->answers[$question->id] ?? '') }}</textarea>
                                    @endif
                                    @error('answers.'.$question->id)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endforeach
                        @endif

                        <div class="d-flex justify-content-between align-items-center">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-paper-plane me-2"></i>
                                @if(isset($group) && $group)
                                {{ $submission->id ? 'Update Group Submission' : 'Submit Group Assignment' }}
                                @else
                                {{ $submission->id ? 'Update Submission' : 'Submit Assessment' }}
                                @endif
                            </button>
                            @if($submission->id && !$submission->graded_at)
                                <form action="{{ route('students.submissions.destroy', $allocation) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('Are you sure you want to delete this submission?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash me-2"></i>Delete Submission
                                    </button>
                                </form>
                            @endif
                        </div>
                    </form>
                @endif
            @else
                {{-- Show submitted content --}}
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    This assessment has been submitted and can no longer be modified.
                </div>

                @if($submission->file_path)
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">Submitted File</h6>
                        <div class="p-3 bg-light rounded-3">
                            <a href="{{ route('students.submissions.download', $allocation) }}" 
                               class="btn btn-outline-primary">
                                <i class="fas fa-download me-2"></i>Download Submission
                            </a>
                        </div>
                    </div>
                @endif

                @if($submission->content)
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">Your Answer</h6>
                        <div class="p-3 bg-light rounded-3">
                            {!! nl2br(e($submission->content)) !!}
                        </div>
                    </div>
                @endif

                @if($submission->answers)
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">Your Answers</h6>
                        @foreach($allocation->questions as $index => $question)
                            <div class="mb-3">
                                <div class="fw-medium mb-2">Question {{ $index + 1 }}</div>
                                <div class="p-3 bg-light rounded-3">
                                    <p class="mb-2">{{ $question->content }}</p>
                                    <div class="ms-3">
                                        <strong>Your Answer:</strong>
                                        <div class="mt-1">{{ $submission->answers[$question->id] ?? 'Not answered' }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if($submission->feedback)
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">Feedback</h6>
                        <div class="p-3 bg-light rounded-3">
                            {!! nl2br(e($submission->feedback)) !!}
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>

    @if($allocation->is_timed && $submission->start_time)
        <script type="text/javascript">
            // Wait for document to be ready
            window.onload = function() {
                // Timer variables
                var startTimeStr = "{{ date('Y-m-d H:i:s', strtotime($submission->start_time)) }}";
                var durationMin = parseInt("{{ $allocation->duration_minutes }}");
                
                function updateTimer() {
                    // Parse dates
                    var startTime = new Date(startTimeStr);
                    var endTime = new Date(startTime.getTime() + (durationMin * 60 * 1000));
                    var currentTime = new Date();
                    var timeLeft = endTime - currentTime;
                    
                    // Update timer display
                    var timerElement = document.getElementById('timer');
                    
                    if (!timerElement) return;
                    
                    if (timeLeft <= 0) {
                        timerElement.textContent = "Time's up!";
                        var form = document.getElementById('submissionForm');
                        if (form) form.submit();
                        return;
                    }
                    
                    // Calculate minutes and seconds
                    var minutes = Math.floor(timeLeft / 60000);
                    var seconds = Math.floor((timeLeft % 60000) / 1000);
                    
                    // Display the time
                    var minuteText = minutes + " minute" + (minutes !== 1 ? "s" : "");
                    var secondText = seconds + " second" + (seconds !== 1 ? "s" : "");
                    timerElement.textContent = minuteText + " and " + secondText + " remaining";
                }
                
                // Run timer immediately and then every second
                updateTimer();
                setInterval(updateTimer, 1000);
            };
        </script>
    @endif
</div>
@endsection
