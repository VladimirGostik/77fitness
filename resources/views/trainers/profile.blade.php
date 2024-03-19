@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container">
    <div class="row">
        <div class="col-md-6">
            <h1>Trainer Profile</h1>
            <a href="/home" class="btn btn-default">Go back</a>

            <div>
                <!-- Display profile picture -->
                @if($trainer->profile_photo)
                    <img src="{{ asset('storage/profile_photos/' . $trainer->profile_photo) }}" alt="Profile Picture" style="max-width: 300px;">
                @else
                    <span>No Profile Photo</span>
                @endif
                <h3>Name: {{ $trainer->user->first_name }} {{ $trainer->user->last_name }}</h3>
                <p><strong>Specialization: </strong>{{ $trainer->specialization }}</p>
                <p><strong>Description: </strong>{{ $trainer->description }}</p>
                <p><strong>Session prize: </strong>{{$trainer->session_price}} eur</p>
                <!-- Add other trainer information here -->
            </div>
        </div>
        <div class="col-md-6">
            <h2>Training Calendar</h2>
            <!-- Add code to display training calendar with reservations here -->
            <div id='calendar'></div>
            <div id="overlay" class="reserved-overlay"></div>

        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="reservationModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Reservation Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Reservation details will be populated here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button id="submitReservation" class="btn btn-primary">Submit Reservation</button>
                <!-- Add buttons for payment options here -->
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
        background-color: rgba(255, 255, 255, 0.7); /* Adjust opacity as needed */
        z-index: 999; /* Ensure it's above the calendar events */
    }

    .fc-daygrid-day-number {
        color: black; /* Set text color to black */
    }

    .fc-event {
        cursor: pointer;
        border-radius: 5px;
        padding: 2px 4px;
        margin-right: 2px;
    }

    .fc-event-reserved {
        background-color: red !important; /* Change background color to red for reserved events */
        color: white; /* Set text color to white */
    }

    .fc-event-free:hover {
        background-color: lightgreen; /* Change background color to light green on hover */
    }

</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridWeek',
            events: [
                @foreach($reservations->where('trainer_id', $trainer->id) as $reservation)
                {
                    title: '{{ $reservation->client_id ? $reservation->client->user->last_name : 'Free' }}',
                    start: '{{ $reservation->start_reservation->toIso8601String() }}',
                    end: '{{ $reservation->end_reservation->toIso8601String() }}',
                    data: {!! json_encode($reservation) !!},
                    type: 'reservation'
                },
                @endforeach

                @foreach($groupReservations->where('trainer_id', $trainer->id) as $groupReservation)
                {
                    title: '{{ $groupReservation->max_participants }}',
                    start: '{{ $groupReservation->start_reservation->toIso8601String() }}',
                    end: '{{ $groupReservation->end_reservation->toIso8601String() }}',
                    data: {!! json_encode($groupReservation) !!},
                    type: 'group_reservation'
                },
                @endforeach
            ]
        });

        calendar.render();

        var overlay = document.getElementById('overlay'); // Get the overlay element

        calendar.on('eventDidMount', function(info) {
            // Check if the event is reserved (client_id != null)
            if (info.event.extendedProps.data.client_id) {
                // Add reserved class to make it visually different and not clickable
                info.el.classList.add('fc-event-reserved');
                // Hide the overlay for reserved events
                overlay.style.display = 'none';
            } else {
                // Add free class to make it visually different and clickable
                info.el.classList.add('fc-event-free');
                // Show the overlay for free events
                overlay.style.display = 'block';
            }
        });

        calendar.on('eventClick', function(info) {
            // Prevent default action for reserved events
            if (info.event.extendedProps.data.client_id) {
                info.jsEvent.preventDefault();
                return;
            }

            // Open modal with reservation data for free events
            openModal(info.event.extendedProps.data, info.event.extendedProps.type);
        });

        // Function to format date time
        function formatDateTime(dateTimeString) {
            var dateTime = new Date(dateTimeString);
            var hours = dateTime.getHours().toString().padStart(2, '0');
            var minutes = dateTime.getMinutes().toString().padStart(2, '0');
            var day = dateTime.getDate().toString().padStart(2, '0');
            var month = (dateTime.getMonth() + 1).toString().padStart(2, '0');
            var year = dateTime.getFullYear();
            return hours + ':' + minutes + ' ' + day + '.' + month + '.' + year;
       

    }

    // Function to open modal
    function openModal(data, type) {
        // Example of how to open a Bootstrap modal using JavaScript
        $('#reservationModal').modal('show');

        // Add logic to populate modal with reservation data based on the type (reservation or group reservation)
        // For example:
        if (type === 'reservation') {
            // Format start and end times
            var startTime = formatDateTime(data.start_reservation);
            var endTime = formatDateTime(data.end_reservation);

            // Populate modal with reservation data
            $('#reservationModal .modal-title').text('Reservation Details');
            $('#reservationModal .modal-body').html(
                '<p><strong>Trainer:</strong> {{ $trainer->user->first_name }} {{ $trainer->user->last_name }}</p>' +
                '<p><strong>Start Time:</strong> ' + startTime + '</p>' +
                '<p><strong>End Time:</strong> ' + endTime + '</p>' +
                '<p><strong>Session Price:</strong> ' + data.reservation_price + ' eur</p>'
            );

            // Add event listener for submit button
            $('#submitReservation').click(function() {
                // Perform reservation submission here
                // You can send an AJAX request to your server to update the reservation with the client ID
                // Example:

                $.ajax({
                    url: '/reservations/' + data.id + '/submit',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        // Handle response from server
                        $('#reservationModal').modal('hide');
                        location.reload(); // Reload the page after successful submission
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                        console.error(error);
                        alert('Failed to submit reservation.');
                    }
                });

            });

        } else if (type === 'group_reservation') {
            // Populate modal with group reservation data
            $('#reservationModal .modal-title').text('Group Reservation Details');
            // Add logic to display group reservation details
        }
    }
    });
</script>
@endsection