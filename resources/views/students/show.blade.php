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

    .table-borderless td, .table-borderless th {
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
                <a class="nav-link" data-bs-toggle="tab" href="#documents" role="tab">Documents</a>
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
                        <h6 class="mb-0">
                            <i class="fas fa-user text-primary me-2"></i>
                            Personal Information
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
                <div class="d-flex justify-content-end mb-3">
                    <a href="{{ route('students.identifications.create', $student) }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Add Document
                    </a>
                </div>
                @if($student->identifications->count() > 0)
                <div class="table-responsive">
                    <table class="table custom-table">
                        <thead>
                            <tr>
                                <th>Document Type</th>
                                <th>Number</th>
                                <th>Expiry Date</th>
                                <th>Document</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($student->identifications as $identification)
                            <tr>
                                <td>{{ $identification->identificationType->name }}</td>
                                <td>{{ $identification->number }}</td>
                                <td>{{ date('d/m/Y', strtotime($identification->expiry_date)) }}</td>
                                <td>
                                    @if($identification->document_path)
                                    <a href="{{ Storage::url($identification->document_path) }}" target="_blank" class="btn btn-sm btn-warning">
                                        <i class="fas fa-file-alt"></i>
                                    </a>
                                    @else
                                    <span class="badge bg-secondary">No Document</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.identifications.edit', $identification) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
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
                        <span class="fas fa-file-alt fa-3x text-300"></span>
                    </div>
                    <h6 class="text-800">No Documents</h6>
                    <p class="text-500">No identification documents have been added yet.</p>
                    <a href="{{ route('students.identifications.create', $student) }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Add Document
                    </a>
                </div>
                @endif
            </div>

            <!-- Health Records Tab -->
            <div class="tab-pane fade" id="health" role="tabpanel">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Health Record</h6>
                        <a href="{{ route('students.health.create', $student) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i> Add Health Record
                        </a>
                    </div>
                    <div class="card-body">
                        @if($student->studentHealth->count() > 0)
                            <div class="table-responsive">
                                <table class="table custom-table">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Condition</th>
                                            <th>Description</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($student->studentHealth as $health)
                                        <tr>
                                            <td>{{ date('d/m/Y', strtotime($health->date)) }}</td>
                                            <td>{{ $health->condition }}</td>
                                            <td>{{ $health->description }}</td>
                                            <td>
                                                <span class="badge bg-{{ $health->status === 'active' ? 'warning' : 'success' }}">
                                                    {{ ucfirst($health->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('student-health.edit', $health) }}" class="btn btn-sm btn-primary">
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
                                    <span class="fas fa-heartbeat fa-3x text-300"></span>
                                </div>
                                <h6 class="text-800">No Health Records</h6>
                                <p class="text-500">No health records have been added yet.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Academic History Tab -->
            <div class="tab-pane fade" id="academic" role="tabpanel">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Academic History</h6>
                        <a href="{{ route('students.academic-histories.create', $student) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i> Add Academic Record
                        </a>
                    </div>
                    <div class="card-body">
                        @if($student->academicHistories->count() > 0)
                        <div class="table-responsive">
                            <table class="table custom-table">
                                <thead>
                                    <tr>
                                        <th>Institution</th>
                                        <th>Qualification</th>
                                        <th>Completion Date</th>
                                        <th>Document</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($student->academicHistories as $history)
                                    <tr>
                                        <td>{{ $history->institution }}</td>
                                        <td>{{ $history->qualificationLevel->name }}</td>
                                        <td>{{ date('d/m/Y', strtotime($history->completion_date)) }}</td>
                                        <td>
                                            @if($history->document_path)
                                            <a href="{{ Storage::url($history->document_path) }}" target="_blank" class="btn btn-sm btn-warning">
                                                <i class="fas fa-file-alt"></i>
                                            </a>
                                            @else
                                            <span class="badge bg-secondary">No Document</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.academic-histories.edit', $history) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
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
                                <span class="fas fa-book fa-3x text-300"></span>
                            </div>
                            <h6 class="text-800">No Academic History</h6>
                            <p class="text-500">No academic history has been added yet.</p>
                            <a href="{{ route('students.academic-histories.create', $student) }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i> Add Academic History
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Disciplinary Tab -->
            <div class="tab-pane fade" id="disciplinary" role="tabpanel">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Disciplinary Record</h6>
                        <a href="{{ route('students.disciplinary.create', $student) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i> Add Disciplinary Record
                        </a>
                    </div>
                    <div class="card-body">
                        @if($student->studentDisciplinary->count() > 0)
                            <div class="table-responsive">
                                <table class="table custom-table">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Incident</th>
                                            <th>Action Taken</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($student->studentDisciplinary as $record)
                                        <tr>
                                            <td>{{ date('d/m/Y', strtotime($record->date)) }}</td>
                                            <td>{{ $record->incident }}</td>
                                            <td>{{ $record->action_taken }}</td>
                                            <td>
                                                <span class="badge bg-{{ $record->status === 'resolved' ? 'success' : 'warning' }}">
                                                    {{ ucfirst($record->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.disciplinary.edit', $record) }}" class="btn btn-sm btn-primary">
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
                                    <span class="fas fa-gavel fa-3x text-300"></span>
                                </div>
                                <h6 class="text-800">No Disciplinary Records</h6>
                                <p class="text-500">No disciplinary records have been added yet.</p>
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
