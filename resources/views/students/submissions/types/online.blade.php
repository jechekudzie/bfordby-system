@extends('students.submissions.layouts.base')

@section('submission_content')
    @if($allocation->is_timed && $submission->start_time)
        <div class="mb-4">
            <div class="badge bg-danger p-2">
                <i class="fas fa-clock me-1"></i> 
                <span id="timer-display">Loading timer...</span>
            </div>
        </div>
    @endif

    <!-- Debug info - will be hidden in production -->
    <div class="alert alert-info mb-4" id="debug-info">
        <p><strong>Debug Info:</strong></p>
        <div id="debug-output"></div>
    </div>

    <form action="{{ $submission->id ? route('students.submissions.update', $allocation) : route('students.submissions.store', $allocation) }}"
          method="POST"
          id="assessmentForm">
        @csrf
        <input type="hidden" id="csrf-token-value" name="_token" value="{{ csrf_token() }}">
        @if($submission->id)
            @method('PUT')
        @endif

        @forelse($allocation->questions as $index => $question)
            <div class="mb-4 p-4 border rounded {{ $index > 0 ? 'mt-4' : '' }}">
                <div class="d-flex align-items-start mb-3">
                    <div class="bg-light rounded-circle p-2 me-3">
                        <span class="fw-bold">{{ $index + 1 }}</span>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-0">{{ $question->question_text }}</h6>
                        @if($question->weight)
                            <small class="text-muted">
                                <i class="fas fa-star text-warning me-1"></i>{{ $question->weight }} points
                            </small>
                        @endif
                    </div>
                </div>

                @if($question->question_type === 'multiple_choice')
                    <div class="ms-5">
                        @foreach($question->options as $option)
                            <div class="form-check mb-2">
                                <input type="radio" 
                                       class="form-check-input" 
                                       name="answers[{{ $question->id }}]" 
                                       id="option_{{ $option->id }}"
                                       value="{{ $option->id }}"
                                       {{ old("answers.{$question->id}", optional($submission->answers)[$question->id] ?? '') == $option->id ? 'checked' : '' }}>
                                <label class="form-check-label" for="option_{{ $option->id }}">
                                    {{ $option->option_text }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="ms-5">
                        <textarea class="form-control" 
                                  name="answers[{{ $question->id }}]" 
                                  rows="4" 
                                  placeholder="Enter your answer here...">{{ old("answers.{$question->id}", optional($submission->answers)[$question->id] ?? '') }}</textarea>
                    </div>
                @endif

                @error("answers.{$question->id}")
                    <div class="ms-5 mt-2 text-danger">
                        <small><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                    </div>
                @enderror
            </div>
        @empty
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>No questions have been added to this assessment yet.
            </div>
        @endforelse

        @if($allocation->questions->isNotEmpty())
            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane me-2"></i>Submit Assessment
                </button>
            </div>
        @endif
    </form>
@endsection

@push('scripts')
<style>
#debug-info {
    font-family: monospace;
    font-size: 12px;
    max-height: 200px;
    overflow-y: auto;
}
.timer-badge {
    font-family: monospace;
    font-size: 1.2rem;
}
#timer-display {
    font-family: monospace;
    font-weight: bold;
    font-size: 1.1rem;
    min-width: 60px;
    display: inline-block;
    text-align: center;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Only run if we have both a timer display and a timed assessment
    const timerDisplay = document.getElementById('timer-display');
    if (!timerDisplay) return;

    // Get the timestamps directly from PHP to avoid timezone issues
    const startTimestamp = {{ strtotime($submission->start_time) }};
    const durationMinutes = {{ $allocation->duration_minutes }};
    const endTimestamp = startTimestamp + (durationMinutes * 60);
    
    // Flag to prevent multiple submissions
    let hasSubmitted = false;
    
    // Refresh CSRF token periodically to prevent expiration
    function refreshCsrfToken() {
        fetch('/csrf-token')
            .then(response => response.json())
            .then(data => {
                document.getElementById('csrf-token-value').value = data.token;
            })
            .catch(error => console.error('Error refreshing CSRF token:', error));
    }
    
    // Refresh token every 5 minutes
    const tokenRefreshInterval = setInterval(refreshCsrfToken, 5 * 60 * 1000);
    
    function updateTimer() {
        const now = Math.floor(Date.now() / 1000);
        const remaining = endTimestamp - now;
        
        if (remaining <= 0) {
            timerDisplay.textContent = "Time's up!";
            
            // Only submit once to prevent refresh loops
            if (!hasSubmitted) {
                hasSubmitted = true;
                clearInterval(tokenRefreshInterval); // Stop token refreshing
                
                // Refresh token one last time before submission
                fetch('/csrf-token')
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('csrf-token-value').value = data.token;
                        
                        // Show submission message
                        const formArea = document.querySelector('.card-body');
                        if (formArea) {
                            const messageDiv = document.createElement('div');
                            messageDiv.className = 'alert alert-warning mt-3';
                            messageDiv.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Time is up! Your answers are being submitted...';
                            formArea.prepend(messageDiv);
                        }
                        
                        // Disable all form fields
                        const inputs = document.querySelectorAll('#assessmentForm input:not([name="_token"]), #assessmentForm textarea, #assessmentForm button');
                        inputs.forEach(input => input.disabled = true);
                        
                        // Submit the form after a brief delay
                        setTimeout(() => {
                            document.getElementById('assessmentForm').submit();
                        }, 1500);
                    })
                    .catch(error => {
                        console.error('Error refreshing final CSRF token:', error);
                        alert('Session may have expired. Please save your work and refresh the page.');
                    });
            }
            
            return false; // Stop timer
        } else {
            const minutes = Math.floor(remaining / 60);
            const seconds = remaining % 60;
            timerDisplay.textContent = 
                `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            return true; // Continue timer
        }
    }
    
    // Update timer immediately
    if (updateTimer()) {
        // Start timer updates every second
        const timerInterval = setInterval(() => {
            if (!updateTimer()) {
                clearInterval(timerInterval);
            }
        }, 1000);
    }
});
</script>
@endpush 