@extends('layouts.student')

@section('title', 'Student Dashboard')

@section('content')
<div class="content pt-5">
    <div class="mb-9">
        <div class="row align-items-center mb-4">
            <div class="col">
                <h2 class="mb-0">Dashboard</h2>
                <p class="mt-2">Welcome to your student portal, {{ auth()->user()->name }}</p>
            </div>
        </div>
        
        <!-- Quick Stats -->
        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-lg-3">
                <div class="card overflow-hidden" style="min-width: 12rem">
                    <div class="bg-holder bg-card"></div>
                    <div class="card-body position-relative">
                        <h6>Enrolled Courses</h6>
                        <div class="display-4 fs-4 mb-2 fw-normal font-sans-serif text-info">
                            {{ auth()->user()->student->enrollments->count() }}
                        </div>
                        <a href="#" class="fw-semi-bold fs--1 text-nowrap">View courses</a>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card overflow-hidden" style="min-width: 12rem">
                    <div class="bg-holder bg-card"></div>
                    <div class="card-body position-relative">
                        <h6>Learning Materials</h6>
                        <div class="display-4 fs-4 mb-2 fw-normal font-sans-serif text-info">
                            {{ auth()->user()->student->enrollments->flatMap(function($e) { return $e->course->subjects; })->flatMap(function($s) { return $s->modules; })->flatMap(function($m) { return $m->contents; })->count() }}
                        </div>
                        <a href="{{ route('student.library.index') }}" class="fw-semi-bold fs--1 text-nowrap">Visit E-Library</a>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card overflow-hidden" style="min-width: 12rem">
                    <div class="bg-holder bg-card"></div>
                    <div class="card-body position-relative">
                        <h6>Pending Assessments</h6>
                        <div class="display-4 fs-4 mb-2 fw-normal font-sans-serif text-info">
                            {{ auth()->user()->student->enrollments->flatMap(function($e) { return $e->course->subjects; })->flatMap(function($s) { return $s->modules; })->flatMap(function($m) { return $m->assessments; })->flatMap(function($a) { return $a->allocations; })->filter(function($a) { return $a->due_date > now(); })->count() }}
                        </div>
                        <a href="{{ route('students.assessments.list') }}" class="fw-semi-bold fs--1 text-nowrap">View assessments</a>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card overflow-hidden" style="min-width: 12rem">
                    <div class="bg-holder bg-card"></div>
                    <div class="card-body position-relative">
                        <h6>Academic Transcript</h6>
                        <div class="display-4 fs-4 mb-2 fw-normal font-sans-serif text-info">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <a href="{{ route('students.transcript.show', auth()->user()->student) }}" class="fw-semi-bold fs--1 text-nowrap">View transcript</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Quick Links</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-sm-6 col-md-4">
                        <a href="{{ route('student.library.index') }}" class="text-decoration-none">
                            <div class="card bg-light shadow-sm quick-link-card h-100">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-book-open fa-2x text-primary"></i>
                                    </div>
                                    <h6 class="text-primary">E-Library</h6>
                                    <p class="mb-0 text-700 fs--1">Access learning materials</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <a href="{{ route('students.assessments.list') }}" class="text-decoration-none">
                            <div class="card bg-light shadow-sm quick-link-card h-100">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-tasks fa-2x text-success"></i>
                                    </div>
                                    <h6 class="text-success">Assessments</h6>
                                    <p class="mb-0 text-700 fs--1">View and submit assessments</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <a href="{{ route('students.transcript.show', auth()->user()->student) }}" class="text-decoration-none">
                            <div class="card bg-light shadow-sm quick-link-card h-100">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-file-alt fa-2x text-info"></i>
                                    </div>
                                    <h6 class="text-info">Transcript</h6>
                                    <p class="mb-0 text-700 fs--1">View your academic record</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 