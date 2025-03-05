@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-users-cog mr-2"></i>Create New Group
            </h1>
            <a href="{{ route('admin.assessment-allocation-groups.index', $allocation) }}"
               class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Groups
            </a>
        </div>

        <div class="row">
            <!-- Assessment Details Column -->
            <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-info-circle mr-1"></i> Assessment Details
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <h5 class="small font-weight-bold">Assessment</h5>
                            <p class="mb-2">{{ $allocation->assessment->name }}</p>
                            <h5 class="small font-weight-bold">Module</h5>
                            <p class="mb-2">{{ $allocation->assessment->module->name }}</p>
                        </div>
                        <hr>
                        <div class="mb-4">
                            <h5 class="small font-weight-bold">Enrollment Code</h5>
                            <p class="mb-2">{{ $allocation->enrollmentCode->code }}</p>
                            <h5 class="small font-weight-bold">Semester</h5>
                            <p class="mb-2">{{ $allocation->semester->name }}</p>
                        </div>
                        <hr>
                        <div>
                            <h5 class="small font-weight-bold">Due Date</h5>
                            <p class="mb-2">{{ $allocation->due_date ? date('d/m/Y H:i', strtotime($allocation->due_date)) : 'Not set' }}</p>
                            <h5 class="small font-weight-bold">Status</h5>
                            <span class="badge badge-{{ $allocation->status === 'active' ? 'success' : 'warning' }} px-2 py-1">
                                {{ ucfirst($allocation->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Create Group Column -->
            <div class="col-xl-8 col-lg-7">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        @if($students->isEmpty())
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                No students are currently enrolled in this course.
                            </div>
                        @else
                            <form action="{{ route('admin.assessment-allocation-groups.store', $allocation) }}" method="POST">
                                @csrf
                                
                                <!-- Group Name Input -->
                                <div class="form-group mb-4">
                                    <label for="name" class="text-dark mb-2">
                                        <i class="fas fa-tag mr-1"></i> Group Name
                                    </label>
                                    <input type="text" 
                                           name="name" 
                                           id="name" 
                                           value="{{ old('name') }}"
                                           class="form-control form-control-lg @error('name') is-invalid @enderror"
                                           placeholder="Enter group name"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Students Selection -->
                                <div class="card mb-4 border">
                                    <div class="card-header bg-white py-3">
                                        <div class="row align-items-center">
                                            <div class="col-md-6">
                                                <div class="input-group input-group-sm">
                                                    <input type="text" 
                                                           id="studentSearch" 
                                                           class="form-control"
                                                           placeholder="Search students..."
                                                           onkeyup="filterStudents()">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">
                                                            <i class="fas fa-search"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 text-right">
                                                <button type="button" 
                                                        class="btn btn-primary btn-sm px-3" 
                                                        onclick="toggleAll(true)">
                                                    <i class="fas fa-check-square mr-1"></i> Select All
                                                </button>
                                                <button type="button" 
                                                        class="btn btn-light btn-sm px-3 ml-2" 
                                                        onclick="toggleAll(false)">
                                                    <i class="fas fa-times-circle mr-1"></i> Clear
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th width="40">#</th>
                                                    <th>Student</th>
                                                    <th>Enrollment</th>
                                                    <th class="text-center" width="100">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($students as $student)
                                                    <tr class="student-item">
                                                        <td class="align-middle">
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" 
                                                                       class="custom-control-input student-checkbox" 
                                                                       name="members[]" 
                                                                       value="{{ $student->id }}"
                                                                       id="student_{{ $student->id }}"
                                                                       {{ in_array($student->id, old('members', [])) ? 'checked' : '' }}
                                                                       onchange="updateSelectedCount()">
                                                                <label class="custom-control-label" for="student_{{ $student->id }}"></label>
                                                            </div>
                                                        </td>
                                                        <td class="align-middle">
                                                            <div class="font-weight-bold">
                                                                {{ $student->first_name }} {{ $student->middle_name }} {{ $student->last_name }}
                                                            </div>
                                                            <div class="small text-muted">
                                                                #{{ $student->student_number }}
                                                            </div>
                                                        </td>
                                                        <td class="align-middle">
                                                            <div class="small">
                                                                <div class="text-muted">
                                                                    <i class="fas fa-calendar-alt mr-1"></i>
                                                                    {{ $student->enrollment_date ? \Carbon\Carbon::parse($student->enrollment_date)->format('d/m/Y') : 'N/A' }}
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            <span class="badge badge-{{ $student->status === 'active' ? 'success' : ($student->status === 'pending' ? 'warning' : 'secondary') }}">
                                                                {{ ucfirst($student->status) }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="card-footer bg-light py-2">
                                        <div class="d-flex justify-content-between align-items-center text-muted">
                                            <div>
                                                <i class="fas fa-users mr-2"></i>
                                                <span id="selectedCount">0</span>&nbsp;&nbsp;students selected
                                            </div>
                                            <div>
                                                <strong>Total:</strong>&nbsp;&nbsp;{{ $students->count() }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @error('members')
                                    <div class="alert alert-danger">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="fas fa-plus-circle mr-1"></i> Create Group
                                    </button>
                                    <a href="{{ route('admin.assessment-allocation-groups.index', $allocation) }}"
                                       class="btn btn-light ml-2">
                                        Cancel
                                    </a>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function filterStudents() {
            const searchText = document.getElementById('studentSearch').value.toLowerCase();
            const items = document.getElementsByClassName('student-item');
            
            for (let item of items) {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(searchText) ? '' : 'none';
            }
        }

        function toggleAll(select) {
            const checkboxes = document.getElementsByClassName('student-checkbox');
            for (let checkbox of checkboxes) {
                const row = checkbox.closest('tr');
                if (row.style.display !== 'none') {
                    checkbox.checked = select;
                }
            }
            updateSelectedCount();
        }

        function updateSelectedCount() {
            const checkboxes = document.getElementsByClassName('student-checkbox');
            const selectedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
            document.getElementById('selectedCount').textContent = selectedCount;
        }

        document.addEventListener('DOMContentLoaded', function() {
            updateSelectedCount();
        });
    </script>
    @endpush
@endsection 