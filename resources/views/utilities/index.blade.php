@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h4 class="mb-4">Admin Dashboard</h4>
        </div>
    </div>

    <div class="row g-3">
        <!-- STUDENT MANAGEMENT -->
        <div class="col-md-12">
            <h5 class="mb-3 text-primary">Student Management</h5>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <i class="fas fa-user-graduate fa-3x text-primary"></i>
                    <h5 class="mt-3">Students</h5>
                    <p>Total: {{ $studentsCount }}</p>
                    <a href="{{ route('students.index') }}" class="btn btn-primary">
                        <i class="fas fa-list"></i> View All
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <i class="fas fa-user-check fa-3x text-info"></i>
                    <h5 class="mt-3">Enrollments</h5>
                    <p>Total: {{ $enrollmentsCount }}</p>
                    <a href="{{ route('students.courses', ['student' => 1]) }}" class="btn btn-info">
                        <i class="fas fa-list"></i> View All
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <i class="fas fa-dollar-sign fa-3x text-danger"></i>
                    <h5 class="mt-3">Payments</h5>
                    <p>Total: {{ $paymentsCount }}</p>
                    <a href="{{ route('students.payments.index', ['student' => 1]) }}" class="btn btn-danger">
                        <i class="fas fa-list"></i> View All
                    </a>
                </div>
            </div>
        </div>

        <!-- ACADEMIC MANAGEMENT -->
        <div class="col-md-12 mt-4">
            <h5 class="mb-3 text-success">Academic Management</h5>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <i class="fas fa-book fa-3x text-primary"></i>
                    <h5 class="mt-3">Courses</h5>
                    <p>Total: {{ $coursesCount }}</p>
                    <a href="{{ route('admin.courses.index') }}" class="btn btn-primary">
                        <i class="fas fa-list"></i> View All
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <i class="fas fa-calendar-alt fa-3x text-secondary"></i>
                    <h5 class="mt-3">Semesters</h5>
                    <p>Total: {{ $semestersCount }}</p>
                    <a href="{{ route('admin.semesters.index') }}" class="btn btn-secondary">
                        <i class="fas fa-list"></i> View All
                    </a>
                </div>
            </div>
        </div>

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



        <!-- SYSTEM CONFIGURATIONS -->
        <div class="col-md-12 mt-4">
            <h5 class="mb-3 text-secondary">System Configurations</h5>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <i class="fas fa-address-book fa-3x text-secondary"></i>
                    <h5 class="mt-3">Contact Types</h5>
                    <a href="{{ route('admin.contact-types.index') }}" class="btn btn-secondary">
                        <i class="fas fa-list"></i> View All
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <i class="fas fa-map-marker-alt fa-3x text-secondary"></i>
                    <h5 class="mt-3">Address Types</h5>
                    <a href="{{ route('admin.address-types.index') }}" class="btn btn-secondary">
                        <i class="fas fa-list"></i> View All
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <i class="fas fa-venus-mars fa-3x text-secondary"></i>
                    <h5 class="mt-3">Genders</h5>
                    <a href="{{ route('admin.genders.index') }}" class="btn btn-secondary">
                        <i class="fas fa-list"></i> View All
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <i class="fas fa-id-badge fa-3x text-secondary"></i>
                    <h5 class="mt-3">Titles</h5>
                    <a href="{{ route('admin.titles.index') }}" class="btn btn-secondary">
                        <i class="fas fa-list"></i> View All
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <i class="fas fa-globe fa-3x text-secondary"></i>
                    <h5 class="mt-3">Countries</h5>
                    <a href="{{ route('admin.countries.index') }}" class="btn btn-secondary">
                        <i class="fas fa-list"></i> View All
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <i class="fas fa-graduation-cap fa-3x text-secondary"></i>
                    <h5 class="mt-3">Qualification Levels</h5>
                    <a href="{{ route('admin.qualification-levels.index') }}" class="btn btn-secondary">
                        <i class="fas fa-list"></i> View All
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection