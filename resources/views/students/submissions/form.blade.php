@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-edit text-primary me-2"></i>{{ $allocation->assessment->name }}
                        </h5>
                        <a href="{{ route('students.enrollments.show', ['student' => $student, 'enrollment' => $student->enrollments()->first()]) }}" 
                           class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left me-2"></i>Back to Assessments
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    {{-- Timer for Timed Assessments --}}
                    @if($allocation->is_timed && $submission->started_at)
                        <div class="alert alert-warning mb-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">
                                    <i class="fas fa-clock me-2"></i>Time Remaining
                                </h6>
                                <div id="timer" class="h5 mb-0" 
                                     data-start="{{ $submission->started_at }}" 
                                     data-duration="{{ $allocation->duration_minutes }}">
                                    Loading...
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Submission Form --}}
                    <form action="{{ $submission->id ? route('students.submissions.update', $allocation) : route('students.submissions.store', $allocation) }}"
                          method="POST"
                          enctype="multipart/form-data">
                        @csrf
                        @if($submission->id)
                            @method('PUT')
                        @endif

                        @if($allocation->submission_type === 'upload')
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">File Upload</h6>
                                    <div class="mb-3">
                                        <label for="file" class="form-label">Select File to Upload</label>
                                        <input type="file" 
                                               class="form-control @error('file') is-invalid @enderror" 
                                               id="file" 
                                               name="file"
                                               accept=".pdf,.doc,.docx,.txt">
                                        @error('file')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">
                                            Accepted file types: PDF, DOC, DOCX, TXT
                                            <br>Maximum file size: 10MB
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @elseif($allocation->submission_type === 'online')
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title mb-4">Online Assessment</h6>
                                    @foreach($allocation->questions as $index => $question)
                                        <div class="mb-4">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <label class="form-label fw-medium">Question {{ $index + 1 }}</label>
                                                @if($question->points)
                                                    <span class="badge bg-primary">{{ $question->points }} points</span>
                                                @endif
                                            </div>
                                            <p class="mb-3">{{ $question->content }}</p>
                                            
                                            @if($question->type === 'multiple_choice')
                                                <div class="list-group">
                                                    @foreach($question->options as $option)
                                                        <label class="list-group-item">
                                                            <input type="radio" 
                                                                   class="form-check-input me-2 @error('answers.'.$question->id) is-invalid @enderror"
                                                                   name="answers[{{ $question->id }}]"
                                                                   value="{{ $option }}"
                                                                   {{ old("answers.{$question->id}", $submission->answers[$question->id] ?? '') === $option ? 'checked' : '' }}>
                                                            {{ $option }}
                                                        </label>
                                                    @endforeach
                                                </div>
                                            @else
                                                <textarea class="form-control @error('answers.'.$question->id) is-invalid @enderror"
                                                          name="answers[{{ $question->id }}]"
                                                          rows="3"
                                                          placeholder="Type your answer here...">{{ old("answers.{$question->id}", $submission->answers[$question->id] ?? '') }}</textarea>
                                            @endif
                                            @error('answers.'.$question->id)
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-paper-plane me-2"></i>Submit Assessment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@if($allocation->is_timed)
    @push('scripts')
    <script>
        // Timer functionality
        function updateTimer() {
            const timerElement = document.getElementById('timer');
            const startTime = new Date(timerElement.dataset.start);
            const durationMinutes = parseInt(timerElement.dataset.duration);
            const endTime = new Date(startTime.getTime() + durationMinutes * 60000);
            
            function pad(num) {
                return num.toString().padStart(2, '0');
            }
            
            const interval = setInterval(() => {
                const now = new Date();
                const diff = endTime - now;
                
                if (diff <= 0) {
                    clearInterval(interval);
                    timerElement.textContent = '00:00';
                    document.querySelector('form').submit();
                    return;
                }
                
                const minutes = Math.floor(diff / 60000);
                const seconds = Math.floor((diff % 60000) / 1000);
                timerElement.textContent = `${pad(minutes)}:${pad(seconds)}`;
            }, 1000);
        }
        
        document.addEventListener('DOMContentLoaded', updateTimer);
    </script>
    @endpush
@endif
@endsection 