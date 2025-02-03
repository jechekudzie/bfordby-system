@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Create Country</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.countries.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label" for="name">Name</label>
                <input class="form-control @error('name') is-invalid @enderror" type="text" name="name" id="name" value="{{ old('name') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label class="form-label" for="code">Country Code</label>
                <input class="form-control @error('code') is-invalid @enderror" type="text" name="code" id="code" value="{{ old('code') }}" required maxlength="2">
                @error('code')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">Two letter country code (e.g., US, GB, FR)</small>
            </div>

            <div class="mb-3">
                <label class="form-label" for="phone_code">Phone Code</label>
                <input class="form-control @error('phone_code') is-invalid @enderror" type="text" name="phone_code" id="phone_code" value="{{ old('phone_code') }}" required>
                @error('phone_code')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">Country calling code (e.g., 1, 44, 33)</small>
            </div>

            <button class="btn btn-primary" type="submit">Create Country</button>
            <a href="{{ route('admin.countries.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
