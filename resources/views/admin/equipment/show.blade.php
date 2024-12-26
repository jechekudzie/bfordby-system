@extends('layouts.backend')

@push('styles')
<style>
    .specification-item {
        transition: all 0.3s ease;
    }
    .specification-item:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        transform: translateY(-2px);
    }
    .specification-icon {
        font-size: 1.2rem;
        width: 24px;
    }
    .specification-value {
        background-color: white;
        padding: 0.5rem;
        border-radius: 0.25rem;
        border: 1px solid rgba(0,0,0,0.05);
    }
</style>
@endpush

@section('content')
<div class="card mb-3">
    <div class="card-header">
        <div class="row flex-between-end">
            <div class="col-auto align-self-center">
                <h5 class="mb-0">Equipment Details</h5>
            </div>
            <div class="col-auto ms-auto">
                <div class="btn-group btn-group-sm">
                    <a href="{{ route('equipment.edit', $equipment) }}" class="btn btn-falcon-primary">
                        <span class="fas fa-edit"></span> Edit
                    </a>
                    <a href="{{ route('equipment.index') }}" class="btn btn-falcon-default">
                        <span class="fas fa-list"></span> Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body bg-light">
        <div class="row g-3">
            <!-- Basic Information -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Basic Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Image Section -->
                            <div class="col-md-4 mb-3">
                                @if($equipment->image_path)
                                    <img src="{{ asset($equipment->image_path) }}" 
                                         alt="{{ $equipment->name }}" 
                                         class="img-fluid rounded shadow-sm"
                                         style="max-height: 300px; width: 100%; object-fit: contain;">
                                @else
                                    <div class="border rounded d-flex align-items-center justify-content-center bg-light" 
                                         style="height: 300px;">
                                        <span class="text-muted">No image available</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Equipment Details -->
                            <div class="col-md-8">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <strong>Name:</strong>
                                            <p class="mb-0">{{ $equipment->name }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <strong>Category:</strong>
                                            <p class="mb-0">{{ $equipment->category->name }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <strong>Serial Number:</strong>
                                            <p class="mb-0">{{ $equipment->serial_number ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <strong>Asset Number:</strong>
                                            <p class="mb-0">{{ $equipment->asset_number }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <strong>Status:</strong>
                                            <p class="mb-0">
                                                <span class="badge bg-{{ 
                                                    $equipment->status === 'available' ? 'success' : 
                                                    ($equipment->status === 'in_use' ? 'warning' : 
                                                    ($equipment->status === 'maintenance' ? 'info' : 'secondary')) 
                                                }}">
                                                    {{ ucfirst($equipment->status) }}
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                    @if($equipment->description)
                                        <div class="col-12">
                                            <div class="mb-2">
                                                <strong>Description:</strong>
                                                <p class="mb-0">{{ $equipment->description }}</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Specifications -->
           <!-- Replace the Specifications card section -->
<div class="col-12 mt-4">
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">
                <i class="fas fa-clipboard-list me-2"></i>Specifications
            </h6>
        </div>
        <div class="card-body">
            @if($equipment->specifications->count() > 0)
                <div class="row g-4">
                    @foreach($equipment->specifications as $specification)
                        <div class="col-md-6">
                            <div class="specification-item p-3 border rounded bg-light h-100">
                                <div class="d-flex align-items-start">
                                    <div class="specification-icon me-3">
                                        <span class="fas fa-cog text-primary"></span>
                                    </div>
                                    <div class="specification-content flex-grow-1">
                                        <h6 class="mb-1 text-primary">
                                            {{ $specification->template->name }}
                                        </h6>
                                        
                                        @if($specification->template->description)
                                            <p class="text-muted small mb-2">
                                                {{ $specification->template->description }}
                                            </p>
                                        @endif

                                        <div class="specification-value">
                                            @if(is_array(json_decode($specification->value, true)))
                                                <ul class="list-unstyled mb-0">
                                                    @foreach(json_decode($specification->value) as $value)
                                                        <li class="mb-1">
                                                            <span class="fas fa-check-circle text-success me-1"></span>
                                                            {{ $value }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <div class="d-flex align-items-center">
                                                    @switch($specification->template->field_type)
                                                        @case('number')
                                                            <span class="fas fa-hashtag text-secondary me-2"></span>
                                                            @break
                                                        @case('text')
                                                            <span class="fas fa-font text-secondary me-2"></span>
                                                            @break
                                                        @case('textarea')
                                                            <span class="fas fa-align-left text-secondary me-2"></span>
                                                            @break
                                                        @default
                                                            <span class="fas fa-info-circle text-secondary me-2"></span>
                                                    @endswitch
                                                    <span>{{ $specification->value }}</span>
                                                </div>
                                            @endif
                                        </div>

                                        @if($specification->template->is_required)
                                            <div class="mt-2">
                                                <span class="badge bg-primary">Required</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-4">
                    <span class="fas fa-clipboard fa-2x text-muted mb-3"></span>
                    <p class="text-muted mb-0">No specifications found for this equipment.</p>
                </div>
            @endif
        </div>
    </div>
</div>



            <!-- Additional Information (if needed) -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">System Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <strong>Created At:</strong>
                                    <p class="mb-0">{{ $equipment->created_at->format('M d, Y H:i A') }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <strong>Last Updated:</strong>
                                    <p class="mb-0">{{ $equipment->updated_at->format('M d, Y H:i A') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 