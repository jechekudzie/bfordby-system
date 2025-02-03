@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">Add Guardian</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('students.guardians.store', $student) }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="fas fa-user text-primary me-1"></i> First Name
                        </label>
                        <input type="text" name="first_name" 
                               class="form-control @error('first_name') is-invalid @enderror"
                               value="{{ old('first_name') }}">
                        @error('first_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="fas fa-user text-primary me-1"></i> Last Name
                        </label>
                        <input type="text" name="last_name" 
                               class="form-control @error('last_name') is-invalid @enderror"
                               value="{{ old('last_name') }}">
                        @error('last_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="fas fa-heart text-primary me-1"></i> Relationship
                        </label>
                        <input type="text" name="relationship" 
                               class="form-control @error('relationship') is-invalid @enderror"
                               value="{{ old('relationship') }}">
                        @error('relationship')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="fas fa-phone text-primary me-1"></i> Contact Details
                        </label>
                        <textarea name="contact_details" 
                                  class="form-control @error('contact_details') is-invalid @enderror"
                                  rows="1">{{ old('contact_details') }}</textarea>
                        @error('contact_details')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <hr>
                        <div class="d-flex justify-content-start gap-2">
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Save Guardian
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
