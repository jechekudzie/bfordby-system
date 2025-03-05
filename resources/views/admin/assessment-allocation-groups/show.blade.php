@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <!-- Back button -->
        <div class="mb-4">
            <a href="{{ route('admin.assessment-allocation-groups.index', $allocation) }}" 
               class="btn btn-link text-decoration-none ps-0">
                <i class="fas fa-arrow-left me-2"></i>Back to Groups
            </a>
        </div>

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-2">
                        <i class="fas fa-users text-primary"></i>
                    </div>
                    <h1 class="h4 mb-0">{{ $group->name }}</h1>
                </div>
                <p class="text-muted small mb-0 mt-2">
                    {{ $allocation->assessment->name }} | {{ $allocation->semester->name }}
                </p>
            </div>
            <div>
                <a href="{{ route('admin.assessment-allocation-groups.edit', [$allocation, $group]) }}" 
                   class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i>Edit Group
                </a>
            </div>
        </div>

        <!-- Content -->
        <div class="row g-4">
            <!-- Assessment Details Card -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="card-subtitle mb-3 text-primary">Assessment Details</h6>
                        
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3">
                                <i class="fas fa-book text-primary"></i>
                            </div>
                            <div>
                                <div class="text-muted small">Assessment</div>
                                <div class="fw-medium">{{ $allocation->assessment->name }}</div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3">
                                <i class="fas fa-hashtag text-primary"></i>
                            </div>
                            <div>
                                <div class="text-muted small">Enrollment Code</div>
                                <div class="fw-medium">{{ $allocation->enrollmentCode->code }}</div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3">
                                <i class="fas fa-calendar text-primary"></i>
                            </div>
                            <div>
                                <div class="text-muted small">Semester</div>
                                <div class="fw-medium">{{ $allocation->semester->name }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Group Members Card -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="card-subtitle mb-3 text-primary">Group Members</h6>
                        
                        @if($group->students->isEmpty())
                            <div class="text-center py-4">
                                <div class="rounded-circle bg-light mx-auto mb-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-user-friends text-primary fa-2x"></i>
                                </div>
                                <p class="text-muted mb-0">No members in this group</p>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="ps-4">Student</th>
                                            <th>Student Number</th>
                                            <th class="text-end pe-4">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($group->students as $student)
                                            <tr>
                                                <td class="ps-4">
                                                    <div class="d-flex align-items-center">
                                                        <div class="rounded-circle bg-light p-2 me-3">
                                                            <i class="fas fa-user-graduate text-primary"></i>
                                                        </div>
                                                        <div>
                                                            <div class="fw-medium">{{ $student->first_name }} {{ $student->last_name }}</div>
                                                            <small class="text-muted">{{ optional($student->enrollments->first())->student_number }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ optional($student->enrollments->first())->student_number }}</td>
                                                <td class="text-end pe-4">
                                                    <form action="{{ route('admin.assessment-allocation-groups.members.remove', [$allocation, $group, $student]) }}" 
                                                          method="POST" 
                                                          class="d-inline"
                                                          onsubmit="return confirm('Are you sure you want to remove this member?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-light btn-sm">
                                                            <i class="fas fa-user-minus text-danger"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Delete Group Card -->
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-body">
                        <h6 class="card-subtitle mb-3 text-danger">Danger Zone</h6>
                        <p class="text-muted small mb-3">Once you delete a group, there is no going back. Please be certain.</p>
                        
                        <form action="{{ route('admin.assessment-allocation-groups.destroy', [$allocation, $group]) }}" 
                              method="POST" 
                              class="d-inline"
                              onsubmit="return confirm('Are you sure you want to delete this group? This action cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash me-2"></i>Delete Group
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .btn-light {
        background-color: #f8f9fa;
        border-color: #f8f9fa;
    }
    .btn-light:hover {
        background-color: #e9ecef;
        border-color: #e9ecef;
    }
    .table > :not(caption) > * > * {
        padding: 1rem 0.5rem;
    }
</style>
@endpush 