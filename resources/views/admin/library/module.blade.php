@extends('layouts.admin')

@section('title', $module->name . ' - Learning Materials')

@section('content')
<div class="content pt-5">
    <div class="mb-9">
        <div class="row g-3 mb-4 align-items-center">
            <div class="col">
                <h2 class="mb-0">{{ $module->name }} - Learning Materials</h2>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.library.index') }}" class="btn btn-falcon-default btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Back to Library
                </a>
            </div>
        </div>
        
        <div class="row g-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                @if($module->description)
                                    <p>{{ $module->description }}</p>
                                @endif
                                <div class="d-flex flex-wrap">
                                    <div class="me-4 mb-3">
                                        <span class="fw-bold">Discipline:</span>
                                        <span>{{ $module->subject->name }}</span>
                                    </div>
                                    <div class="me-4 mb-3">
                                        <span class="fw-bold">Course:</span>
                                        <span>{{ $module->subject->course->name }}</span>
                                    </div>
                                    <div class="mb-3">
                                        <span class="fw-bold">Total Materials:</span>
                                        <span>{{ $module->contents->count() }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex justify-content-md-end mb-3">
                                    <a href="{{ route('admin.courses.subjects.modules.contents.create', [$module->subject->slug, $module]) }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-1"></i> Add Content
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Content Cards by Type -->
            @foreach($contentTypes as $type => $typeData)
                @if(isset($contentByType[$type]) && $contentByType[$type]->count() > 0)
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex align-items-center">
                                    <i class="{{ $typeData['icon'] }} {{ $typeData['class'] }} me-2 fs-0"></i>
                                    <h5 class="mb-0">{{ $typeData['name'] }}</h5>
                                    <span class="badge rounded-pill bg-primary ms-2">{{ $contentByType[$type]->count() }}</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    @foreach($contentByType[$type] as $content)
                                        <div class="col-md-6 col-lg-4">
                                            <div class="card h-100 border {{ $content->is_required ? 'border-warning' : '' }}">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                                        <h5 class="mb-0">{{ $content->title }}</h5>
                                                        @if($content->is_required)
                                                            <span class="badge bg-warning text-dark">Required</span>
                                                        @endif
                                                    </div>
                                                    
                                                    @if($content->description)
                                                        <p class="mb-3 fs--1">{{ $content->description }}</p>
                                                    @endif
                                                    
                                                    <div class="d-flex mt-auto">
                                                        @if($content->isFile() && $content->getFileUrl())
                                                            <a href="{{ $content->getFileUrl() }}" target="_blank" class="btn btn-sm btn-falcon-primary me-2">
                                                                <i class="fas fa-download me-1"></i> Download
                                                            </a>
                                                        @elseif($content->isExternal())
                                                            <a href="{{ $content->external_url }}" target="_blank" class="btn btn-sm btn-falcon-info me-2">
                                                                <i class="fas fa-external-link-alt me-1"></i> Open Link
                                                            </a>
                                                        @endif
                                                        
                                                        <a href="{{ route('admin.courses.subjects.modules.contents.edit', [$module->subject->slug, $module, $content]) }}" class="btn btn-sm btn-falcon-default">
                                                            <i class="fas fa-edit me-1"></i> Edit
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="card-footer bg-light py-2">
                                                    <div class="row g-0 h-100 align-items-center">
                                                        <div class="col">
                                                            <div class="fs--1 text-muted">
                                                                <i class="{{ $typeData['icon'] }} me-1"></i> {{ ucfirst($content->content_type) }}
                                                            </div>
                                                        </div>
                                                        <div class="col-auto">
                                                            <form action="{{ route('admin.courses.subjects.modules.contents.destroy', [$module->subject->slug, $module, $content]) }}" method="POST" class="d-inline delete-form">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-link btn-sm text-danger p-0">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        </div>
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
                                <div class="mb-3">
                                    <i class="fas fa-folder-open fa-3x text-300"></i>
                                </div>
                                <h5>No Learning Materials Yet</h5>
                                <p class="text-700">This module doesn't have any content yet.</p>
                                <a href="{{ route('admin.courses.subjects.modules.contents.create', [$module->subject->slug, $module]) }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i> Add First Content
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize delete confirmations
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!confirm('Are you sure you want to delete this content? This action cannot be undone.')) {
                    e.preventDefault();
                }
            });
        });
    });
</script>
@endpush 