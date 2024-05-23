@extends('layouts.app')

@section('content')
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="container mt-5">
        <h2 class="text-white text-center mb-5">Recent Articles</h2>
        <div id="articleCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach ($articles as $index => $article)
                    <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                        <div class="card bg-dark text-white article-card">
                            @if($article->cover_image)
                                <img src="{{ asset('storage/cover_images/' . $article->cover_image) }}" class="card-img article-img" alt="{{ $article->title }}">
                            @else
                                <img src="{{ asset('images/default_article_image.jpg') }}" class="card-img article-img" alt="Default Image">
                            @endif
                            <div class="card-img-overlay d-flex flex-column justify-content-end">
                                <h5 class="card-title">{{ $article->title }}</h5>
                                <p class="card-text">{{ Str::limit($article->content, 200, '...') }}</p>
                                <a href="{{ route('articles.show', ['article' => $article->id]) }}" class="btn btn-primary btn-block">Read More</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#articleCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#articleCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('articles.index') }}" class="btn btn-outline-primary">See More Articles</a>
        </div>

        <h2 class="text-white text-center mt-5 mb-5">Available Trainers</h2>
        <div id="trainerCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach ($trainers->chunk(3) as $chunk)
                    <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                        <div class="row">
                            @foreach ($chunk as $trainer)
                                <div class="col-md-4">
                                    <div class="card bg-dark text-white h-100">
                                        @if($trainer->profile_photo)
                                            <img src="{{ asset('storage/profile_photos/' . $trainer->profile_photo) }}" class="card-img-top img-fluid trainer-img" alt="Trainer Photo">
                                        @else
                                            <img src="{{ asset('images/default_trainer_photo.jpg') }}" class="card-img-top img-fluid trainer-img" alt="Default Trainer Photo">
                                        @endif
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $trainer->user->first_name }} {{ $trainer->user->last_name }}</h5>
                                            <p class="card-text">{{ $trainer->specialization }}</p>
                                        </div>
                                        <div class="card-footer">
                                            <a href="{{ route('trainer.profile', ['trainer' => $trainer->user_id]) }}" class="btn btn-primary btn-block">View Profile</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#trainerCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#trainerCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>

        <div class="text-center mt-5">
            <a id="makeReservationLink" href="{{ route('trainer.profile', ['trainer' => $trainers->first()->user_id]) }}" class="btn btn-primary btn-lg">Make a Reservation</a>
        </div>

        <div class="row mt-5 justify-content-center">
            <div class="col-md-6 text-center">
                <h4 class="text-white">Select Trainer</h4>
                <select id="trainer-dropdown" class="form-select mb-3">
                    <option value="">Select Trainer</option>
                    @foreach ($trainers as $trainer)
                        <option value="{{ $trainer->user_id }}" {{ (request()->get('trainer') == $trainer->user_id || (!request()->get('trainer') && $trainer->user_id === $trainers->first()->user_id)) ? 'selected' : '' }}>
                            {{ $trainer->user->first_name }} {{ $trainer->user->last_name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row mt-3 justify-content-center">
            <div class="col-md-12">
                <h2 class="text-white text-center mb-4">Training Calendar</h2>
                <div id="calendar" class="bg-light p-3 rounded"></div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            var preselectedTrainerId = '{{ request()->get('trainer') }}';
            if (!preselectedTrainerId) {
                preselectedTrainerId = {{ $trainers->first()->user_id }};
                // Default to lowest ID
            }

            // Fetch reservations for the preselected trainer when the page loads
            fetchReservations(preselectedTrainerId);
            // Initialize the calendar        
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridWeek',
                events: [
                    @foreach($reservations->where('trainer_id', $trainer->user_id) as $reservation)
                    {
                        title: '{{ $reservation->client_id ? $reservation->client->user->last_name : 'Free' }}',
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
            });

            calendar.render();

            // Handle trainer dropdown change
            $('#trainer-dropdown').change(function() {
                var trainerId = $(this).val();
                // Fetch reservations for the selected trainer

                if (trainerId === "" || trainerId === null) {
                    // Set trainer ID to the first trainer's ID (assuming $trainers is an array of trainers)
                    trainerId = {{ $trainers->first()->user_id }};
                }
                var reservationLink = '{{ route("trainer.profile", ":trainerId") }}';
                reservationLink = reservationLink.replace(':trainerId', trainerId);
                $('#makeReservationLink').attr('href', reservationLink);

                fetchReservations(trainerId);
            });

            // Function to fetch reservations for a trainer
            function fetchReservations(trainerId) {
                // Make an AJAX request to your Laravel route
                $.ajax({
                    url: '/home/' + trainerId, // Adjust the URL to include the trainer ID
                    method: 'GET',
                    success: function(data) {
                        const transformedReservations = transformReservationData(data.reservations);
                        const transformedGroupReservations = transformGroupReservationData(data.groupReservations);
                        // Update the calendar events with the fetched data
                        calendar.removeAllEvents();
                        calendar.addEventSource(transformedReservations.concat(transformedGroupReservations));
                        calendar.render();
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching reservations:', error);
                        // Handle error response (optional)
                        alert('Error fetching reservations. Please try again later.');
                    }
                });
            }
        });

        function transformReservationData(reservations) {
            const transformedEvents = [];
            for (const reservation of reservations) {
                const event = {
                    title:  reservation.client_id ? 'Full' : 'Free',
                    start: reservation.start_reservation,
                    end: reservation.end_reservation,
                    data: reservation,
                    type: 'reservation', 
                    backgroundColor: reservation.client_id ? 'red' : 'lightgreen'
                };
                transformedEvents.push(event);
            }
            return transformedEvents;
        }

        function transformGroupReservationData(groupReservations) {
            const transformedEvents = [];
            for (const groupReservation of groupReservations) {
                const event = {
                    title: `${groupReservation.participants?.length || 0} / ${groupReservation.max_participants}`,
                    start: groupReservation.start_reservation, // Use moment.js or similar library to format date (replace with your date formatting logic)
                    end: groupReservation.end_reservation,   // Use moment.js or similar library to format date (replace with your date formatting logic)
                    data: groupReservation, // Optional: Store the entire group reservation object for additional data access
                    type: 'group_reservation', // Adjust type based on your reservation type
                    backgroundColor: groupReservation.participants_count >= groupReservation.max_participants ? 'red' : 'lightgreen' // Adjust color logic based on your data structure
                };
                transformedEvents.push(event);
            }
            return transformedEvents;
        }
        document.addEventListener('DOMContentLoaded', function() {
        const trainerCarousel = document.querySelector('#trainerCarousel');
        const trainers = trainerCarousel.querySelectorAll('.carousel-item .col-md-4');

        let visibleItems = 3;
        let itemWidth = 100 / visibleItems;

        trainers.forEach((trainer, index) => {
            trainer.style.minWidth = `${itemWidth}%`;
            trainer.style.maxWidth = `${itemWidth}%`;
        });

        trainerCarousel.addEventListener('slide.bs.carousel', function (e) {
            const items = e.target.querySelectorAll('.carousel-item');
            const totalItems = items.length;

            if (e.direction === 'left') {
                const end = parseInt(items[totalItems - 1].getAttribute('data-index')) + 1;
                const start = parseInt(items[0].getAttribute('data-index'));
                items[start].setAttribute('data-index', end);
                items[start].parentNode.appendChild(items[start]);
            } else {
                const start = parseInt(items[0].getAttribute('data-index')) - 1;
                const end = parseInt(items[totalItems - 1].getAttribute('data-index'));
                items[end].setAttribute('data-index', start);
                items[end].parentNode.insertBefore(items[end], items[0]);
            }
        });
    });
    </script>
@endsection

<style>
    .article-card {
        height: 400px; /* Fixed height for all article cards */
        max-width: 700px;
        margin: auto;
        border-radius: 15px; /* Rounded corners */
        overflow: hidden; /* Hide overflow */
    }

    .article-img {
        height: 70%; /* Adjust height */
        width: 100%; /* Adjust width to fit within the card */
        object-fit: cover;
    }

    .card-body {
        position: relative;
        height: 30%;
        background: rgba(0, 0, 0, 0.6); /* Black background with transparency */
        color: white;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .carousel-inner .carousel-item {
        max-height: 400px; /* Set a max height for the carousel items */
    }

    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        background-color: rgba(0, 0, 0, 0.5); /* Darken the carousel control icons */
    }

    .btn-outline-primary {
        color: #ffffff;
        border-color: #ffffff;
    }

    .btn-outline-primary:hover {
        color: #000000;
        background-color: #ffffff;
        border-color: #ffffff;
    }

    .card {
        border-radius: 15px;
    }

    .btn {
        margin-top: 10px;
        width: 100%; /* Ensure buttons are full width */
    }

    .alert {
        background-color: #343a40;
        color: #ffffff;
        border: none;
    }

    .fc-event-title {
        color: white !important; /* Adjust text color for events */
    }

    .fc-daygrid-day-number {
        color: white !important; 
    }

    .fc-toolbar-title, .fc-toolbar button {
        color: white !important;
    }

    .fc .fc-toolbar {
        margin: 0 0 20px 0;
    }

    .fc .fc-daygrid-day-frame {
        background-color: #343a40;
        border: 1px solid #454d55;
        border-radius: 5px;
    }

    .btn-close-white {
        filter: invert(1);
    }

    .modal-content {
        border-radius: 15px;
    }

    .btn-primary.btn-lg {
        width: auto;
        padding: 10px 20px;
    }

    .form-select {
        width: 50%;
        margin: 0 auto;
    }

    #calendar {
        width: 100%;
        max-width: 100%;
    }
    .card-img-overlay {
        position: absolute;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7); /* Semi-transparent background */
        color: white;
        width: 100%;
        padding: 20px;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8); /* Add text shadow for better readability */
        transition: background 0.5s ease; /* Smooth background transition */
    }

    .card:hover .card-img-overlay {
        background: rgba(0, 0, 0, 0.9); /* Darken background on hover */
    }

    .carousel-inner .carousel-item {
        display: flex;
    }

    .carousel-inner .carousel-item > div {
        flex: 1;
    }

    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        background-color: rgba(0, 0, 0, 0.5); /* Darken the carousel control icons */
    }

    .card {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Add box shadow for depth */
        transition: transform 0.3s ease; /* Smooth hover effect */
    }

    .card:hover {
        transform: translateY(-10px); /* Lift card on hover */
    }


</style>
