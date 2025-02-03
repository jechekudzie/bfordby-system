@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Add Contact</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('students.contacts.store', $student) }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Contact Type</label>
                        <select name="contact_type_id" class="form-select @error('contact_type_id') is-invalid @enderror">
                            <option value="">Select Contact Type</option>
                            @foreach($contactTypes as $type)
                                <option value="{{ $type->id }}" {{ old('contact_type_id') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('contact_type_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Contact Value</label>
                        <input type="text" name="value" class="form-control @error('value') is-invalid @enderror" 
                               value="{{ old('value') }}">
                        @error('value')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <div class="form-check">
                            <input type="checkbox" name="is_primary" value="1" class="form-check-input" 
                                   id="isPrimary" {{ old('is_primary') ? 'checked' : '' }}>
                            <label class="form-check-label" for="isPrimary">Set as Primary Contact</label>
                        </div>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Save Contact</button>
                        <a href="{{ route('students.show', $student) }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
