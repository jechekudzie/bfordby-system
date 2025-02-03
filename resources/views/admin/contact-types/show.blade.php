@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="row flex-between-end">
            <div class="col-auto align-self-center">
                <h5 class="mb-0">Contact Type Details</h5>
            </div>
            <div class="col-auto ms-auto">
                <a href="{{ route('admin.contact-types.edit', $contactType) }}" class="btn btn-primary">Edit</a>
                <a href="{{ route('admin.contact-types.index') }}" class="btn btn-secondary">Back to List</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p><strong>Name:</strong> {{ $contactType->name }}</p>
                <p><strong>Description:</strong> {{ $contactType->description }}</p>
                <p><strong>Created At:</strong> {{ $contactType->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Updated At:</strong> {{ $contactType->updated_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
