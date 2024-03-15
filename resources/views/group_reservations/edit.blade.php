@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Group Reservation</h1>
        <a href="/reservations" class="btn btn-default">Go back</a>

        @if(session()->has('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form id="updateGroupReservationForm" action="{{ route('group_reservations.update', ['group_reservation' => $groupReservation->id]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="start_time">Start Time:</label>
                <input type="time" name="start_time" value="{{ date('H:i', strtotime($groupReservation->start_reservation)) }}" required>
            </div>

            <div class="form-group">
                <label for="end_time">End Time:</label>
                <input type="time" name="end_time" value="{{ date('H:i', strtotime($groupReservation->end_reservation)) }}" required>
            </div>

            <div class="mb-3">
                <label for="max_participants" class="form-label">Max Participants</label>
                <input type="number" class="form-control" id="max_participants" name="max_participants" value="{{ old('max_participants', $groupReservation->max_participants) }}" required>
            </div>

            <div class="mb-3">
                <label for="room_id" class="form-label">Room</label>
                <select class="form-select" id="room_id" name="room_id" required>
                    @foreach($rooms as $room)
                        <option value="{{ $room->id }}" {{ $groupReservation->room_id == $room->id ? 'selected' : '' }}>
                            {{ $room->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
   
@endsection
