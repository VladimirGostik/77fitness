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

<!-- Group Reservation Modal -->
<div class="modal fade" id="groupReservationModal" tabindex="-1" aria-labelledby="groupReservationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="groupReservationModalLabel">Group Reservation Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Content for group reservation modal -->
                @foreach($groupReservations as $groupReservation)
                    <div>
                        <strong>Reservation ID:</strong> {{ $groupReservation->id }}<br>
                        <strong>Number of Participants:</strong> {{ $groupReservation->participants_count }}<br>
                        <strong>Start time:</strong> {{ date('H:i  d.m.Y', strtotime($groupReservation->start_reservation)) }} <br>
                        <strong>End time:</strong> {{ date('H:i  d.m.Y', strtotime($groupReservation->end_reservation)) }} <br>                        
                        <!-- Add form to add new participant -->
                        <form method="POST" action="{{ route('add.participant', ['group_reservations' => $groupReservation->id]) }}">
                            @csrf
                            <input type="text" name="participant_name" placeholder="Participant Name">
                            <button type="submit">Add Participant</button>
                        </form>
                    </div>
                @endforeach
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button id="submitGroupReservation" class="btn btn-primary">Submit Group Reservation</button>
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
                    title: '{{ $groupReservation->participants_count }} / {{ $groupReservation->max_participants }}',
                    start: '{{ $groupReservation->start_reservation->toIso8601String() }}',
                    end: '{{ $groupReservation->end_reservation->toIso8601String() }}',
                    data: {!! json_encode($groupReservation) !!},
                    type: 'group_reservation'
                },
                @endforeach
            ],
            eventClick: function(info) {
                if (info.event.extendedProps.type === 'group_reservation') {
                    openGroupReservationModal(info.event.extendedProps.data, info.event.extendedProps.type);
                } else if (info.event.extendedProps.data.client_id) {
                    info.jsEvent.preventDefault();
                    return;
                } else {
                    openModal(info.event.extendedProps.data, info.event.extendedProps.type);
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
            console.log("Opening group reservation modal");


            $('#groupReservationModal').modal('show');
            if (type === 'group_reservation' && data.participants) { // Ensure data.participants is defined
                $('#participantCount').val(data.participants.length); // Set the number of participants
                var participantInputs = '';
                data.participants.forEach(function(participant) {
                    participantInputs += '<input type="text" class="form-control mb-2" name="participant[]" value="' + participant.name + '">';
                });
                $('#participantInputs').html(participantInputs); // Add participant input fields
                $('#submitGroupReservation').off('click').on('click', function() {
                    var formData = $('#groupReservationForm').serialize(); // Serialize form data
                    $.ajax({
                        url: '/group_reservation/' + data.id + '/submit',
                        type: 'POST',
                        data: formData,
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
            }
        }


        function formatDateTime(dateTimeString) {
            var dateTime = new Date(dateTimeString);
            var hours = dateTime.getHours().toString().padStart(2, '0');
            var minutes = dateTime.getMinutes().toString().padStart(2, '0');
            var day = dateTime.getDate().toString().padStart(2, '0');
            var month = (dateTime.getMonth() + 1).toString().padStart(2, '0');
            var year = dateTime.getFullYear();
            return hours + ':' + minutes + '   ' + day + '.' + month + '.' + year;
        }
    });
</script>

@endsection