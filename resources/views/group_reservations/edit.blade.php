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

      <h5 class="mt-4">Add Participant</h5>
      @if ($groupReservation->participants_count < $groupReservation->max_participants)
        <form action="{{ route('group_reservations.add_participant', $groupReservation->id) }}" method="POST">
          @csrf

          <div class="mb-3">
            <select class="form-select" aria-label="Add Participant" name="participant_id" id="participant_id" required>
              <option value="" selected>Select Participant</option>
              @if(isset($clients))
                @foreach($clients as $client)
                  <option value="{{ $client->user->id }}"
                    @if(old('participant_id') == $client->user->id) selected @endif>
                    {{ $client->user->first_name }} {{ $client->user->last_name }}
                  </option>
                @endforeach
              @else
                <option disabled>Clients not loaded</option>
              @endif
            </select>
          </div>
          <div id="selected_user_info"></div>

          <div class="mb-3">
            <label for="participant_name" class="form-label">Participant Name</label>
            <input type="text" class="form-control" id="participant_name" name="participant_name" value="">
          </div>

          <button type="submit" class="btn btn-success">Add Participant</button>
        </form>
      @else
        <p class="text-muted">Maximum participant limit reached.</p>
      @endif
    </div>
  </div>
</div>

<script>
  const participantSelect = document.getElementById('participant_id');
  const selectedUserInfo = document.getElementById('selected_user_info');

  participantSelect.addEventListener('change', function() {
    console.log('Dropdown selection changed!');

    const selectedUserId = this.value;
    console.log('Selected user ID:', selectedUserId);

    if (selectedUserId) {
      // Assuming you have a function to fetch user data
      fetchUserdata(selectedUserId)
        .then(user => {
          if (user) {
            selectedUserInfo.innerHTML = `
              <p>Selected: ${user.first_name} ${user.last_name}</p>
            `;
            document.getElementById('participant_name').value = `${user.first_name} ${user.last_name}`;
          } else {
            selectedUserInfo.innerHTML = '';
          }
        })
        .catch(error => console.error('Error fetching user data:', error));
    } else {
      console.log('Empty selection');
      selectedUserInfo.innerHTML = '';
      document.getElementById('participant_name').value = '';
    }
  });

  // Function to fetch user data (replace with your implementation)
  function fetchUserdata(userId) {
    return fetch(`/users/${userId}`)
      .then(response => response.json())
      .catch(error => {
        console.error('Error fetching user data:', error);
        return null; // Handle errors gracefully, e.g., display an error message
      });
  }
</script>
@endsection

          
