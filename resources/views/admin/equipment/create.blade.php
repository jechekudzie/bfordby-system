@extends('layouts.backend')

@section('content')
<div class="card mb-3">
    <div class="card-header">
        <div class="row flex-between-end">
            <div class="col-auto align-self-center">
                <h5 class="mb-0">Add New Equipment</h5>
            </div>
        </div>
    </div>
    <div class="card-body bg-light">
       <!-- Update the form tag -->
<form action="{{ route('equipment.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="name">Name</label>
                    <input type="text" 
                           class="form-control @error('name') is-invalid @enderror" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}" 
                           required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="equipment_category_id">Category</label>
                    <select class="form-select @error('equipment_category_id') is-invalid @enderror" 
                            id="equipment_category_id" 
                            name="equipment_category_id" 
                            required>
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" 
                                {{ old('equipment_category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('equipment_category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="serial_number">Serial Number</label>
                    <input type="text" 
                           class="form-control @error('serial_number') is-invalid @enderror" 
                           id="serial_number" 
                           name="serial_number" 
                           value="{{ old('serial_number') }}">
                    @error('serial_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="asset_number">Asset Number</label>
                    <input type="text" 
                           class="form-control" 
                           value="Will be auto-generated"
                           disabled>
                    <div class="form-text">Asset number will be automatically generated</div>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="status">Status</label>
                    <select class="form-select @error('status') is-invalid @enderror" 
                            id="status" 
                            name="status" 
                            required>
                        @foreach(\App\Models\Equipment::getStatuses() as $value => $label)
                            <option value="{{ $value }}" 
                                {{ old('status', 'available') == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label" for="description">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" 
                              name="description" 
                              rows="3">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 mt-4">
                    <h6 class="mb-3">Specifications</h6>
                    <div id="specifications-container">
                        <p class="text-muted">Please select a category to view specifications</p>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Create Equipment</button>
                <a href="{{ route('equipment.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('equipment_category_id');
    const specificationsContainer = document.getElementById('specifications-container');

    function loadSpecificationTemplates() {
        const categoryId = categorySelect.value;
        if (!categoryId) {
            specificationsContainer.innerHTML = '<p class="text-muted">Please select a category to view specifications</p>';
            return;
        }

        // Show loading state
        specificationsContainer.innerHTML = '<p class="text-muted">Loading specifications...</p>';

        // Fetch specification templates for selected category
        fetch(`/admin/equipment-categories/${categoryId}/specification-templates`)
            .then(response => {
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                return response.json();
            })
            .then(templates => {
                if (templates.length === 0) {
                    specificationsContainer.innerHTML = '<p class="text-muted">No specifications found for this category</p>';
                    return;
                }

                specificationsContainer.innerHTML = templates.map(template => `
                    <div class="mb-3">
                        <label class="form-label">
                            ${template.name}
                            ${template.is_required ? '<span class="text-danger">*</span>' : ''}
                        </label>
                        ${generateInputField(template)}
                        ${template.description ? `<div class="form-text">${template.description}</div>` : ''}
                    </div>
                `).join('');
            })
            .catch(error => {
                console.error('Error:', error);
                specificationsContainer.innerHTML = `<p class="text-danger">Error loading specifications: ${error.message}</p>`;
            });
    }

    function generateInputField(template) {
        const name = `specifications[${template.id}]`;
        const required = template.is_required ? 'required' : '';

        switch(template.field_type) {
            case 'text':
                return `<input type="text" 
                               class="form-control" 
                               name="${name}" 
                               ${required}>`;

            case 'number':
                return `<input type="number" 
                               class="form-control" 
                               name="${name}" 
                               ${required}>`;

            case 'textarea':
                return `<textarea class="form-control" 
                                 name="${name}" 
                                 rows="3"
                                 ${required}></textarea>`;

            case 'select':
                const options = JSON.parse(template.options || '[]');
                return `
                    <select class="form-select" 
                            name="${name}${template.allow_multiple ? '[]' : ''}"
                            ${template.allow_multiple ? 'multiple' : ''}
                            ${required}>
                        <option value="">Select Option</option>
                        ${options.map(option => `
                            <option value="${option}">${option}</option>
                        `).join('')}
                    </select>`;

            default:
                return `<input type="text" 
                               class="form-control" 
                               name="${name}" 
                               ${required}>`;
        }
    }

    // Load specifications when category changes
    categorySelect.addEventListener('change', loadSpecificationTemplates);
});
</script>
@endpush 