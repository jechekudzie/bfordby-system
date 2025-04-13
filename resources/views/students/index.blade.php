@extends('layouts.admin')

@push('head')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
@endpush

@section('content')
<!-- Statistics Cards -->
<div class="row g-3 mb-3">
    <div class="col-sm-6 col-lg-3">
        <div class="card overflow-hidden" style="min-width: 12rem">
            <div class="bg-holder bg-card" style="background-image:url(/assets/img/icons/spot-illustrations/corner-1.png);"></div>
            <div class="card-body position-relative">
                <h6>Total Students</h6>
                <div class="display-4 fs-4 mb-2 fw-normal font-sans-serif text-warning">{{ $students->count() }}</div>
                <a class="fw-semi-bold fs--1 text-nowrap" href="#">See all<span class="fas fa-angle-right ms-1"></span></a>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card overflow-hidden" style="min-width: 12rem">
            <div class="bg-holder bg-card" style="background-image:url(/assets/img/icons/spot-illustrations/corner-2.png);"></div>
            <div class="card-body position-relative">
                <h6>Active Students</h6>
                <div class="display-4 fs-4 mb-2 fw-normal font-sans-serif text-info">{{ $students->where('status', 'active')->count() }}</div>
                <a class="fw-semi-bold fs--1 text-nowrap" href="#">View active<span class="fas fa-angle-right ms-1"></span></a>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card overflow-hidden" style="min-width: 12rem">
            <div class="bg-holder bg-card" style="background-image:url(/assets/img/icons/spot-illustrations/corner-3.png);"></div>
            <div class="card-body position-relative">
                <h6>Recent Enrollments</h6>
                <div class="display-4 fs-4 mb-2 fw-normal font-sans-serif text-success">
                    {{ $students->where('enrollment_date', '>=', \Carbon\Carbon::now()->subDays(30))->count() }}
                </div>
                <a class="fw-semi-bold fs--1 text-nowrap" href="#">Last 30 days<span class="fas fa-angle-right ms-1"></span></a>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card overflow-hidden" style="min-width: 12rem">
            <div class="bg-holder bg-card" style="background-image:url(/assets/img/icons/spot-illustrations/corner-4.png);"></div>
            <div class="card-body position-relative">
                <h6>Inactive Students</h6>
                <div class="display-4 fs-4 mb-2 fw-normal font-sans-serif text-danger">{{ $students->where('status', '!=', 'active')->count() }}</div>
                <a class="fw-semi-bold fs--1 text-nowrap" href="#">View inactive<span class="fas fa-angle-right ms-1"></span></a>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filter -->
<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center bg-light">
        <h5 class="mb-0">Student Management</h5>
        <div>
            <a href="{{ route('students.create') }}" class="btn btn-falcon-success btn-sm me-2">
                <span class="fas fa-plus me-1"></span> Enroll Student
            </a>
            <button class="btn btn-falcon-default btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                <span class="fas fa-filter me-1"></span> Filters
            </button>
        </div>
    </div>
    <div class="collapse" id="filterCollapse">
        <div class="card-body border-top">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select class="form-select form-select-sm" id="statusFilter">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Gender</label>
                    <select class="form-select form-select-sm" id="genderFilter">
                        <option value="">All Genders</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Sort By</label>
                    <select class="form-select form-select-sm" id="sortBy">
                        <option value="name">Name</option>
                        <option value="date">Enrollment Date</option>
                        <option value="status">Status</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Student Cards -->
<div class="card">
    <div class="card-body p-0">
        <div class="row g-0">
            @forelse($students as $student)
                <div class="col-xl-3 col-lg-4 col-md-6 p-3 border-bottom border-end">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar avatar-xl me-3">
                            @php
                                $initials = strtoupper(substr($student->first_name, 0, 1) . substr($student->last_name, 0, 1));
                                $bgColors = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'];
                                $colorIndex = abs(crc32($student->first_name)) % count($bgColors);
                                $bgColor = $bgColors[$colorIndex];
                            @endphp
                            <div class="avatar-initial rounded-circle" style="background-color: {{ $bgColor }}; width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 18px;">
                                {{ $initials }}
                            </div>
                        </div>
                        <div>
                            <h6 class="mb-0">{{ $student->first_name }} {{ $student->last_name }}</h6>
                            <small class="text-muted">ID: {{ $student->student_number }}</small>
                        </div>
                    </div>
                    
                    <div class="student-info mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <span class="fas fa-calendar-alt text-primary me-2"></span>
                            <small>{{ \Carbon\Carbon::parse($student->date_of_birth)->format('d/m/Y') }}</small>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <span class="fas fa-venus-mars text-info me-2"></span>
                            <small>{{ $student->gender->name ?? 'N/A' }}</small>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="fas fa-clock text-warning me-2"></span>
                            <small>Enrolled: {{ \Carbon\Carbon::parse($student->enrollment_date)->format('d/m/Y') }}</small>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge bg-{{ $student->status == 'active' ? 'success' : 'secondary' }} rounded-pill">
                            {{ ucfirst($student->status) }}
                        </span>
                        <div class="btn-group">
                            <a href="{{ route('students.show', $student->slug) }}" class="btn btn-falcon-info btn-sm" title="View Details">
                                <span class="fas fa-eye"></span>
                            </a>
                            <a href="{{ route('students.edit', $student->slug) }}" class="btn btn-falcon-primary btn-sm" title="Edit">
                                <span class="fas fa-edit"></span>
                            </a>
                            <form action="{{ route('students.destroy', $student->slug) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-falcon-danger btn-sm" onclick="return confirm('Are you sure?')" title="Delete">
                                    <span class="fas fa-trash"></span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <span class="fas fa-users fa-3x text-muted"></span>
                        </div>
                        <h6 class="text-muted">No students found</h6>
                        <a href="{{ route('students.create') }}" class="btn btn-falcon-primary btn-sm mt-2">
                            <span class="fas fa-plus me-1"></span> Enroll First Student
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>

<style>
.avatar {
    position: relative;
    display: inline-block;
}

.avatar-xl {
    width: 48px;
    height: 48px;
}

.student-info {
    padding: 0.5rem;
    background-color: rgba(0, 0, 0, 0.02);
    border-radius: 0.5rem;
}

.btn-group .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

.badge {
    padding: 0.5em 0.75em;
}

.bg-holder {
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    background-size: contain;
    background-position: right;
    background-repeat: no-repeat;
    opacity: 0.1;
}

.card {
    position: relative;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}
</style>

@push('scripts')
<script>
    // Initialize filters
    document.addEventListener('DOMContentLoaded', function() {
        const statusFilter = document.getElementById('statusFilter');
        const genderFilter = document.getElementById('genderFilter');
        const sortBy = document.getElementById('sortBy');

        // Add event listeners for filter changes
        [statusFilter, genderFilter, sortBy].forEach(filter => {
            filter.addEventListener('change', applyFilters);
        });

        function applyFilters() {
            // Add your filter logic here
            // You can use AJAX to fetch filtered results or implement client-side filtering
            console.log('Filters changed:', {
                status: statusFilter.value,
                gender: genderFilter.value,
                sort: sortBy.value
            });
        }
    });
</script>
@endpush

@endsection
