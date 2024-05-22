@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="text-center mb-5">Recent Articles</h2>
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

    <h2 class="text-center mt-5 mb-5">Available Trainers</h2>
    <div class="row">
        @if (count($trainers) > 0)
            @foreach ($trainers as $trainer)
                <div class="col-md-3 mb-4">
                    <div class="card h-100">
                        @if($trainer->profile_photo)
                            <img src="{{ asset('storage/profile_photos/' . $trainer->profile_photo) }}" class="card-img-top" alt="Trainer Photo">
                        @else
                            <img src="{{ asset('images/default_trainer_photo.jpg') }}" class="card-img-top" alt="Default Trainer Photo">
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
            <div class="col-md-12 text-center">
                <a href="/trainers" class="btn btn-outline-primary mt-4">See More Trainers</a>
            </div>
        @else
            <p class="text-center">No trainers available.</p>
        @endif
    </div>

    <div class="row mt-5">
        <div class="col-md-12">
            <a id="makeReservationLink" href="{{ route('trainer.profile', ['trainer' => $trainers->first()->user_id]) }}" class="btn btn-primary">Make a Reservation</a>
        <div class="row">
            <div class="col-md-6">
              <h4>Select Trainer</h4>
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
          <div class="row">
            <div class="col-md-12">
              <div id="calendar-container" ></div>
            </div>
          </div>
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
        var calendarEl = document.getElementById('calendar-container');
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
</script>
@endsection

<style>
    .article-card {
        height: 400px; /* Fixed height for all article cards */
        max-width: 700px;
        margin: auto;
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
    
</style>
