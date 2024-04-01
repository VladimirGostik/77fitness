@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Recent Articles</h2>
    <div class="row">
        @if (count($articles) > 0)
            @foreach ($articles as $article)
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">{{ $article->title }}</h5>
                            <p class="card-text">{{ $article->content }}</p>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('articles.show', ['article' => $article->id]) }}" class="btn btn-primary">Read More</a>
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="col-md-12">
                <a href="{{ route('articles.index') }}" class="btn btn-outline-primary">See More Articles</a>
            </div>
        @else
            <p>No articles found.</p>
        @endif
    </div>

    <h2 class="mt-5">Available Trainers</h2>
    <div class="row">
        @if (count($trainers) > 0)
            @foreach ($trainers as $trainer)
                <div class="col-md-3 mb-4">
                    <div class="card">
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
                            <a href="{{ route('trainer.profile', ['trainer' => $trainer->id]) }}" class="btn btn-primary">View Profile</a>
                        </div>
                        
                    </div>
                </div>
            @endforeach
            <div class="col-md-12">
                <a href="/trainers" class="btn btn-outline-primary">See More Trainers</a>
            </div>
        @else
            <p>No trainers available.</p>
        @endif
    </div>

    <div class="row mt-5">
        <div class="col-md-12">
            <a id="makeReservationLink" href="{{ route('trainer.profile', ['trainer' => $trainers->first()->id]) }}" class="btn btn-primary">Make a Reservation</a>

          <div class="row">
            <div class="col-md-6">
              <h4>Select Trainer</h4>
              <select id="trainer-dropdown" class="form-select mb-3">
                <option value="">Select Trainer</option>
                @foreach ($trainers as $trainer)
                  <option value="{{ $trainer->id }}" {{ (request()->get('trainer') == $trainer->id || (!request()->get('trainer') && $trainer->id === $trainers->first()->id)) ? 'selected' : '' }}>
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
        preselectedTrainerId = {{ $trainers->first()->id }};
        // Default to lowest ID
    }

    // Fetch reservations for the preselected trainer when the page loads
    fetchReservations(preselectedTrainerId);
      // Initialize the calendar        
      var calendarEl = document.getElementById('calendar-container');
      var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridWeek',
        events: [
        @foreach($reservations->where('trainer_id', $trainer->id) as $reservation)
        {
            title: '{{ $reservation->client_id ? $reservation->client->user->last_name : 'Free' }}',
            start: '{{ $reservation->start_reservation->toIso8601String() }}',
            end: '{{ $reservation->end_reservation->toIso8601String() }}',
            data: {!! json_encode($reservation) !!},
            type: 'reservation',
            backgroundColor: '{{ $reservation->client_id ? 'red' : 'lightgreen' }}' // Set color based on client_id
        },
        @endforeach

        @foreach($groupReservations->where('trainer_id', $trainer->id) as $groupReservation)
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

        // Calendar options (events, initialView, etc.)
      });
      calendar.render();
  
      // Handle trainer dropdown change
      $('#trainer-dropdown').change(function() {
        var trainerId = $(this).val();
        // Fetch reservations for the selected trainer

        if (trainerId === "" || trainerId === null) {
      // Set trainer ID to the first trainer's ID (assuming $trainers is an array of trainers)
        trainerId = {{ $trainers->first()->id }};
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
