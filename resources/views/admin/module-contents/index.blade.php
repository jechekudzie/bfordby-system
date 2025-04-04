@extends('layouts.admin')

@section('title', 'Module Content')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">{{ $module->title }} - Content</h1>
    
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.courses.index') }}">Courses</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.courses.subjects.index', $course->slug) }}">{{ $course->title }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.courses.subjects.modules.index', $subject->slug) }}">{{ $subject->title }}</a></li>
        <li class="breadcrumb-item active">Module Content</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-folder-open me-1"></i>
                Module Content
            </div>
            <div class="d-flex">
                <a href="{{ route('admin.courses.subjects.modules.index', $subject->slug) }}" class="btn btn-outline-secondary btn-sm me-2">
                    <i class="fas fa-arrow-left me-1"></i> Back to Modules
                </a>
                <a href="{{ route('admin.courses.subjects.modules.contents.create', [$subject->slug, $module]) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i> Add Content
                </a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            @if($contents->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="contentsTable">
                        <thead>
                            <tr>
                                <th style="width: 50px">Order</th>
                                <th style="width: 80px">Type</th>
                                <th>Title</th>
                                <th style="width: 100px">Required</th>
                                <th style="width: 150px">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="sortable-content">
                            @foreach($contents as $content)
                                <tr data-content-id="{{ $content->id }}">
                                    <td class="text-center handle">
                                        <i class="fas fa-grip-vertical" style="cursor: move"></i>
                                        <span class="sort-order">{{ $content->sort_order }}</span>
                                    </td>
                                    <td>
                                        <i class="{{ $content->getIconClass() }} fa-lg"></i>
                                        <span class="ms-1">{{ ucfirst($content->content_type) }}</span>
                                    </td>
                                    <td>{{ $content->title }}</td>
                                    <td class="text-center">
                                        @if($content->is_required)
                                            <span class="badge bg-primary">Required</span>
                                        @else
                                            <span class="badge bg-secondary">Optional</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <a href="{{ route('admin.courses.subjects.modules.contents.edit', [$subject->slug, $module, $content]) }}" class="btn btn-sm btn-primary me-1">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            
                                            @if($content->isFile() && $content->getFileUrl())
                                                <a href="{{ $content->getFileUrl() }}" target="_blank" class="btn btn-sm btn-info me-1">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @elseif($content->isExternal())
                                                <a href="{{ $content->external_url }}" target="_blank" class="btn btn-sm btn-info me-1">
                                                    <i class="fas fa-external-link-alt"></i>
                                                </a>
                                            @endif
                                            
                                            <form action="{{ route('admin.courses.subjects.modules.contents.destroy', [$subject->slug, $module, $content]) }}" method="POST" class="d-inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">
                    No content has been added to this module yet. <a href="{{ route('admin.courses.subjects.modules.contents.create', [$subject->slug, $module]) }}">Add your first content item</a>.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Sortable
        const sortableContent = document.getElementById('sortable-content');
        if (sortableContent) {
            new Sortable(sortableContent, {
                handle: '.handle',
                animation: 150,
                onEnd: function() {
                    // Update order in database
                    const rows = document.querySelectorAll('#sortable-content tr');
                    const contentIds = Array.from(rows).map(row => row.dataset.contentId);
                    
                    fetch('{{ route('admin.courses.subjects.modules.contents.order', [$subject->slug, $module]) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ content_ids: contentIds })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update the visual order numbers
                            rows.forEach((row, index) => {
                                row.querySelector('.sort-order').textContent = index + 1;
                            });
                        }
                    });
                }
            });
        }
        
        // Confirm deletion
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