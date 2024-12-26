<div class="card mb-3">
    <div class="card-header">
        <div class="row flex-between-end">
            <div class="col-auto align-self-center">
                <h5 class="mb-0">Residency Information</h5>
            </div>
            <div class="col-auto ms-auto">
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#assignRoomModal">
                    <span class="fas fa-plus me-2"></span>Assign Room
                </button>
            </div>
        </div>
    </div>
    <div class="card-body">
        @if($student->currentResidency)
            <div class="row">
                <div class="col-md-6">
                    <h6 class="fs-0">Current Room Assignment</h6>
                    <div class="mt-3">
                        <div class="row mb-2">
                            <div class="col-4 fw-semi-bold">Room Number:</div>
                            <div class="col">{{ $student->currentResidency->room_number }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4 fw-semi-bold">Semester:</div>
                            <div class="col">{{ $student->currentResidency->semester->name }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4 fw-semi-bold">Payment Status:</div>
                            <div class="col">
                                <span class="badge bg-{{ $student->currentResidency->is_paid ? 'success' : 'warning' }}">
                                    {{ $student->currentResidency->is_paid ? 'Paid' : 'Pending' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <h6 class="fs-0">Damage Reports</h6>
                    <div class="mt-3">
                        @if($student->currentResidency->damages)
                            <div class="alert alert-warning">
                                {{ $student->currentResidency->damages }}
                            </div>
                        @else
                            <div class="alert alert-success">
                                No damage reports filed
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-3">
                <div class="alert alert-info mb-0">
                    No current residency assignment found
                </div>
            </div>
        @endif

        @if($student->pastResidencies->count() > 0)
</div>
