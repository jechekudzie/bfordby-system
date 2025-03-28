@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">Add Language</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('students.languages.store', $student->slug) }}" method="POST">
                @csrf
                <div class="row g-3">
                    <!-- Language Selection -->
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="fas fa-language text-primary me-1"></i> Language
                        </label>
                        <select name="name" class="form-select @error('name') is-invalid @enderror">
                            <option value="">Select Language</option>
                            <option value="English" {{ old('name') == 'English' ? 'selected' : '' }}>English</option>
                            <option value="Mandarin Chinese" {{ old('name') == 'Mandarin Chinese' ? 'selected' : '' }}>Mandarin Chinese</option>
                            <option value="Hindi" {{ old('name') == 'Hindi' ? 'selected' : '' }}>Hindi</option>
                            <option value="Spanish" {{ old('name') == 'Spanish' ? 'selected' : '' }}>Spanish</option>
                            <option value="Arabic" {{ old('name') == 'Arabic' ? 'selected' : '' }}>Arabic</option>
                            <option value="Bengali" {{ old('name') == 'Bengali' ? 'selected' : '' }}>Bengali</option>
                            <option value="Portuguese" {{ old('name') == 'Portuguese' ? 'selected' : '' }}>Portuguese</option>
                            <option value="Russian" {{ old('name') == 'Russian' ? 'selected' : '' }}>Russian</option>
                            <option value="Japanese" {{ old('name') == 'Japanese' ? 'selected' : '' }}>Japanese</option>
                            <option value="French" {{ old('name') == 'French' ? 'selected' : '' }}>French</option>
                        </select>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Proficiency Level -->
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="fas fa-chart-line text-primary me-1"></i> Proficiency Level
                        </label>
                        <select name="proficiency_level" class="form-select @error('proficiency_level') is-invalid @enderror">
                            <option value="">Select Proficiency Level</option>
                            <option value="beginner" {{ old('proficiency_level') == 'beginner' ? 'selected' : '' }}>Beginner</option>
                            <option value="intermediate" {{ old('proficiency_level') == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                            <option value="advanced" {{ old('proficiency_level') == 'advanced' ? 'selected' : '' }}>Advanced</option>
                            <option value="native" {{ old('proficiency_level') == 'native' ? 'selected' : '' }}>Native</option>
                        </select>
                        @error('proficiency_level')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Speaking -->
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="fas fa-comments text-primary me-1"></i> Speaking
                        </label>
                        <select name="speaking" class="form-select @error('speaking') is-invalid @enderror">
                            <option value="">Select Speaking Level</option>
                            <option value="poor" {{ old('speaking') == 'poor' ? 'selected' : '' }}>Poor</option>
                            <option value="fair" {{ old('speaking') == 'fair' ? 'selected' : '' }}>Fair</option>
                            <option value="good" {{ old('speaking') == 'good' ? 'selected' : '' }}>Good</option>
                            <option value="excellent" {{ old('speaking') == 'excellent' ? 'selected' : '' }}>Excellent</option>
                        </select>
                        @error('speaking')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Writing -->
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="fas fa-pen text-primary me-1"></i> Writing
                        </label>
                        <select name="writing" class="form-select @error('writing') is-invalid @enderror">
                            <option value="">Select Writing Level</option>
                            <option value="poor" {{ old('writing') == 'poor' ? 'selected' : '' }}>Poor</option>
                            <option value="fair" {{ old('writing') == 'fair' ? 'selected' : '' }}>Fair</option>
                            <option value="good" {{ old('writing') == 'good' ? 'selected' : '' }}>Good</option>
                            <option value="excellent" {{ old('writing') == 'excellent' ? 'selected' : '' }}>Excellent</option>
                        </select>
                        @error('writing')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Reading -->
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="fas fa-book-reader text-primary me-1"></i> Reading
                        </label>
                        <select name="reading" class="form-select @error('reading') is-invalid @enderror">
                            <option value="">Select Reading Level</option>
                            <option value="poor" {{ old('reading') == 'poor' ? 'selected' : '' }}>Poor</option>
                            <option value="fair" {{ old('reading') == 'fair' ? 'selected' : '' }}>Fair</option>
                            <option value="good" {{ old('reading') == 'good' ? 'selected' : '' }}>Good</option>
                            <option value="excellent" {{ old('reading') == 'excellent' ? 'selected' : '' }}>Excellent</option>
                        </select>
                        @error('reading')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Listening -->
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="fas fa-headphones text-primary me-1"></i> Listening
                        </label>
                        <select name="listening" class="form-select @error('listening') is-invalid @enderror">
                            <option value="">Select Listening Level</option>
                            <option value="poor" {{ old('listening') == 'poor' ? 'selected' : '' }}>Poor</option>
                            <option value="fair" {{ old('listening') == 'fair' ? 'selected' : '' }}>Fair</option>
                            <option value="good" {{ old('listening') == 'good' ? 'selected' : '' }}>Good</option>
                            <option value="excellent" {{ old('listening') == 'excellent' ? 'selected' : '' }}>Excellent</option>
                        </select>
                        @error('listening')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Is Native -->
                    <div class="col-12">
                        <div class="form-check">
                            <input type="hidden" name="is_native" value="0">
                            <input type="checkbox" 
                                   class="form-check-input" 
                                   id="isNative" 
                                   name="is_native"
                                   value="1"
                                   {{ old('is_native') ? 'checked' : '' }}>
                            <label class="form-check-label" for="isNative">
                                <i class="fas fa-home text-primary me-1"></i> This is my native language
                            </label>
                        </div>
                    </div>

                    <div class="col-12">
                        <hr>
                        <div class="d-flex justify-content-start gap-2">
                           
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Save Language
                            </button>

                            <a href="{{ route('students.show', $student) }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 