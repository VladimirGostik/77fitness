@extends('layouts.app')

@section('content')
@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container">
  <div class="row">
    <div class="col-md-6">
      <h1>Trainer Profile</h1>
      @if (Auth::check())
        <a href="/home" class="btn btn-primary">Go back</a>
      @else
        <a href="/" class="btn btn-primary">Go back</a>
      @endif
      <div>
        @if($trainer->profile_photo)
          <img src="{{ asset('storage/profile_photos/' . $trainer->profile_photo) }}" alt="Profile Picture" style="max-width: 300px;">
        @else
          <span>No Profile Photo</span>
        @endif
        <h3>Name: {{ $trainer->user->first_name }} {{ $trainer->user->last_name }}</h3>
        <p><strong>Specialization: </strong>{{ $trainer->specialization }}</p>
        <p><strong>Description: </strong>{{ $trainer->description }}</p>
        <p><strong>Session prize: </strong>{{$trainer->session_price}} eur</p>
      </div>
    </div>
    <div class="col-md-6">
      <h2>Training Calendar</h2>
      <div id='calendar'></div>
      <div id="overlay" class="reserved-overlay"></div>
    </div>
  </div>
</div>
<div class="container">
    @if ($trainer->profilePhotos->count() > 0)
        <h3>Photos from gallery: </h3>
        <ul>
            @foreach ($trainer->profilePhotos as $photo)
                <img src="{{ asset('storage/trainer_gallery_photos/' . $photo->filename) }}" alt="{{ $photo->filename }}" style="max-width: 150px; max-height: 150px; margin-right: 5px;" />
            @endforeach
        </ul>
    @else
        <p>No gallery photos found for this trainer.</p>
    @endif
</div>

<div class="modal fade" id="reservationModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Reservation Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button id="submitReservation" class="btn btn-primary">Submit Reservation</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="groupReservationModal" tabindex="-1" aria-labelledby="groupReservationModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="groupReservationModalLabel">Group Reservation Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="group-reservation-details">
          <p><strong>Trainer:</strong> <span id="trainer-name"></span></p>
          <p><strong>Start Time:</strong> <span id="start-time"></span></p>
          <p><strong>End Time:</strong> <span id="end-time"></span></p>
          <p><strong>Price per participant:</strong> 12 eur</p>
          <p><strong>Participants:</strong> <span id="participant-count">0</span></p>
          <p><strong>Total Price:</strong> <span id="total-price">12</span> eur</p>
          <ul id="participant-list">
          </ul>
        </div>
        <div id="participant-container"></div>
        <div class="participant-input mt-2">
          <button type="button" class="btn btn-success btn-sm add-participant">Add participant +</button>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="submitGroupReservation">Submit Changes</button>
      </div>
    </div>
  </div>
</div>

<style>
  .reserved-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
  } 

  .fc-event-title {
    color: black; /* Adjust text color for events */
  }

  .fc-daygrid-day-number {
    color: black; 
  }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
  var pricePerParticipant = 12; // Price per participant
  var totalPriceElement = document.getElementById('total-price');
  var participantCountElement = document.getElementById('participant-count');
  var participantListElement = document.getElementById('participant-list');
  var addParticipantButtons = document.querySelectorAll('.add-participant');

  addParticipantButtons.forEach(function(button) {
    button.addEventListener('click', function() {
      var participantContainer = document.querySelector('#participant-container');

      if (participantContainer) {
        var newInput = document.createElement('div');
        newInput.classList.add('participant-input');
        newInput.innerHTML = '<input type="text" name="participant_name[]" placeholder="Participant Name">' +
          ' <button type="button" class="btn btn-danger btn-sm remove-participant">-</button>';
        participantContainer.appendChild(newInput);
        updateTotalPrice();
      } else {
        console.error("Participant container element not found.");
      }
    });
  });

  document.querySelectorAll('.modal-body').forEach(function(container) {
    container.addEventListener('click', function(event) {
      if (event.target.classList.contains('remove-participant')) {
        event.target.closest('.participant-input').remove();
        updateTotalPrice();
      }
    });
  });

  var calendarEl = document.getElementById('calendar');
  var calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridWeek',
    events: [
      @foreach($reservations->where('trainer_id', $trainer->user_id) as $reservation)
      {
        title: '{{ Auth::user()->role == 2 ? ($reservation->client_id ? $reservation->client->user->last_name : 'Free') : ($reservation->client_id ? 'Reserved' : 'Free') }}',
        start: '{{ $reservation->start_reservation->toIso8601String() }}',
        end: '{{ $reservation->end_reservation->toIso8601String() }}',
        data: {!! json_encode($reservation) !!},
        type: 'reservation',
        backgroundColor: '{{ $reservation->client_id ? 'red' : 'lightgreen' }}' // Set color based on client_id
      },
      @endforeach

      @foreach($groupReservations->where('trainer_id', $trainer->user_id) as $groupReservation)
      {
        title: '{{ $groupReservation->participants_count }} / {{ $groupReservation->max_participants }}',
        start: '{{ $groupReservation->start_reservation->toIso8601String() }}',
        end: '{{ $groupReservation->end_reservation->toIso8601String() }}',
        data: {!! json_encode($groupReservation) !!},
        type: 'group_reservation',
        @if ($groupReservation->participants_count >= $groupReservation->max_participants)
          backgroundColor: 'red' // Set red for full reservations
        @else
          backgroundColor: 'lightgreen' // Set green for available group reservations
        @endif
      },
      @endforeach
    ],

    eventClick: function(info) {
      var now = new Date();
      var eventEnd = new Date(info.event.end);

      if (eventEnd < now) {
        return;
      }

      if (info.event.extendedProps.type === 'group_reservation') {
        if (info.event.extendedProps.data.participants_count < info.event.extendedProps.data.max_participants) {
          openGroupReservationModal(info.event.extendedProps.data, info.event.extendedProps.type);
        } else {
          console.log('Maximum participants reached for group reservation.');
          return;
        }
      } else if (info.event.extendedProps.data.client_id == null) {
        openModal(info.event.extendedProps.data, info.event.extendedProps.type);
      } else {
        info.jsEvent.preventDefault();
        return;
      }
    }
  });

  calendar.render();

  function openModal(data, type) {
    $('#reservationModal').modal('show');
    if (type === 'reservation') {
      var startTime = formatDateTime(data.start_reservation);
      var endTime = formatDateTime(data.end_reservation);
      $('#reservationModal .modal-title').text('Reservation Details');
      $('#reservationModal .modal-body').html(
        '<p><strong>Trainer:</strong> {{ $trainer->user->first_name }} {{ $trainer->user->last_name }}</p>' +
        '<p><strong>Start Time:</strong> ' + startTime + '</p>' +
        '<p><strong>End Time:</strong> ' + endTime + '</p>' +
        '<p><strong>Session Price:</strong> ' + data.reservation_price + ' eur</p>'
      );
      $('#submitReservation').off('click').on('click', function() {
        $.ajax({
          url: '/reservations/' + data.id + '/submit',
          type: 'POST',
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function(response) {
            $('#reservationModal').modal('hide');
            location.reload();
          },
          error: function(xhr, status, error) {
            console.error(error);
            alert('Failed to submit reservation.');
          }
        });
      });
    }
  }

  function openGroupReservationModal(data, type) {
    if (type === 'group_reservation') {
      $('#groupReservationModal').modal('show');
      document.getElementById('trainer-name').textContent = '{{ $trainer->user->first_name }} {{ $trainer->user->last_name }}';
      document.getElementById('start-time').textContent = formatDateTime(data.start_reservation);
      document.getElementById('end-time').textContent = formatDateTime(data.end_reservation);
      participantCountElement.textContent = data.participants_count;
      participantListElement.innerHTML = ''; // Clear existing participants

      $.ajax({
        url: '/group_reservation/' + data.id + '/participants',
        type: 'GET',
        success: function(response) {
          if (response.length >= 0) {
            participantCountElement.textContent = response.length;
            initializeTotalPrice(1);
          } else {
            participantCountElement.textContent = '1';
            initializeTotalPrice(1);
          }

          $('#submitGroupReservation').off('click').on('click', function() {
            var formData = $('#groupReservationForm').serialize();

            $.ajax({
              url: '/group_reservation/' + data.id + '/submit',
              type: 'POST',
              data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                participants: $('#groupReservationModal input[name="participant_name[]"]').map(function() {
                  return $(this).val();
                }).get(),
              },
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              success: function(response) {
                $('#groupReservationModal').modal('hide');
                location.reload();
              },
              error: function(xhr, status, error) {
                console.error(error);
                alert('Failed to submit Group Reservation.');
              }
            });
          });
        },
        error: function(xhr, status, error) {
          console.error(error);
          alert('Failed to fetch group participants.');
        }
      });
    } else {
      console.error("Invalid type or participants data.");
    }
  }

  function initializeTotalPrice(initialCount) {
    // Initialize total price with the given initial count of participants
    var totalPrice = 12 + initialCount * pricePerParticipant;
    totalPriceElement.textContent = totalPrice ;
  }

  function updateTotalPrice() {
    var participantCount = document.querySelectorAll('#participant-container .participant-input').length;
    var totalPrice = 12 + participantCount * pricePerParticipant;
    totalPriceElement.textContent = totalPrice ;
  }

  function formatDateTime(dateTime) {
    if (dateTime instanceof Date) {
      // Existing logic for formatting a Date object
    } else {
      var dateObj = new Date(dateTime); // Convert the string to a Date object
      if (dateObj.getHours) { // Check if conversion was successful (optional)
        var hours = dateObj.getHours().toString().padStart(2, '0');
        var minutes = dateObj.getMinutes().toString().padStart(2, '0');
        var day = dateObj.getDate().toString().padStart(2, '0');
        var month = (dateObj.getMonth() + 1).toString().padStart(2, '0');
        var year = dateObj.getFullYear();
        return hours + ':' + minutes + '  ' + day + '.' + month + '.' + year;
      } else {
        console.error("Invalid date format passed to formatDateTime:", dateTime);
        return ""; // Or return some default value
      }
    }
  }
});
</script>

@endsection
