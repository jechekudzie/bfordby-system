@extends('layouts.student')

@section('title', 'E-Library - Learning Materials')

@section('content')
<div class="content pt-5">
    <div class="mb-9">
        <div class="row align-items-center mb-4">
            <div class="col">
                <h2 class="mb-0">E-Library</h2>
                <p class="mt-2">Access learning materials for all your enrolled courses</p>
            </div>
            <div class="col-auto">
                <span class="badge bg-primary rounded-pill fs--1">
                    Total Materials: {{ $totalContent }}
                </span>
            </div>
        </div>
        
        <div class="row g-3 mb-4">
            <!-- Content Type Summary Cards -->
            @foreach($contentTypes as $type => $typeData)
                @if(isset($totalContentByType[$type]) && $totalContentByType[$type] > 0)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="me-3">
                                        <span class="fa-stack fa-2x">
                                            <i class="fas fa-circle fa-stack-2x text-200"></i>
                                            <i class="{{ $typeData['icon'] }} fa-stack-1x {{ $typeData['class'] }} light"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <h5 class="mb-0">{{ $typeData['name'] }}</h5>
                                        <div class="text-700">{{ $totalContentByType[$type] }} resources</div>
                                    </div>
                                </div>
                                <p class="mb-0 fs--1">
                                    @switch($type)
                                        @case('pdf')
                                            Access downloadable PDF documents including lecture notes, research papers, and readings.
                                            @break
                                        @case('video')
                                            Watch instructional videos, lectures, and demonstrations for your modules.
                                            @break
                                        @case('audio')
                                            Listen to audio lectures, podcasts, and other audio resources.
                                            @break
                                        @case('image')
                                            View diagrams, charts, illustrations, and other visual aids.
                                            @break
                                        @case('youtube')
                                            Access curated YouTube videos related to your course content.
                                            @break
                                        @case('external_link')
                                            Explore external websites, articles, and additional learning resources.
                                            @break
                                    @endswitch
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
        
        <!-- Enrolled Courses and Materials -->
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0">My Learning Materials</h5>
            </div>
            <div class="card-body p-0">
                <div class="accordion" id="enrollmentAccordion">
                    @forelse($enrollments as $index => $enrollment)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading-{{ $enrollment->id }}">
                                <button class="accordion-button {{ $index > 0 ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $enrollment->id }}" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" aria-controls="collapse-{{ $enrollment->id }}">
                                    <span class="me-2 fw-bold">{{ $enrollment->course->name }}</span>
                                    <span class="badge rounded-pill bg-info me-2">{{ $enrollment->course->subjects->count() }} subjects</span>
                                </button>
                            </h2>
                            <div id="collapse-{{ $enrollment->id }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" aria-labelledby="heading-{{ $enrollment->id }}" data-bs-parent="#enrollmentAccordion">
                                <div class="accordion-body p-0">
                                    <div class="list-group list-group-flush">
                                        @foreach($enrollment->course->subjects as $subject)
                                            <div class="list-group-item">
                                                <div class="row align-items-center g-3">
                                                    <div class="col-md-7">
                                                        <h5 class="mb-1">{{ $subject->name }}</h5>
                                                        <p class="text-700 mb-0 fs--1">
                                                            {{ $subject->modules->count() }} modules | 
                                                            {{ $subject->modules->sum(function($module) { return $module->contents->count(); }) }} materials
                                                        </p>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="d-flex flex-wrap justify-content-md-end gap-2">
                                                            @foreach($subject->modules as $module)
                                                                @if($module->contents->count() > 0)
                                                                    <a href="{{ route('student.library.modules.show', $module) }}" class="btn btn-sm btn-falcon-primary">
                                                                        {{ $module->name }}
                                                                        <span class="badge rounded-pill bg-primary ms-1">{{ $module->contents->count() }}</span>
                                                                    </a>
                                                                @else
                                                                    <span class="btn btn-sm btn-falcon-default disabled">
                                                                        {{ $module->name }}
                                                                        <span class="badge rounded-pill bg-secondary ms-1">0</span>
                                                                    </span>
                                                                @endif
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
                    @empty
                        <div class="text-center p-5">
                            <div class="mb-3">
                                <i class="fas fa-graduation-cap fa-3x text-300"></i>
                            </div>
                            <h5>You're not enrolled in any courses yet</h5>
                            <p class="text-700">Once you're enrolled, your learning materials will appear here.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 