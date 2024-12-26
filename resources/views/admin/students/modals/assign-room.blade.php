<div class="modal fade" id="assignRoomModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assign Room</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.residencies.store') }}" method="POST" id="assignRoomForm">
                @csrf
                <input type="hidden" name="student_id" value="{{ $student->id }}">
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" for="semester_id">Semester</label>
                        <select class="form-select" name="semester_id" id="semester_id" required>
                            <option value="">Select Semester</option>
                            @foreach($semesters as $semester)
                                <option value="{{ $semester->id }}">{{ $semester->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="room_number">Room Number</label>
                        <select class="form-select" name="room_number" id="room_number" required>
                            <option value="">Select Room</option>
                            @foreach($availableRooms as $room)
                                <option value="{{ $room }}">{{ $room }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" 
                                   name="is_paid" id="is_paid" value="1">
                            <label class="form-check-label" for="is_paid">
                                Payment Received
                            </label>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Assign Room</button>
                </div>
            </form>
        </div>
    </div>
</div>
