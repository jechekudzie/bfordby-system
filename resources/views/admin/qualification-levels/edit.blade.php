@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Edit Qualification Level</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.qualification-levels.update', $qualificationLevel) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label class="form-label" for="name">Name</label>
                <input class="form-control @error('name') is-invalid @enderror" type="text" name="name" id="name" value="{{ old('name', $qualificationLevel->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label class="form-label" for="description">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" name="description" id="description">{{ old('description', $qualificationLevel->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button class="btn btn-primary" type="submit">Update Qualification Level</button>
            <a href="{{ route('admin.qualification-levels.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
