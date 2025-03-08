@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">
            <i class="fas fa-cogs text-primary me-2"></i>System Utilities
        </h4>
    </div>

    <!-- Academic Management Section -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">
                <i class="fas fa-graduation-cap text-primary me-2"></i>Academic Management
            </h5>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="fas fa-book fa-2x text-primary"></i>
                            </div>
                            <h6>Courses</h6>
                            <p class="text-muted mb-3">Total: {{ $coursesCount }}</p>
                            <a href="{{ route('admin.courses.index') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-list me-1"></i>Manage Courses
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="fas fa-calendar-alt fa-2x text-success"></i>
                            </div>
                            <h6>Semesters</h6>
                            <p class="text-muted mb-3">Total: {{ $semestersCount }}</p>
                            <a href="{{ route('admin.semesters.index') }}" class="btn btn-success btn-sm">
                                <i class="fas fa-list me-1"></i>Manage Semesters
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="fas fa-clock fa-2x text-info"></i>
                            </div>
                            <h6>Study Modes</h6>
                            <p class="text-muted mb-3">&nbsp;</p>
                            <a href="{{ route('admin.study-modes.index') }}" class="btn btn-info btn-sm">
                                <i class="fas fa-list me-1"></i>Manage Modes
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="fas fa-qrcode fa-2x text-warning"></i>
                            </div>
                            <h6>Enrollment Codes</h6>
                            <p class="text-muted mb-3">&nbsp;</p>
                            <a href="{{ route('admin.enrollment-codes.index') }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-list me-1"></i>Manage Codes
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Student Management Section -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">
                <i class="fas fa-user-graduate text-primary me-2"></i>Student Management
            </h5>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="fas fa-users fa-2x text-primary"></i>
                            </div>
                            <h6>Students</h6>
                            <p class="text-muted mb-3">Total: {{ $studentsCount }}</p>
                            <a href="{{ route('students.index') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-list me-1"></i>View Students
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="fas fa-user-check fa-2x text-success"></i>
                            </div>
                            <h6>Enrollment</h6>
                            <p class="text-muted mb-3">Total: {{ $enrollmentsCount }}</p>
                            <a href="{{ route('students.create') }}" class="btn btn-success btn-sm">
                                <i class="fas fa-list me-1"></i> Enroll Student
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="fas fa-dollar-sign fa-2x text-danger"></i>
                            </div>
                            <h6>Payments</h6>
                            <p class="text-muted mb-3">Total: {{ $paymentsCount }}</p>
                            <a href="{{ route('students.payments.index', ['student' => 1]) }}" class="btn btn-danger btn-sm">
                                <i class="fas fa-list me-1"></i>View Payments
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- System Configuration Section -->
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">
                <i class="fas fa-cog text-primary me-2"></i>System Configuration
            </h5>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <!-- Personal Information -->
                <div class="col-12">
                    <h6 class="text-muted mb-3">Personal Information Settings</h6>
                </div>
                
                <div class="col-md-3">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="fas fa-id-badge fa-2x text-info"></i>
                            </div>
                            <h6>Titles</h6>
                            <a href="{{ route('admin.titles.index') }}" class="btn btn-info btn-sm">
                                <i class="fas fa-cog me-1"></i>Manage Titles
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="fas fa-venus-mars fa-2x text-purple"></i>
                            </div>
                            <h6>Genders</h6>
                            <a href="{{ route('admin.genders.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-cog me-1"></i>Manage Genders
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="col-12">
                    <h6 class="text-muted mb-3">Contact Information Settings</h6>
                </div>

                <div class="col-md-3">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="fas fa-address-book fa-2x text-success"></i>
                            </div>
                            <h6>Contact Types</h6>
                            <a href="{{ route('admin.contact-types.index') }}" class="btn btn-success btn-sm">
                                <i class="fas fa-cog me-1"></i>Manage Types
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="fas fa-map-marker-alt fa-2x text-danger"></i>
                            </div>
                            <h6>Address Types</h6>
                            <a href="{{ route('admin.address-types.index') }}" class="btn btn-danger btn-sm">
                                <i class="fas fa-cog me-1"></i>Manage Types
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Location Information -->
                <div class="col-12">
                    <h6 class="text-muted mb-3">Location Settings</h6>
                </div>

                <div class="col-md-3">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="fas fa-globe fa-2x text-primary"></i>
                            </div>
                            <h6>Countries</h6>
                            <a href="{{ route('admin.countries.index') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-cog me-1"></i>Manage Countries
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Academic Information -->
                <div class="col-12">
                    <h6 class="text-muted mb-3">Academic Settings</h6>
                </div>

                <div class="col-md-3">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="fas fa-graduation-cap fa-2x text-warning"></i>
                            </div>
                            <h6>Qualification Levels</h6>
                            <a href="{{ route('admin.qualification-levels.index') }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-cog me-1"></i>Manage Levels
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection