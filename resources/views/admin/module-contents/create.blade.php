@extends('layouts.admin')

@section('title', 'Add Module Content')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Add Content to {{ $module->title }}</h1>
    
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.courses.index') }}">Courses</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.courses.subjects.index', $course->slug) }}">{{ $course->title }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.courses.subjects.modules.index', $subject->slug) }}">{{ $subject->title }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.courses.subjects.modules.contents.index', [$subject->slug, $module]) }}">Module Content</a></li>
        <li class="breadcrumb-item active">Add Content</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-plus me-1"></i>
            Add New Content
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form action="{{ route('admin.courses.subjects.modules.contents.store', [$subject->slug, $module]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-3">
                    <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                    <div class="form-text">Provide a brief description of this content (optional).</div>
                </div>
                
                <div class="mb-3">
                    <label for="content_type" class="form-label">Content Type <span class="text-danger">*</span></label>
                    <select class="form-select" id="content_type" name="content_type" required>
                        <option value="" selected disabled>Select content type...</option>
                        <option value="pdf" {{ old('content_type') == 'pdf' ? 'selected' : '' }}>PDF Document</option>
                        <option value="video" {{ old('content_type') == 'video' ? 'selected' : '' }}>Video File</option>
                        <option value="audio" {{ old('content_type') == 'audio' ? 'selected' : '' }}>Audio File</option>
                        <option value="image" {{ old('content_type') == 'image' ? 'selected' : '' }}>Image</option>
                        <option value="youtube" {{ old('content_type') == 'youtube' ? 'selected' : '' }}>YouTube Video</option>
                        <option value="external_link" {{ old('content_type') == 'external_link' ? 'selected' : '' }}>External Link</option>
                    </select>
                </div>
                
                <div class="mb-3 file-upload-section">
                    <label for="file" class="form-label">File Upload <span class="text-danger">*</span></label>
                    <input type="file" class="form-control" id="file" name="file">
                    <div class="form-text">
                        Maximum file size: 100MB. 
                        <span class="text-file-type pdf-file">Accepted formats: PDF (.pdf)</span>
                        <span class="text-file-type video-file">Accepted formats: MP4, WebM, Ogg (.mp4, .webm, .ogg)</span>
                        <span class="text-file-type audio-file">Accepted formats: MP3, WAV, Ogg (.mp3, .wav, .ogg)</span>
                        <span class="text-file-type image-file">Accepted formats: JPEG, PNG, GIF, SVG (.jpg, .jpeg, .png, .gif, .svg)</span>
                    </div>
                </div>
                
                <div class="mb-3 external-url-section">
                    <label for="external_url" class="form-label">External URL <span class="text-danger">*</span></label>
                    <input type="url" class="form-control" id="external_url" name="external_url" value="{{ old('external_url') }}">
                    <div class="form-text youtube-hint">Enter the full YouTube video URL (e.g., https://www.youtube.com/watch?v=VIDEOID)</div>
                    <div class="form-text external-link-hint">Enter the full URL to the external resource (e.g., https://example.com/resource)</div>
                </div>
                
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="is_required" name="is_required" value="1" {{ old('is_required') ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_required">Mark as required content</label>
                    <div class="form-text">Required content will be highlighted to students as essential material.</div>
                </div>
                
                <div class="mt-4">
                    <a href="{{ route('admin.courses.subjects.modules.contents.index', [$subject->slug, $module]) }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save Content</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const contentTypeSelect = document.getElementById('content_type');
        const fileUploadSection = document.querySelector('.file-upload-section');
        const fileInput = document.getElementById('file');
        const externalUrlSection = document.querySelector('.external-url-section');
        const externalUrlInput = document.getElementById('external_url');
        const pdfFileText = document.querySelector('.pdf-file');
        const videoFileText = document.querySelector('.video-file');
        const audioFileText = document.querySelector('.audio-file');
        const imageFileText = document.querySelector('.image-file');
        const youtubeHint = document.querySelector('.youtube-hint');
        const externalLinkHint = document.querySelector('.external-link-hint');
        
        // Initially hide all
        fileUploadSection.style.display = 'none';
        externalUrlSection.style.display = 'none';
        pdfFileText.style.display = 'none';
        videoFileText.style.display = 'none';
        audioFileText.style.display = 'none';
        imageFileText.style.display = 'none';
        youtubeHint.style.display = 'none';
        externalLinkHint.style.display = 'none';
        
        // Initial state based on selected value
        updateFormVisibility();
        
        // Add change event listener
        contentTypeSelect.addEventListener('change', updateFormVisibility);
        
        function updateFormVisibility() {
            const selectedValue = contentTypeSelect.value;
            
            // Reset visibility and required attributes
            fileUploadSection.style.display = 'none';
            externalUrlSection.style.display = 'none';
            pdfFileText.style.display = 'none';
            videoFileText.style.display = 'none';
            audioFileText.style.display = 'none';
            imageFileText.style.display = 'none';
            youtubeHint.style.display = 'none';
            externalLinkHint.style.display = 'none';
            
            // Remove required attribute from all inputs
            fileInput.removeAttribute('required');
            externalUrlInput.removeAttribute('required');
            
            // Show relevant sections based on selection
            if (['pdf', 'video', 'audio', 'image'].includes(selectedValue)) {
                fileUploadSection.style.display = 'block';
                fileInput.setAttribute('required', 'required');
                
                if (selectedValue === 'pdf') {
                    pdfFileText.style.display = 'inline';
                } else if (selectedValue === 'video') {
                    videoFileText.style.display = 'inline';
                } else if (selectedValue === 'audio') {
                    audioFileText.style.display = 'inline';
                } else if (selectedValue === 'image') {
                    imageFileText.style.display = 'inline';
                }
            } else if (['youtube', 'external_link'].includes(selectedValue)) {
                externalUrlSection.style.display = 'block';
                externalUrlInput.setAttribute('required', 'required');
                
                if (selectedValue === 'youtube') {
                    youtubeHint.style.display = 'block';
                } else if (selectedValue === 'external_link') {
                    externalLinkHint.style.display = 'block';
                }
            }
        }
        
        // Add form submission handler
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const selectedValue = contentTypeSelect.value;
            const isFileType = ['pdf', 'video', 'audio', 'image'].includes(selectedValue);
            const isUrlType = ['youtube', 'external_link'].includes(selectedValue);
            
            let isValid = true;
            
            // Validate based on content type
            if (isFileType && !fileInput.files.length) {
                alert('Please select a file to upload.');
                isValid = false;
            } else if (isUrlType && !externalUrlInput.value) {
                alert('Please enter a valid URL.');
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    });
</script>
@endpush 