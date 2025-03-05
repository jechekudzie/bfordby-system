@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <!-- Back button -->
        <div class="mb-4">
            <a href="{{ route('admin.modules.assessments.allocations.index', [$allocation->assessment->module->slug, $allocation->assessment->slug]) }}" 
               class="btn btn-link text-decoration-none ps-0">
                <i class="fas fa-arrow-left me-2"></i>Back to Allocations
            </a>
        </div>

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-2">
                        <i class="fas fa-users text-primary"></i>
                    </div>
                    <h1 class="h4 mb-0">Groups for {{ $allocation->assessment->name }}</h1>
                </div>
                <p class="text-muted small mb-0 mt-2">
                    {{ $allocation->semester->name }} | {{ $allocation->enrollmentCode->code }}
                </p>
            </div>
            <div>
                <a href="{{ route('admin.assessment-allocation-groups.create', $allocation) }}" 
                   class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Create New Group
                </a>
                <a href="{{ route('admin.modules.assessments.allocations.index', [$allocation->assessment->module->slug, $allocation->assessment->slug]) }}" 
                   class="btn btn-outline-primary ms-2">
                    <i class="fas fa-arrow-left me-2"></i>Back to Allocation
                </a>
            </div>
        </div>

        <!-- Content -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                @if($groups->isEmpty())
                    <div class="text-center py-5">
                        <div class="rounded-circle bg-light mx-auto mb-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-users text-primary fa-2x"></i>
                        </div>
                        <p class="text-muted mb-0">No groups have been created yet</p>
                        <small class="text-muted d-block mt-1">Click the "Create New Group" button to get started</small>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Group Name</th>
                                    <th>Members</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($groups as $group)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3">
                                                    <i class="fas fa-users text-primary"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $group->name }}</h6>
                                                    <small class="text-muted">{{ $group->students->count() }} members</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach($group->students as $student)
                                                    <div class="d-flex align-items-center bg-light rounded-pill px-3 py-1">
                                                        <i class="fas fa-user-graduate text-primary me-2"></i>
                                                        <span class="small">{{ $student->first_name }} {{ $student->last_name }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="btn-group">
                                                <a href="{{ route('admin.assessment-allocation-groups.show', [$allocation, $group]) }}" 
                                                   class="btn btn-light btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.assessment-allocation-groups.edit', [$allocation, $group]) }}" 
                                                   class="btn btn-light btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.assessment-allocation-groups.destroy', [$allocation, $group]) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('Are you sure you want to delete this group?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-light btn-sm">
                                                        <i class="fas fa-trash text-danger"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($groups->hasPages())
                        <div class="d-flex justify-content-center border-top p-3">
                            {{ $groups->links() }}
                        </div>
                    @endif
                @endif
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
    .gap-2 {
        gap: 0.5rem !important;
    }
</style>
@endpush 