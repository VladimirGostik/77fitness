@extends('layouts.app')

@section('content')
<div class="container mt-4">
  <h1 class="mb-4">Edit Group Reservation</h1>
  <a href="/reservations" class="btn btn-default">Go back</a>

  <div class="row">
    <div class="col-md-6">
      <form id="updateGroupReservationForm" action="{{ route('group_reservations.update', ['group_reservation' => $groupReservation->id]) }}" method="POST">
        @csrf
        @method('PUT')

        @if(session()->has('success'))
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        @endif

        <div class="mb-3">
          <label for="start_time" class="form-label">Start Time:</label>
          <input type="time" class="form-control" id="start_time" name="start_time" value="{{ date('H:i', strtotime($groupReservation->start_reservation)) }}" required>
        </div>

        <div class="mb-3">
          <label for="end_time" class="form-label">End Time:</label>
          <input type="time" class="form-control" id="end_time" name="end_time" value="{{ date('H:i', strtotime($groupReservation->end_reservation)) }}" required>
        </div>

        <div class="mb-3">
          <label for="max_participants" class="form-label">Max Participants:</label>
          <input type="number" class="form-control" id="max_participants" name="max_participants" value="{{ old('max_participants', $groupReservation->max_participants) }}" required>
        </div>

        <div class="mb-3">
          <label for="room_id" class="form-label">Room:</label>
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

    <div class="col-md-6">
        @if ($groupReservation->participants->count() > 0)
          <h4 class="mb-3">Registered Participants</h4>
          <ul class="list-group">
            @foreach($groupReservation->participants as $participant)
              <li class="list-group-item d-flex justify-content-between">
                {{ $participant->name }}
                @if($participant->client_id)
                  <span class="badge bg-primary rounded-pill">Client ID: {{ $participant->client_id }}</span>
                @else
                  <span class="badge bg-secondary rounded-pill">N/A</span>
                @endif
              </li>
            @endforeach
          </ul>
        @else
          <p>No participants registered yet.</p>
        @endif
  
        <h5 class="mt-4">Add Participant</h5>
        @if ($groupReservation->participants_count < $groupReservation->max_participants)
          <form action="{{ route('group_reservations.add_participant', $groupReservation->id) }}" method="POST">
            @csrf
  
            <div class="mb-3">
              <div class="input-group">
                <select class="form-select" aria-label="Add Participant" name="participant_id" id="participant_id" required>
                  <option value="" selected>Select Participant</option>
                  @if(isset($clients))
                    @foreach($clients as $client)
                      <option value="{{ $client->user->id }}">
                        {{ $client->user->first_name }} {{ $client->user->last_name }}
                      </option>
                    @endforeach

                  @else
                    <option disabled>Clients not loaded</option>
                  @endif
                </select>
                <a href="{{ route('group_reservations.download_pdf', $groupReservation->id) }}" class="btn btn-primary ms-2">Download Participants PDF</a>
              </div>
            </div>
  
            <button type="submit" class="btn btn-success">Add Participant</button>
          </form>

        @else
          <p class="text-muted">Maximum participant limit reached.</p>
        @endif
      </div>
</div>


@endsection

          
