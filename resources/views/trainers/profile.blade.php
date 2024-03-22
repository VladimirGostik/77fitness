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
                    <div id="participant-container">
                        <div class="participant-input">
                            <button type="button" class="btn btn-primary btn-sm add-participant">Add participants +</button>
                        </div>
                    </div>
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
        // Add event listener to plus button
        document.querySelectorAll('.add-participant').forEach(function(button) {
            button.addEventListener('click', function() {
                // Create new input field and append it to the participant container
                var participantContainer = button.closest('#participant-container'); // Check this line
                var newInput = document.createElement('div');
                newInput.classList.add('participant-input');
                newInput.innerHTML = '<input type="text" name="participant_name[]" placeholder="Participant Name">' +
                    '<button type="button" class="btn btn-danger btn-sm remove-participant">-</button>';
                participantContainer.appendChild(newInput);
            });
        });

        // Add event listener to remove button
        document.querySelectorAll('.modal-body').forEach(function(container) {
            container.addEventListener('click', function(event) {
                if (event.target.classList.contains('remove-participant')) {
                    // Remove the participant input field when remove button is clicked
                    event.target.closest('.participant-input').remove();
                }
            });
        });


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


            // Check if type is 'group_reservation'
            if (type === 'group_reservation') {
                $('#groupReservationModal').modal('show');

                // Fetch participants for the group reservation
                $.ajax({
                    url: '/group_reservation/' + data.id + '/participants',
                    type: 'GET',
                    success: function(response) {
                        console.log(response); // Log the response data

                        // Check if the response data is an empty array
                        if (Array.isArray(response) && response.length === 0) {
                            //console.log("No participants data available.");
                        }

                        // Clear any existing participant inputs
                        $('#participantInputs').empty();

                        var participants = response; // Assuming response contains the participants data

                        $('#participantCount').val(participants.length);

                        var participantInputs = '';
                        // Populate participant input fields
                        participants.forEach(function(participant) {
                            participantInputs += '<input type="text" class="form-control mb-2" name="participant[]" value="' + participant.name + '">';
                        });
                        $('#participantInputs').html(participantInputs);
                        console.log("dostal som sa az sem");
                        // Handle submission of form data
                        $('#submitGroupReservation').off('click').on('click', function() {
                            var formData = $('#groupReservationForm').serialize();
                            
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