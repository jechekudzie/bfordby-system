@extends('layouts.student')

@section('title', $module->name . ' - Learning Materials')

@section('content')
<style>
    @media (max-width: 576px) {
        .content-card-buttons {
            flex-direction: column;
            gap: 0.5rem;
        }
        .content-card-buttons .btn {
            width: 100%;
        }
    }
</style>

<div class="content pt-5">
    <div class="mb-9">
        <div class="row g-3 mb-4 align-items-center">
            <div class="col">
                <h2 class="mb-0">{{ $module->name }}</h2>
            </div>
            <div class="col-auto">
                <a href="{{ route('student.library.index') }}" class="btn btn-falcon-default btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Back to Library
                </a>
            </div>
        </div>
        
        <div class="row g-3">
            <!-- Module Info Card -->
            <div class="col-12">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                @if($module->description)
                                    <p>{{ $module->description }}</p>
                                @endif
                                <div class="d-flex flex-wrap">
                                    <div class="me-4 mb-2">
                                        <span class="fw-bold">Subject:</span>
                                        <span>{{ $module->subject->name }}</span>
                                    </div>
                                    <div class="me-4 mb-2">
                                        <span class="fw-bold">Course:</span>
                                        <span>{{ $module->subject->course->name }}</span>
                                    </div>
                                    <div class="mb-2">
                                        <span class="fw-bold">Total Materials:</span>
                                        <span>{{ $module->contents->count() }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="alert alert-info mb-0">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <div>
                                            <p class="mb-0 fs--1">Materials marked as <span class="badge bg-warning text-dark">Required</span> are essential for your coursework.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Content Type Sections -->
            @foreach($contentTypes as $type => $typeData)
                @if(isset($contentByType[$type]) && $contentByType[$type]->count() > 0)
                    <div class="col-12">
                        <div class="card mb-3">
                            <div class="card-header">
                                <div class="d-flex align-items-center">
                                    <i class="{{ $typeData['icon'] }} {{ $typeData['class'] }} me-2"></i>
                                    <h5 class="mb-0">{{ $typeData['name'] }}</h5>
                                    <span class="badge rounded-pill bg-primary ms-2">{{ $contentByType[$type]->count() }}</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    @foreach($contentByType[$type] as $content)
                                        <div class="col-md-6 col-xl-4">
                                            <div class="card h-100 shadow-sm {{ $content->is_required ? 'border border-warning' : '' }}">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <h5 class="mb-0">{{ $content->title }}</h5>
                                                        @if($content->is_required)
                                                            <span class="badge bg-warning text-dark">Required</span>
                                                        @endif
                                                    </div>
                                                    
                                                    @if($content->description)
                                                        <p class="mb-3 fs--1">{{ $content->description }}</p>
                                                    @endif
                                                    
                                                    <div class="d-flex mt-3 content-card-buttons">
                                                        @if($content->isFile() && $content->getFileUrl())
                                                            <a href="{{ $content->getFileUrl() }}" target="_blank" class="btn btn-sm btn-falcon-primary w-100">
                                                                <i class="fas fa-download me-1"></i> Download
                                                            </a>
                                                        @elseif($content->isExternal())
                                                            <a href="{{ $content->external_url }}" target="_blank" class="btn btn-sm btn-falcon-info w-100">
                                                                <i class="fas fa-external-link-alt me-1"></i> Open Link
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="card-footer bg-light py-2">
                                                    <div class="fs--1 text-muted">
                                                        <i class="{{ $typeData['icon'] }} me-1"></i> {{ ucfirst($content->content_type) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
            
            <!-- No Content Message -->
            @if($module->contents->count() == 0)
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="text-center py-5">
                                <i class="fas fa-folder-open fa-3x text-300 mb-3"></i>
                                <h5>No Learning Materials Available</h5>
                                <p class="text-700">This module doesn't have any content yet.</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 