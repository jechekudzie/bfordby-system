<div class="col-md-4 mb-4">
    <div class="card h-100">
        <div class="card-body">
            <div class="d-flex align-items-center mb-3">
                <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                    <i class="fas fa-qrcode text-primary fa-lg"></i>
                </div>
                <h5 class="mb-0 ms-3">Enrollment Codes</h5>
            </div>
            <p class="text-muted mb-3">Manage enrollment codes for student registrations and course assignments.</p>
            <a href="{{ route('admin.enrollment-codes.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-right me-2"></i>Manage Codes
            </a>
        </div>
    </div>
</div>

<div class="col-md-4 mb-4">
    <div class="card h-100">
        <div class="card-body">
            <div class="d-flex align-items-center mb-3">
                <div class="rounded-circle bg-info bg-opacity-10 p-3">
                    <i class="fas fa-clock text-info fa-lg"></i>
                </div>
                <h5 class="mb-0 ms-3">Study Modes</h5>
            </div>
            <p class="text-muted mb-3">Manage study modes like full-time, part-time, and distance learning options.</p>
            <a href="{{ route('admin.study-modes.index') }}" class="btn btn-outline-info">
                <i class="fas fa-arrow-right me-2"></i>Manage Modes
            </a>
        </div>
    </div>
</div> 