<div class="card mb-3">
    <div class="card-header">
        <div class="row flex-between-end">
            <div class="col-auto align-self-center">
                <h5 class="mb-0">Assessment Records</h5>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-striped" id="assessments-table">
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Assessment Type</th>
                        <th>Score</th>
                        <th>Max Score</th>
                        <th>Percentage</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($student->assessments as $assessment)
                        <tr>
                            <td>{{ $assessment->subject->name }}</td>
                            <td>{{ ucfirst($assessment->type) }}</td>
                            <td>{{ $assessment->score }}</td>
                            <td>{{ $assessment->max_score }}</td>
                            <td>
                                @php
                                    $percentage = ($assessment->score / $assessment->max_score) * 100;
                                @endphp
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar bg-{{ $percentage >= 50 ? 'success' : 'danger' }}" 
                                         role="progressbar" 
                                         style="width: {{ $percentage }}%">
                                        {{ number_format($percentage, 1) }}%
                                    </div>
                                </div>
                            </td>
                            <td>{{ $assessment->created_at->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-3">No assessments recorded yet</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
