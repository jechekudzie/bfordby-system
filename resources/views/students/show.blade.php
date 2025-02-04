@extends('layouts.admin')

@push('head')
<style>
    .profile-header {
        background: linear-gradient(135deg, #065d40 0%, #043927 100%);
        color: white;
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .stat-card {
        background: white;
        border-radius: 10px;
        padding: 1rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .stat-icon {
        width: 45px;
        height: 45px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
    }

    .timeline {
        position: relative;
        padding-left: 3rem;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 2px;
        background: #e9ecef;
    }

    .timeline-item {
        position: relative;
        padding-bottom: 1.5rem;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -3rem;
        top: 0;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #065d40;
        border: 2px solid white;
    }

    .nav-pills .nav-link.active {
        background-color: #065d40;
        color: white !important;
    }

    .nav-pills .nav-link {
        color: #065d40;
    }

    .nav-pills .nav-link:hover:not(.active) {
        color: #043927;
    }

    .custom-table th {
        background-color: #f8f9fa;
        font-weight: 600;
    }

    .badge-outline {
        background-color: transparent;
        border: 1px solid currentColor;
    }

    .btn-primary {
        background-color: #065d40;
        border-color: #065d40;
    }

    .btn-primary:hover {
        background-color: #043927;
        border-color: #043927;
    }

    .text-primary {
        color: #065d40 !important;
    }

    .bg-primary-subtle {
        background-color: rgba(6, 93, 64, 0.1) !important;
    }

    .progress-bar {
        background-color: #065d40;
    }

    .btn-warning {
        background-color: #FFD800;
        border-color: #FFD800;
        color: #000;
    }

    .btn-warning:hover {
        background-color: #E6C200;
        border-color: #E6C200;
        color: #000;
    }

    .bg-warning-subtle {
        background-color: rgba(255, 216, 0, 0.1) !important;
    }

    .text-warning {
        color: #FFD800 !important;
    }

    .table-borderless td,
    .table-borderless th {
        padding: 0.75rem 0;
        vertical-align: middle;
    }

    .table-borderless thead th {
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        padding-bottom: 0.5rem;
        font-weight: 500;
    }

    .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .card-header {
        background-color: transparent;
        border-bottom: 1px solid rgba(0, 0, 0, 0.125);
    }

    .fas {
        width: 20px;
        text-align: center;
    }

    .btn-group .btn {
        padding: 0.25rem 0.5rem;
    }

    .badge {
        padding: 0.5em 0.75em;
    }

    .timeline {
        position: relative;
        padding: 1rem 0;
    }

    .timeline-point {
        position: relative;
        z-index: 2;
        background: white;
        padding: 8px 0;
    }

    .timeline .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        transition: transform 0.2s ease-in-out;
    }

    .timeline .card:hover {
        transform: translateY(-2px);
    }
</style>
@endpush

@section('content')
<div class="profile-header">
    <div class="row align-items-center">
        <div class="col-auto">
            <div class="avatar avatar-4xl">
                <div class="avatar-name rounded-circle bg-white text-primary">
                    <span>{{ strtoupper(substr($student->first_name, 0, 1) . substr($student->last_name, 0, 1)) }}</span>
                </div>
            </div>
        </div>
        <div class="col">
            <h3 class="mb-1 text-white">{{ $student->title->name ?? '' }} {{ $student->first_name }} {{ $student->last_name }}</h3>
            <p class="mb-0">Student ID: #{{ str_pad($student->id, 6, '0', STR_PAD_LEFT) }}</p>
            <span class="badge badge-outline {{ $student->status === 'active' ? 'text-success' : 'text-warning' }}">
                {{ ucfirst($student->status) }}
            </span>
        </div>

    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon bg-primary-subtle">
                <i class="fas fa-graduation-cap text-primary"></i>
            </div>
            <h6 class="text-800">Enrolled Courses</h6>
            <h3 class="mb-0">{{ $student->studentCourses->count() }}</h3>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon bg-success-subtle">
                <i class="fas fa-dollar-sign text-success"></i>
            </div>
            <h6 class="text-800">Total Payments</h6>
            <h3 class="mb-0">{{ number_format($student->studentPayments->sum('amount'), 2) }}</h3>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon bg-info-subtle">
                <i class="fas fa-book text-info"></i>
            </div>
            <h6 class="text-800">Qualifications</h6>
            <h3 class="mb-0">{{ $student->academicHistories->count() }}</h3>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon bg-warning-subtle">
                <i class="fas fa-calendar text-warning"></i>
            </div>
            <h6 class="text-800">Days Enrolled</h6>
            <h3 class="mb-0">{{ \Carbon\Carbon::parse($student->enrollment_date)->diffInDays(now()) }}</h3>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <ul class="nav nav-pills card-header-pills" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#personal" role="tab">Personal Info</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#documents" role="tab">Identification</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#health" role="tab">Health Record</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#academic" role="tab">Academic History</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#disciplinary" role="tab">Disciplinary Record</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#courses" role="tab">Courses</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#attendance" role="tab">Attendance</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#payments" role="tab">Payments</a>
            </li>

        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content">
            <!-- Personal Info Tab -->
            <div class="tab-pane fade show active" id="personal" role="tabpanel">
                <!-- Personal Details Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0 fs-5">
                            <i class="fas fa-user text-primary me-2"></i>Personal Information
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="text-muted" width="35%">
                                            <i class="fas fa-user text-primary me-2"></i> Full Name
                                        </td>
                                        <td>
                                            {{ $student->title->name ?? '' }}
                                            {{ $student->first_name }}
                                            {{ $student->middle_name ?? '' }}
                                            {{ $student->last_name }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">
                                            <i class="fas fa-id-card text-primary me-2"></i> Student Number
                                        </td>
                                        <td>{{ $student->student_number ?? 'Not Assigned' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">
                                            <i class="fas fa-birthday-cake text-primary me-2"></i> Date of Birth
                                        </td>
                                        <td>{{ $student->date_of_birth ? date('d/m/Y', strtotime($student->date_of_birth)) : 'Not Set' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="text-muted" width="35%">
                                            <i class="fas fa-venus-mars text-primary me-2"></i> Gender
                                        </td>
                                        <td>{{ $student->gender->name ?? 'Not Set' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">
                                            <i class="fas fa-calendar-check text-primary me-2"></i> Enrollment Date
                                        </td>
                                        <td>{{ $student->enrollment_date ? date('d/m/Y', strtotime($student->enrollment_date)) : 'Not Set' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">
                                            <i class="fas fa-user-clock text-primary me-2"></i> Status
                                        </td>
                                        <td>
                                            @php
                                            $statusColors = [
                                            'pending' => 'warning',
                                            'active' => 'success',
                                            'graduated' => 'info',
                                            'inactive' => 'secondary',
                                            'withdrawn' => 'danger'
                                            ];
                                            @endphp
                                            <span class="badge bg-{{ $statusColors[$student->status] ?? 'secondary' }}">
                                                {{ ucfirst($student->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information Card -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                            <i class="fas fa-address-book text-primary me-2"></i> Contact Information
                        </h6>
                        <a href="{{ route('students.contacts.create', $student) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i> Add Contact
                        </a>
                    </div>
                    <div class="card-body">
                        @if($student->contacts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th class="text-muted" width="25%">
                                            <i class="fas fa-layer-group text-primary me-2"></i> Type
                                        </th>
                                        <th class="text-muted">
                                            <i class="fas fa-info-circle text-primary me-2"></i> Value
                                        </th>
                                        <th class="text-muted" width="15%">
                                            <i class="fas fa-star text-primary me-2"></i> Primary
                                        </th>
                                        <th class="text-muted" width="15%">
                                            <i class="fas fa-cog text-primary me-2"></i> Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($student->contacts as $contact)
                                    <tr>
                                        <td>
                                            @php
                                            $contactIcons = [
                                            'Email' => 'fa-envelope',
                                            'Phone' => 'fa-phone',
                                            'Mobile' => 'fa-mobile-alt',
                                            'WhatsApp' => 'fa-whatsapp',
                                            'Address' => 'fa-map-marker-alt'
                                            ];
                                            $icon = $contactIcons[$contact->contactType->name] ?? 'fa-circle';
                                            @endphp
                                            <i class="fas {{ $icon }} text-primary me-2"></i>
                                            {{ $contact->contactType->name }}
                                        </td>
                                        <td>{{ $contact->value }}</td>
                                        <td>
                                            @if($contact->is_primary)
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i> Yes
                                            </span>
                                            @else
                                            <span class="badge bg-secondary">
                                                <i class="fas fa-times me-1"></i> No
                                            </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('students.contacts.edit', ['student' => $student, 'contact' => $contact]) }}"
                                                    class="btn btn-outline-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-outline-danger"
                                                    onclick="confirmDelete('{{ route('students.contacts.destroy', ['student' => $student, 'contact' => $contact]) }}')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-4">
                            <i class="fas fa-address-book fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No contacts found for this student.</p>
                            <a href="{{ route('students.contacts.create', $student) }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i> Add First Contact
                            </a>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Guardian Information Card -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                            <i class="fas fa-users text-primary me-2"></i> Guardian Information
                        </h6>
                        <a href="{{ route('students.guardians.create', $student) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i> Add Guardian
                        </a>
                    </div>
                    <div class="card-body">
                        @if($student->guardians->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th class="text-muted">
                                            <i class="fas fa-user text-primary me-2"></i> Name
                                        </th>
                                        <th class="text-muted">
                                            <i class="fas fa-heart text-primary me-2"></i> Relationship
                                        </th>
                                        <th class="text-muted">
                                            <i class="fas fa-phone text-primary me-2"></i> Contact Details
                                        </th>
                                        <th class="text-muted" width="15%">
                                            <i class="fas fa-cog text-primary me-2"></i> Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($student->guardians as $guardian)
                                    <tr>
                                        <td>{{ $guardian->first_name }} {{ $guardian->last_name }}</td>
                                        <td>{{ $guardian->relationship ?? 'Not Specified' }}</td>
                                        <td>{{ $guardian->contact_details ?? 'Not Provided' }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('students.guardians.edit', ['student' => $student, 'guardian' => $guardian]) }}"
                                                    class="btn btn-outline-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-outline-danger"
                                                    onclick="confirmDelete('{{ route('students.guardians.destroy', ['student' => $student, 'guardian' => $guardian]) }}')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-4">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No guardians found for this student.</p>
                            <a href="{{ route('students.guardians.create', $student) }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i> Add First Guardian
                            </a>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Address Information Card -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                            <i class="fas fa-map-marked-alt text-primary me-2"></i> Address Information
                        </h6>
                        <a href="{{ route('students.addresses.create', $student) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i> Add Address
                        </a>
                    </div>
                    <div class="card-body">
                        @if($student->addresses->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th class="text-muted">
                                            <i class="fas fa-tag text-primary me-2"></i> Type
                                        </th>
                                        <th class="text-muted">
                                            <i class="fas fa-map-marker-alt text-primary me-2"></i> Address
                                        </th>
                                        <th class="text-muted" width="15%">
                                            <i class="fas fa-star text-primary me-2"></i> Primary
                                        </th>
                                        <th class="text-muted" width="15%">
                                            <i class="fas fa-cog text-primary me-2"></i> Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($student->addresses as $address)
                                    <tr>
                                        <td>{{ $address->addressType->name }}</td>
                                        <td>
                                            {{ $address->address_line1 }}
                                            @if($address->address_line2)
                                            <br>{{ $address->address_line2 }}
                                            @endif
                                            <br>
                                            {{ collect([$address->city, $address->state, $address->zip_code])->filter()->join(', ') }}
                                            @if($address->country)
                                            <br>{{ $address->country->name }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($address->is_primary == 1)
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i> Yes
                                            </span>
                                            @else
                                            <span class="badge bg-secondary">
                                                <i class="fas fa-times me-1"></i> No
                                            </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('students.addresses.edit', ['student' => $student, 'address' => $address]) }}"
                                                    class="btn btn-outline-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-outline-danger"
                                                    onclick="confirmDelete('{{ route('students.addresses.destroy', ['student' => $student, 'address' => $address]) }}')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-4">
                            <i class="fas fa-map-marked-alt fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No addresses found for this student.</p>
                            <a href="{{ route('students.addresses.create', $student) }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i> Add First Address
                            </a>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Languages (now full width at the bottom) -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Languages</h6>
                        <a href="{{ route('students.languages.create', $student) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i> Add Language
                        </a>
                    </div>
                    <div class="card-body">
                        @if($student->languages->count() > 0)
                        <div class="table-responsive">
                            <table class="table custom-table">
                                <thead>
                                    <tr>
                                        <th>Language</th>
                                        <th>Proficiency</th>
                                        <th>Skills</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($student->languages as $language)
                                    <tr>
                                        <td>
                                            {{ $language->name }}
                                            @if($language->is_native)
                                            <span class="badge bg-success ms-1">Native</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">
                                                {{ ucfirst($language->proficiency_level) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                @if($language->speaking)
                                                <span class="badge bg-info" title="Speaking">
                                                    S: {{ ucfirst($language->speaking) }}
                                                </span>
                                                @endif
                                                @if($language->writing)
                                                <span class="badge bg-info" title="Writing">
                                                    W: {{ ucfirst($language->writing) }}
                                                </span>
                                                @endif
                                                @if($language->reading)
                                                <span class="badge bg-info" title="Reading">
                                                    R: {{ ucfirst($language->reading) }}
                                                </span>
                                                @endif
                                                @if($language->listening)
                                                <span class="badge bg-info" title="Listening">
                                                    L: {{ ucfirst($language->listening) }}
                                                </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{ route('students.languages.edit', ['student' => $student->slug, 'language' => $language->id]) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-3">
                            <div class="mb-3">
                                <span class="fas fa-language fa-3x text-300"></span>
                            </div>
                            <h6 class="text-800">No Languages Added</h6>
                            <p class="text-500">Add languages that the student speaks</p>
                            <a href="{{ route('students.languages.create', $student) }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i> Add Language
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Documents Tab -->
            <div class="tab-pane fade" id="documents" role="tabpanel">
                <div class="card">
                    <div class="card-header bg-transparent">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fs-5">
                                <i class="fas fa-id-card text-primary me-2"></i>Identifications
                            </h6>
                            <a href="{{ route('students.identifications.create', $student) }}" class="btn btn-outline-primary">
                                <i class="fas fa-plus me-1"></i>Add Identification
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($student->identifications->count() > 0)
                            <div class="row g-4">
                                @foreach($student->identifications->sortByDesc('issue_date')->chunk(2) as $chunk)
                                    @foreach($chunk as $identification)
                                        <div class="col-md-6">
                                            <div class="card border h-100">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                                        <div class="d-flex align-items-center gap-2">
                                                            @switch(strtolower($identification->type))
                                                                @case('passport')
                                                                    <i class="fas fa-passport text-primary fs-5"></i>
                                                                    @break
                                                                @case('national id')
                                                                    <i class="fas fa-id-card text-info fs-5"></i>
                                                                    @break
                                                                @case('driver license')
                                                                    <i class="fas fa-id-card-alt text-success fs-5"></i>
                                                                    @break
                                                                @case('birth certificate')
                                                                    <i class="fas fa-certificate text-warning fs-5"></i>
                                                                    @break
                                                                @case('student id')
                                                                    <i class="fas fa-user-graduate text-danger fs-5"></i>
                                                                    @break
                                                                @default
                                                                    <i class="fas fa-id-badge text-secondary fs-5"></i>
                                                            @endswitch
                                                            <h6 class="mb-0 text-primary">{{ $identification->type }}</h6>
                                                            <span class="badge rounded-pill bg-{{ 
                                                                $identification->status === 'active' ? 'success' : 
                                                                ($identification->status === 'expired' ? 'danger' : 'warning') 
                                                            }}">
                                                                {{ Str::title($identification->status) }}
                                                            </span>
                                                        </div>
                                                        <div class="dropdown">
                                                            <button class="btn btn-link text-muted p-0" data-bs-toggle="dropdown">
                                                                <i class="fas fa-ellipsis-v"></i>
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a href="{{ route('students.identifications.edit', ['student' => $student, 'identification' => $identification]) }}" 
                                                                   class="dropdown-item">
                                                                    <i class="fas fa-edit text-primary me-2"></i>Edit
                                                                </a>
                                                                <form action="{{ route('students.identifications.destroy', ['student' => $student, 'identification' => $identification]) }}" 
                                                                      method="POST" 
                                                                      onsubmit="return confirm('Are you sure you want to delete this record?');">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="dropdown-item text-danger">
                                                                        <i class="fas fa-trash me-2"></i>Delete
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row g-3">
                                                        <div class="col-12">
                                                            <div class="d-flex align-items-center mb-2">
                                                                <i class="fas fa-hashtag text-info me-2"></i>
                                                                <span class="text-muted">ID Number:</span>
                                                                <span class="ms-2">{{ $identification->number }}</span>
                                                            </div>
                                                            <div class="d-flex align-items-center mb-2">
                                                                <i class="fas fa-calendar-plus text-success me-2"></i>
                                                                <span class="text-muted">Issue Date:</span>
                                                                <span class="ms-2">{{ date('M d, Y', strtotime($identification->issue_date)) }}</span>
                                                            </div>
                                                            <div class="d-flex align-items-center">
                                                                <i class="fas fa-calendar-times text-danger me-2"></i>
                                                                <span class="text-muted">Expiry Date:</span>
                                                                <span class="ms-2">{{ date('M d, Y', strtotime($identification->expiry_date)) }}</span>
                                                            </div>
                                                        </div>

                                                        @if($identification->issuing_authority)
                                                            <div class="col-12">
                                                                <div class="border-top pt-3">
                                                                    <div class="d-flex align-items-center">
                                                                        <i class="fas fa-building text-primary me-2"></i>
                                                                        <span class="text-muted">Issuing Authority:</span>
                                                                        <span class="ms-2">{{ $identification->issuing_authority }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif

                                                        @if($identification->document_path)
                                                            <div class="col-12">
                                                                <div class="border-top pt-3">
                                                                    <a href="{{ Storage::url($identification->document_path) }}" 
                                                                       class="btn btn-sm btn-outline-primary" 
                                                                       target="_blank">
                                                                        <i class="fas fa-file-alt me-1"></i>View Document
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        @endif

                                                        @if($identification->notes)
                                                            <div class="col-12">
                                                                <div class="border-top pt-3">
                                                                    <div class="d-flex">
                                                                        <i class="fas fa-sticky-note text-warning me-2 mt-1"></i>
                                                                        <p class="mb-0 text-muted">{{ $identification->notes }}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5 text-muted">
                                <i class="fas fa-id-card fa-3x mb-3"></i>
                                <p class="mb-0">No identification records found</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Health Records Tab -->
            <div class="tab-pane fade" id="health" role="tabpanel">
                <div class="card">
                    <div class="card-header bg-transparent">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fs-5">
                                <i class="fas fa-heartbeat text-danger me-2"></i>Health Information
                            </h6>
                            @if(!$student->studentHealth)
                            <a href="{{ route('students.health.create', $student) }}" class="btn btn-outline-primary">
                                <i class="fas fa-plus me-1"></i>Add Health Info
                            </a>
                            @else
                            <a href="{{ route('students.health.edit', ['student' => $student, 'health' => $student->studentHealth]) }}" class="btn btn-outline-primary">
                                <i class="fas fa-edit me-1"></i>Edit
                            </a>
                            @endif
                        </div>
                    </div>

                    @if($student->studentHealth)
                    <div class="card-body">
                        <!-- Top Row: Blood Group and Last Checkup -->
                        <div class="row mb-4">
                            @if($student->studentHealth->blood_group)
                            <div class="col-md-6 mb-3">
                                <div class="p-3 border rounded-3 h-100">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-danger bg-opacity-10 p-3">
                                            <i class="fas fa-tint text-danger fa-lg"></i>
                                        </div>
                                        <div class="ms-3">
                                            <h6 class="text-muted mb-1">Blood Group</h6>
                                            <h5 class="mb-0">{{ $student->studentHealth->blood_group }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if($student->studentHealth->last_checkup_date)
                            <div class="col-md-6 mb-3">
                                <div class="p-3 border rounded-3 h-100">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                                            <i class="fas fa-calendar-check text-primary fa-lg"></i>
                                        </div>
                                        <div class="ms-3">
                                            <h6 class="text-muted mb-1">Last Checkup</h6>
                                            <h5 class="mb-0">{{ $student->studentHealth->last_checkup_date->format('M d, Y') }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Emergency Contact Section -->
                        <div class="p-3 border rounded-3 mb-4">
                            <div class="d-flex align-items-start">
                                <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                                    <i class="fas fa-user-shield text-warning fa-lg"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="text-muted mb-2">Emergency Contact</h6>
                                    <h5 class="mb-2">{{ $student->studentHealth->emergency_contact_name }}</h5>
                                    <div class="d-flex flex-wrap gap-3 text-muted">
                                        <div>
                                            <i class="fas fa-phone me-2"></i>
                                            {{ $student->studentHealth->emergency_contact_phone }}
                                        </div>
                                        <div>
                                            <i class="fas fa-user-friends me-2"></i>
                                            {{ $student->studentHealth->emergency_contact_relationship }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Health Details Section -->
                        <div class="row">
                            <!-- Allergies -->
                            @if($student->studentHealth->allergies)
                            <div class="col-md-4 mb-3">
                                <div class="p-3 border rounded-3 h-100">
                                    <div class="d-flex align-items-start">
                                        <div class="rounded-circle bg-info bg-opacity-10 p-3">
                                            <i class="fas fa-allergies text-info fa-lg"></i>
                                        </div>
                                        <div class="ms-3">
                                            <h6 class="text-muted mb-2">Allergies</h6>
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach(json_decode($student->studentHealth->allergies) as $allergy)
                                                <span class="badge bg-info bg-opacity-10 text-info px-3 py-2">{{ $allergy }}</span>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Medical Conditions -->
                            @if($student->studentHealth->medical_conditions)
                            <div class="col-md-4 mb-3">
                                <div class="p-3 border rounded-3 h-100">
                                    <div class="d-flex align-items-start">
                                        <div class="rounded-circle bg-danger bg-opacity-10 p-3">
                                            <i class="fas fa-notes-medical text-danger fa-lg"></i>
                                        </div>
                                        <div class="ms-3">
                                            <h6 class="text-muted mb-2">Medical Conditions</h6>
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach(json_decode($student->studentHealth->medical_conditions) as $condition)
                                                <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2">{{ $condition }}</span>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Medications -->
                            @if($student->studentHealth->medications)
                            <div class="col-md-4 mb-3">
                                <div class="p-3 border rounded-3 h-100">
                                    <div class="d-flex align-items-start">
                                        <div class="rounded-circle bg-success bg-opacity-10 p-3">
                                            <i class="fas fa-pills text-success fa-lg"></i>
                                        </div>
                                        <div class="ms-3">
                                            <h6 class="text-muted mb-2">Medications</h6>
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach(json_decode($student->studentHealth->medications) as $medication)
                                                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2">{{ $medication }}</span>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Notes Section -->
                        @if($student->studentHealth->notes)
                        <div class="p-3 border rounded-3 mt-1">
                            <div class="d-flex align-items-start">
                                <div class="rounded-circle bg-secondary bg-opacity-10 p-3">
                                    <i class="fas fa-sticky-note text-secondary fa-lg"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="text-muted mb-2">Notes</h6>
                                    <p class="mb-0">{{ $student->studentHealth->notes }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    @else
                    <div class="card-body text-center py-5 text-muted">
                        <i class="fas fa-heartbeat fa-3x mb-3"></i>
                        <p class="mb-0">No health information added yet</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Academic History Tab -->
            <div class="tab-pane fade" id="academic" role="tabpanel">
                <div class="card">
                    <div class="card-header bg-transparent">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fs-5">
                                <i class="fas fa-graduation-cap text-primary me-2"></i>Academic History
                            </h6>
                            <a href="{{ route('students.academic-histories.create', $student->slug) }}" class="btn btn-outline-primary">
                                <i class="fas fa-plus me-1"></i>Add Academic History
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($student->academicHistories->count() > 0)
                            <div class="row g-4">
                                @foreach($student->academicHistories->sortByDesc('start_date')->chunk(2) as $chunk)
                                    @foreach($chunk as $history)
                                        <div class="col-md-6">
                                            <div class="card border h-100">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                                        <div class="d-flex align-items-center gap-2">
                                                            @switch($history->status)
                                                                @case('completed')
                                                                    <span class="badge rounded-pill bg-success"><i class="fas fa-check"></i></span>
                                                                    @break
                                                                @case('in_progress')
                                                                    <span class="badge rounded-pill bg-primary"><i class="fas fa-clock"></i></span>
                                                                    @break
                                                                @case('incomplete')
                                                                    <span class="badge rounded-pill bg-danger"><i class="fas fa-times"></i></span>
                                                                    @break
                                                                @case('verified')
                                                                    <span class="badge rounded-pill bg-info"><i class="fas fa-shield-alt"></i></span>
                                                                    @break
                                                            @endswitch
                                                            <h6 class="mb-0 text-primary">{{ $history->institution_name }}</h6>
                                                        </div>
                                                        <div class="dropdown">
                                                            <button class="btn btn-link text-muted p-0" data-bs-toggle="dropdown">
                                                                <i class="fas fa-ellipsis-v"></i>
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a href="{{ route('students.academic-histories.edit', [$student->slug, $history->slug]) }}" 
                                                                   class="dropdown-item">
                                                                    <i class="fas fa-edit text-primary me-2"></i>Edit
                                                                </a>
                                                                <form action="{{ route('students.academic-histories.destroy', [$student->slug, $history->slug]) }}" 
                                                                      method="POST" 
                                                                      onsubmit="return confirm('Are you sure you want to delete this record?');">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="dropdown-item text-danger">
                                                                        <i class="fas fa-trash me-2"></i>Delete
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row g-3">
                                                        <div class="col-12">
                                                            <div class="d-flex align-items-center mb-2">
                                                                <i class="fas fa-book text-info me-2"></i>
                                                                <span class="text-muted">Program:</span>
                                                                <span class="ms-2">{{ $history->program_name }}</span>
                                                            </div>
                                                            <div class="d-flex align-items-center mb-2">
                                                                <i class="fas fa-award text-warning me-2"></i>
                                                                <span class="text-muted">Qualification Level:</span>
                                                                <span class="ms-2">{{ $history->qualificationLevel->name }}</span>
                                                            </div>
                                                            <div class="d-flex align-items-center">
                                                                <i class="fas fa-calendar-alt text-primary me-2"></i>
                                                                <span class="text-muted">Duration:</span>
                                                                <span class="ms-2">
                                                                    {{ date('M d, Y', strtotime($history->start_date)) }} - 
                                                                    {{ date('M d, Y', strtotime($history->completion_date)) }}
                                                                </span>
                                                            </div>
                                                        </div>

                                                        @if($history->grade_achieved || $history->certificate_number)
                                                            <div class="col-12">
                                                                <div class="border-top pt-3">
                                                                    @if($history->grade_achieved)
                                                                        <div class="d-flex align-items-center mb-2">
                                                                            <i class="fas fa-star text-warning me-2"></i>
                                                                            <span class="text-muted">Grade Achieved:</span>
                                                                            <span class="ms-2">{{ $history->grade_achieved }}</span>
                                                                        </div>
                                                                    @endif
                                                                    @if($history->certificate_number)
                                                                        <div class="d-flex align-items-center">
                                                                            <i class="fas fa-certificate text-info me-2"></i>
                                                                            <span class="text-muted">Certificate Number:</span>
                                                                            <span class="ms-2">{{ $history->certificate_number }}</span>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endif

                                                        @if($history->certificate_path || $history->transcript_path)
                                                            <div class="col-12">
                                                                <div class="border-top pt-3">
                                                                    <div class="d-flex gap-2">
                                                                        @if($history->certificate_path)
                                                                            <a href="{{ Storage::url($history->certificate_path) }}" 
                                                                               class="btn btn-sm btn-outline-primary" 
                                                                               target="_blank">
                                                                                <i class="fas fa-certificate me-1"></i>View Certificate
                                                                            </a>
                                                                        @endif
                                                                        @if($history->transcript_path)
                                                                            <a href="{{ Storage::url($history->transcript_path) }}" 
                                                                               class="btn btn-sm btn-outline-info" 
                                                                               target="_blank">
                                                                                <i class="fas fa-file-alt me-1"></i>View Transcript
                                                                            </a>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif

                                                        @if($history->notes)
                                                            <div class="col-12">
                                                                <div class="border-top pt-3">
                                                                    <div class="d-flex">
                                                                        <i class="fas fa-sticky-note text-warning me-2 mt-1"></i>
                                                                        <p class="mb-0 text-muted">{{ $history->notes }}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5 text-muted">
                                <i class="fas fa-graduation-cap fa-3x mb-3"></i>
                                <p class="mb-0">No academic history records found</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Disciplinary Tab -->
            <div class="tab-pane fade" id="disciplinary" role="tabpanel">
                <div class="card">
                    <div class="card-header bg-transparent">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fs-5">
                                <i class="fas fa-gavel text-primary me-2"></i>Disciplinary Records
                            </h6>
                            <a href="{{ route('students.disciplinaries.create', $student->slug) }}" class="btn btn-outline-primary">
                                <i class="fas fa-plus me-1"></i>Add Record
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($student->disciplinaries->count() > 0)
                            <div class="row g-4">
                                @foreach($student->disciplinaries->sortByDesc('incident_date')->chunk(2) as $chunk)
                                    @foreach($chunk as $disciplinary)
                                        <div class="col-md-6">
                                            <div class="card border h-100">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                                        <div class="d-flex align-items-center gap-2">
                                                            @switch($disciplinary->status)
                                                                @case('pending')
                                                                    <span class="badge rounded-pill bg-warning"><i class="fas fa-clock"></i></span>
                                                                    @break
                                                                @case('active')
                                                                    <span class="badge rounded-pill bg-danger"><i class="fas fa-exclamation"></i></span>
                                                                    @break
                                                                @case('resolved')
                                                                    <span class="badge rounded-pill bg-success"><i class="fas fa-check"></i></span>
                                                                    @break
                                                                @case('appealed')
                                                                    <span class="badge rounded-pill bg-info"><i class="fas fa-balance-scale"></i></span>
                                                                    @break
                                                            @endswitch
                                                            <h6 class="mb-0 text-primary">{{ \App\Models\StudentDisciplinary::INCIDENT_TYPES[$disciplinary->incident_type] }}</h6>
                                                        </div>
                                                        <div class="dropdown">
                                                            <button class="btn btn-link text-muted p-0" data-bs-toggle="dropdown">
                                                                <i class="fas fa-ellipsis-v"></i>
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a href="{{ route('students.disciplinaries.edit', [$student->slug, $disciplinary->id]) }}" 
                                                                   class="dropdown-item">
                                                                    <i class="fas fa-edit text-primary me-2"></i>Edit
                                                                </a>
                                                                <form action="{{ route('students.disciplinaries.destroy', ['student' => $student, 'disciplinary' => $disciplinary]) }}" 
                                                                      method="POST" 
                                                                      onsubmit="return confirm('Are you sure you want to delete this record?');">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="dropdown-item text-danger">
                                                                        <i class="fas fa-trash me-2"></i>Delete
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <div class="d-flex align-items-center mb-2">
                                                                <i class="fas fa-calendar text-muted me-2"></i>
                                                                <span class="text-muted">Incident Date:</span>
                                                                <span class="ms-2">{{ date('M d, Y', strtotime($disciplinary->incident_date)) }}</span>
                                                            </div>
                                                            <div class="d-flex align-items-center mb-2">
                                                                <i class="fas fa-map-marker-alt text-muted me-2"></i>
                                                                <span class="text-muted">Location:</span>
                                                                <span class="ms-2">{{ $disciplinary->location ?: 'Not specified' }}</span>
                                                            </div>
                                                            <div class="d-flex align-items-center">
                                                                <i class="fas fa-user text-muted me-2"></i>
                                                                <span class="text-muted">Reported By:</span>
                                                                <span class="ms-2">{{ $disciplinary->reported_by }}</span>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-6">
                                                            @if($disciplinary->sanction)
                                                                <div class="d-flex align-items-center mb-2">
                                                                    <i class="fas fa-ban text-danger me-2"></i>
                                                                    <span class="text-muted">Sanction:</span>
                                                                    <span class="ms-2">{{ \App\Models\StudentDisciplinary::SANCTIONS[$disciplinary->sanction] }}</span>
                                                                </div>
                                                            @endif
                                                            @if($disciplinary->start_date && $disciplinary->end_date)
                                                                <div class="d-flex align-items-center mb-2">
                                                                    <i class="fas fa-calendar-alt text-muted me-2"></i>
                                                                    <span class="text-muted">Duration:</span>
                                                                    <span class="ms-2">
                                                                        {{ date('M d, Y', strtotime($disciplinary->start_date)) }} - 
                                                                        {{ date('M d, Y', strtotime($disciplinary->end_date)) }}
                                                                    </span>
                                                                </div>
                                                            @endif
                                                            <div class="d-flex align-items-center">
                                                                <i class="fas fa-info-circle text-muted me-2"></i>
                                                                <span class="text-muted">Status:</span>
                                                                <span class="ms-2 badge bg-{{ 
                                                                    $disciplinary->status === 'pending' ? 'warning' : 
                                                                    ($disciplinary->status === 'active' ? 'danger' : 
                                                                    ($disciplinary->status === 'resolved' ? 'success' : 'info')) 
                                                                }} px-2">
                                                                    {{ Str::title($disciplinary->status) }}
                                                                </span>
                                                            </div>
                                                        </div>

                                                        <div class="col-12">
                                                            <div class="border-top pt-3">
                                                                <h6 class="text-muted mb-2">Description</h6>
                                                                <p class="mb-0">{{ $disciplinary->description }}</p>
                                                            </div>
                                                        </div>

                                                        <div class="col-12">
                                                            <div class="border-top pt-3">
                                                                <h6 class="text-muted mb-2">Action Taken</h6>
                                                                <p class="mb-0">{{ $disciplinary->action_taken }}</p>
                                                            </div>
                                                        </div>

                                                        @if($disciplinary->witnesses)
                                                            <div class="col-12">
                                                                <div class="border-top pt-3">
                                                                    <h6 class="text-muted mb-2">Witnesses</h6>
                                                                    <div class="d-flex flex-wrap gap-2">
                                                                        @php
                                                                            $witnesses = is_string($disciplinary->witnesses) ? 
                                                                                json_decode($disciplinary->witnesses, true) : 
                                                                                $disciplinary->witnesses;
                                                                        @endphp
                                                                        @foreach($witnesses as $witness)
                                                                            <span class="badge bg-light text-dark border px-2 py-1">
                                                                                <i class="fas fa-user me-1"></i>{{ $witness }}
                                                                            </span>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif

                                                        @if($disciplinary->notes)
                                                            <div class="col-12">
                                                                <div class="border-top pt-3">
                                                                    <div class="d-flex">
                                                                        <i class="fas fa-sticky-note text-warning me-2 mt-1"></i>
                                                                        <p class="mb-0 text-muted">{{ $disciplinary->notes }}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5 text-muted">
                                <i class="fas fa-gavel fa-3x mb-3"></i>
                                <p class="mb-0">No disciplinary records found</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Courses Tab -->
            <div class="tab-pane fade" id="courses" role="tabpanel">
                <div class="d-flex justify-content-end mb-3">
                    <a href="{{ route('students.courses.create', $student) }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Enroll in New Course
                    </a>
                </div>
                @if($student->studentCourses->count() > 0)
                <div class="table-responsive">
                    <table class="table custom-table">
                        <thead>
                            <tr>
                                <th>Course</th>
                                <th>Start Date</th>
                                <th>Status</th>
                                <th>Progress</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($student->studentCourses as $course)
                            <tr>
                                <td>{{ $course->course->name }}</td>
                                <td>{{ date('d/m/Y', strtotime($course->start_date)) }}</td>
                                <td>
                                    <span class="badge bg-{{ $course->status === 'active' ? 'success' : 'warning' }}">
                                        {{ ucfirst($course->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="progress" style="height: 5px;">
                                        <div class="progress-bar" role="progressbar" style="width: 75%"></div>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('student-courses.show', $course) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <span class="fas fa-graduation-cap fa-3x text-300"></span>
                    </div>
                    <h6 class="text-800">No Courses Enrolled</h6>
                    <p class="text-500">This student hasn't enrolled in any courses yet.</p>
                    <a href="{{ route('students.courses.create', $student) }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Enroll in Course
                    </a>
                </div>
                @endif
            </div>

            <!-- Attendance Tab -->
            <div class="tab-pane fade" id="attendance" role="tabpanel">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Attendance Records</h6>
                        <a href="{{ route('students.attendance.create', $student) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i> Add Attendance
                        </a>
                    </div>
                    <div class="card-body">
                        @if($student->attendance->count() > 0)
                        <div class="table-responsive">
                            <table class="table custom-table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Course</th>
                                        <th>Notes</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($student->attendance as $record)
                                    <tr>
                                        <td>{{ date('d/m/Y', strtotime($record->date)) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $record->status === 'present' ? 'success' : 'danger' }}">
                                                {{ ucfirst($record->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $record->course->name ?? 'N/A' }}</td>
                                        <td>{{ $record->notes ?? '-' }}</td>
                                        <td>
                                            <a href="{{ route('admin.attendance.edit', $record) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-3">
                            <div class="mb-3">
                                <span class="fas fa-calendar-check fa-3x text-300"></span>
                            </div>
                            <h6 class="text-800">No Attendance Records</h6>
                            <p class="text-500">No attendance records have been added yet.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Payments Tab -->
            <div class="tab-pane fade" id="payments" role="tabpanel">
                <div class="d-flex justify-content-end mb-3">
                    <a href="{{ route('students.payments.create', $student) }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Add Payment
                    </a>
                </div>
                @if($student->studentPayments->count() > 0)
                <div class="table-responsive">
                    <table class="table custom-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Reference</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($student->studentPayments as $payment)
                            <tr>
                                <td>{{ date('d/m/Y', strtotime($payment->payment_date)) }}</td>
                                <td>{{ number_format($payment->amount, 2) }}</td>
                                <td>{{ $payment->payment_method }}</td>
                                <td>{{ $payment->reference_number ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-{{ $payment->status === 'completed' ? 'success' : 'warning' }}">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('student-payments.show', $payment) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <span class="fas fa-dollar-sign fa-3x text-300"></span>
                    </div>
                    <h6 class="text-800">No Payments</h6>
                    <p class="text-500">No payments have been recorded yet.</p>
                    <a href="{{ route('students.payments.create', $student) }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Add Payment
                    </a>
                </div>
                @endif
            </div>


        </div>
    </div>
</div>
@endsection

@push('modals')
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this contact?
            </div>
            <div class="modal-footer">
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize any plugins or interactions
    });

    function confirmDelete(deleteUrl) {
        document.getElementById('deleteForm').action = deleteUrl;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }
</script>
@endpush