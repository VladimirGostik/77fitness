<!-- reservations.index.blade.php -->

@extends('layouts.app')

@section('content')

    <div class="container">
        <h1>All Reservations</h1>
        <a href="/home" class="btn btn-default">Go back</a>
        <a href="{{ route('reservations.create') }}">
            <i class="fas fa-calendar-plus"></i> Create Reservation
        </a> <br>
        
        <div id='calendar'></div>

        @if(count($reservations) >= 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Reservation Period</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reservations as $reservation)
                        <tr>
                            <td>
                                @if($reservation->user)
                                    {{ $reservation->user->first_name }} {{ $reservation->user->last_name }}
                                @else
                                    Free
                                @endif
                            </td>
                            <td>
                                {{ $reservation->start_reservation->format('Y-m-d H:i') }} -
                                {{ $reservation->end_reservation->format('Y-m-d H:i') }}
                            </td>
                            <td>
                                <!-- Placeholder for Edit -->
                                <a href="{{ route('reservations.edit', ['reservation' => $reservation->id]) }}">Edit</a>
                                <!-- Placeholder for Delete -->
                                <form action="{{ route('reservations.destroy', ['reservation' => $reservation->id]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                
                    @foreach($groupReservations as $groupReservation)
                        <tr>
                            <td>
                                Group Reservation
                            </td>
                            <td>
                                {{ $groupReservation->start_reservation->format('Y-m-d H:i') }} -
                                {{ $groupReservation->end_reservation->format('Y-m-d H:i') }}
                            </td>
                            <td>
                                <!-- Placeholder for Edit (Modify the route as needed) -->
                                <a href="{{ route('group_reservations.edit', ['group_reservation' => $groupReservation->id]) }}">Edit</a>
                                <!-- Placeholder for Delete (Modify the route as needed) -->
                                <form action="{{ route('group_reservations.destroy', ['group_reservation' => $groupReservation->id]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                
            </table>


            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var calendarEl = document.getElementById('calendar');
                    var calendar = new FullCalendar.Calendar(calendarEl, {
                        initialView: 'dayGridWeek',
                        events: [
                            @foreach($reservations as $reservation)
                                {
                                    title: '{{ $reservation->user ? $reservation->user->full_name : 'Free' }}',
                                    start: '{{ $reservation->start_reservation->toIso8601String() }}',
                                    end: '{{ $reservation->end_reservation->toIso8601String() }}',
                                    url: '{{ route('reservations.edit', ['reservation' => $reservation->id]) }}',
                                },
                            @endforeach
                            @foreach($groupReservations as $groupReservation)
                                {
                                    title: '(' + {{ $groupReservation->max_participants }} + ')',
                                    start: '{{ $groupReservation->start_reservation->toIso8601String() }}',
                                    end: '{{ $groupReservation->end_reservation->toIso8601String() }}',
                                    url: '{{ route('group_reservations.edit', ['group_reservation' => $groupReservation->id]) }}',
                                },
                            @endforeach
                        ],
                        eventClick: function(info) {
                            window.location = info.event.url;
                            return false;
                        },
                    });
                    calendar.render();
                });
            </script>
        @else
            <p>No reservations found.</p>
        @endif

    </div>
@endsection
