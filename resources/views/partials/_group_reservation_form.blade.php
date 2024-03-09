<!-- _group_reservation_form.blade.php -->

<div id="group-reservation-fields" style="adding: 20px; border: 1px solid #ccc; border-radius: 10px; margin-top: 20px;">
    <h3>Create Group Reservation</h3>

    <label for="start_time_group">Start Time:</label>
    <input type="time" name="start_time_group" id="start_time_group" required class="form-control">

    <label for="end_time_group">End Time:</label>
    <input type="time" name="end_time_group" id="end_time_group" required class="form-control">

    <label for="max_participants">Max Participants:</label>
    <input type="number" name="max_participants" id="max_participants" required class="form-control">

    <label for="room_id">Select Room:</label>
    <select name="room_id" id="room_id" class="form-control">
        <!-- Add options dynamically from your rooms in the database -->
        @foreach($rooms as $room)
            <option value="{{ $room->id }}">{{ $room->name }}</option>
        @endforeach
    </select>

    <button type="button" class="btn btn-primary mt-3" onclick="createGroupReservation()">Create Group Reservation</button>
</div>

<script>
    function createGroupReservation() {
        // You can add logic here to submit the group reservation form via JavaScript
        alert('Group Reservation Created!');
    }
</script>
