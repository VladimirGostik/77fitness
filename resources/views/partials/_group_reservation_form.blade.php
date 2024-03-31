<div id="group-reservation-fields" style="padding: 20px; border: 1px solid #ccc; border-radius: 10px; margin-top: 20px;">
    <form method="POST" action="{{ route('group_reservations.store') }}">
      @csrf
  
      <h3>Create Group Reservation</h3>
  
      <label for="start_time">Start Time:</label>
      <input type="time" name="start_time" required class="form-control">
  
      <label for="end_time">End Time:</label>
      <input type="time" name="end_time" required class="form-control">
  
      <label for="reservation_date">Date:</label>
      <input type="date" name="reservation_date" required class="form-control">
  
      <label for="max_participants">Max Participants:</label>
      <input type="number" name="max_participants" id="max_participants" required class="form-control">
  
      <label for="room_id">Select Room:</label>
  
      <select name="room_id" id="room_id" class="form-control">
        @foreach ($rooms as $room)
          <option value="{{ $room->id }}">{{ $room->name }}</option>
        @endforeach
      </select>
  
      <button type="submit" class="btn btn-primary mt-3">Create Group Reservation</button>
    </form>
  </div>
  