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

                    {{-- Assessment Instructions --}}
                    @if($allocation->content)
                        <div class="card mb-4">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2 text-muted">
                                    <i class="fas fa-info-circle me-2"></i>Instructions
                                </h6>
                                <div class="mt-3">
                                    {!! nl2br(e($allocation->content)) !!}
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Group Information (if applicable) --}}
                    @if($allocation->submission_type === 'group' && $group)
                        <div class="card mb-4">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-3 text-muted">
                                    <i class="fas fa-users me-2"></i>Group Members
                                </h6>
                                <div class="list-group list-group-flush">
                                    @foreach($group->students as $member)
                                        <div class="list-group-item px-0">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-light p-2 me-3">
                                                    <i class="fas fa-user-graduate text-primary"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $member->first_name }} {{ $member->last_name }}</h6>
                                                    <small class="text-muted">{{ optional($member->enrollments->first())->student_number }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Main Content Area --}}
                    @yield('submission_content')
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
@if($allocation->is_timed && $submission->started_at)
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const timerElement = document.getElementById('timer');
        const startTime = new Date(timerElement.dataset.start);
        const durationMinutes = parseInt(timerElement.dataset.duration);
        const endTime = new Date(startTime.getTime() + durationMinutes * 60000);

        function updateTimer() {
            const now = new Date();
            const timeLeft = endTime - now;

            if (timeLeft <= 0) {
                timerElement.innerHTML = 'Time\'s up!';
                document.querySelector('form').submit();
                return;
            }

            const minutes = Math.floor(timeLeft / 60000);
            const seconds = Math.floor((timeLeft % 60000) / 1000);
            timerElement.innerHTML = `${minutes}:${seconds.toString().padStart(2, '0')}`;
        }

        updateTimer();
        setInterval(updateTimer, 1000);
    });
</script>
@endif
@endpush
@endsection 