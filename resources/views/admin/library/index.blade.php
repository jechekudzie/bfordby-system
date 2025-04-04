@extends('layouts.admin')

@section('title', 'E-Library')

@section('content')
<div class="content pt-5">
    <div class="mb-9">
        <div class="row g-3 mb-4">
            <div class="col-auto">
                <h2 class="mb-0">E-Library</h2>
            </div>
        </div>
        
        <div class="row g-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <p class="mb-0">Access all course materials and learning resources organized by subject and content type.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Content Type Summary Cards -->
        <div class="row g-3 mb-4 mt-4">
            @foreach($contentTypes as $type => $typeData)
                <div class="col-md-4 col-lg-2">
                    <div class="card h-100 hover-actions-trigger">
                        <div class="card-body d-flex align-items-center px-4">
                            <div class="w-100">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="{{ $typeData['icon'] }} {{ $typeData['class'] }} fs-1 me-2"></i>
                                    <h5 class="mb-0">{{ $typeData['name'] }}</h5>
                                </div>
                                @php
                                    $count = 0;
                                    foreach ($subjects as $subject) {
                                        foreach ($subject->modules as $module) {
                                            foreach ($module->contents as $content) {
                                                if ($content->content_type === $type) {
                                                    $count++;
                                                }
                                            }
                                        }
                                    }
                                @endphp
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-primary rounded-pill">{{ $count }}</span>
                                    <span class="ms-2">{{ Str::plural('resource', $count) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Courses and Subjects -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h5 class="mb-0">Courses & Learning Materials</h5>
                    </div>
                    <div class="card-body">
                        <div class="accordion" id="coursesAccordion">
                            @foreach($courses as $course)
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading{{ $course->id }}">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                                data-bs-target="#collapse{{ $course->id }}" aria-expanded="false" 
                                                aria-controls="collapse{{ $course->id }}">
                                            <i class="fas fa-graduation-cap me-2"></i> {{ $course->name }}
                                        </button>
                                    </h2>
                                    <div id="collapse{{ $course->id }}" class="accordion-collapse collapse" 
                                         aria-labelledby="heading{{ $course->id }}" data-bs-parent="#coursesAccordion">
                                        <div class="accordion-body p-0">
                                            <!-- Subjects Accordion -->
                                            <div class="accordion" id="subjectsAccordion{{ $course->id }}">
                                                @foreach($course->subjects as $subject)
                                                    <div class="accordion-item border-0">
                                                        <h2 class="accordion-header" id="subject{{ $subject->id }}Heading">
                                                            <button class="accordion-button collapsed bg-light" type="button" 
                                                                    data-bs-toggle="collapse" 
                                                                    data-bs-target="#subject{{ $subject->id }}Collapse" 
                                                                    aria-expanded="false" 
                                                                    aria-controls="subject{{ $subject->id }}Collapse">
                                                                <i class="fas fa-book me-2"></i> {{ $subject->name }}
                                                            </button>
                                                        </h2>
                                                        <div id="subject{{ $subject->id }}Collapse" 
                                                             class="accordion-collapse collapse" 
                                                             aria-labelledby="subject{{ $subject->id }}Heading">
                                                            <div class="accordion-body">
                                                                <div class="row g-3">
                                                                    @foreach($subject->modules as $module)
                                                                        <div class="col-md-6 col-lg-4">
                                                                            <div class="card h-100">
                                                                                <div class="card-body">
                                                                                    <div class="d-flex align-items-center mb-3">
                                                                                        <div class="avatar avatar-2xl me-2">
                                                                                            <div class="avatar-name rounded-circle bg-primary-subtle text-primary">
                                                                                                <span>{{ substr($module->name, 0, 2) }}</span>
                                                                                            </div>
                                                                                        </div>
                                                                                        <h5 class="mb-0">{{ $module->name }}</h5>
                                                                                    </div>
                                                                                    
                                                                                    @if($module->description)
                                                                                        <p class="mb-3 fs--1">{{ Str::limit($module->description, 100) }}</p>
                                                                                    @endif
                                                                                    
                                                                                    <!-- Content Type Breakdown -->
                                                                                    <div class="mb-3">
                                                                                        @foreach($contentTypes as $type => $typeData)
                                                                                            @php
                                                                                                $typeCount = $module->contents->where('content_type', $type)->count();
                                                                                            @endphp
                                                                                            @if($typeCount > 0)
                                                                                                <span class="badge rounded-pill {{ $typeData['class'] }} bg-opacity-10 me-2 mb-1">
                                                                                                    <i class="{{ $typeData['icon'] }} me-1"></i>
                                                                                                    {{ $typeCount }}
                                                                                                </span>
                                                                                            @endif
                                                                                        @endforeach
                                                                                    </div>
                                                                                    
                                                                                    <a href="{{ route('admin.library.modules.show', $module) }}" class="btn btn-sm btn-falcon-primary">
                                                                                        <i class="fas fa-eye me-1"></i> View Materials
                                                                                    </a>
                                                                                    
                                                                                    @if($module->contents->where('is_required', true)->count() > 0)
                                                                                        <div class="mt-2">
                                                                                            <span class="badge rounded-pill bg-warning text-dark">
                                                                                                <i class="fas fa-star me-1"></i>
                                                                                                {{ $module->contents->where('is_required', true)->count() }} Required
                                                                                            </span>
                                                                                        </div>
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize any JavaScript functionality here if needed
    });
</script>
@endpush 