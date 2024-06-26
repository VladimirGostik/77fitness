@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="text-white">Všetky rezervácie</h1>
            <div>
                <a href="/home" class="btn btn-outline-secondary">Späť</a>
                <a href="{{ route('reservations.create') }}" class="btn btn-primary ml-2">
                    <i class="fas fa-calendar-plus"></i> Vytvor rezerváciu
                </a>
            </div>
        </div>

        <div id='calendar' class="bg-light p-3 rounded mb-4"></div>

        @if(count($reservations) > 0 || count($groupReservations) > 0)
            <table class="table table-dark table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Klient</th>
                        <th>Čas rezervácie</th>
                        <th>Akcie</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reservations as $reservation)
                        <tr>
                            <td>
                                @if($reservation->user)
                                    {{ $reservation->user->first_name }} {{ $reservation->user->last_name }}
                                @else
                                    Voľná
                                @endif
                            </td>
                            <td>
                                {{ $reservation->start_reservation->format('Y-m-d H:i') }} -
                                {{ $reservation->end_reservation->format('Y-m-d H:i') }}
                            </td>
                            <td class="d-flex">
                                <a href="{{ route('reservations.edit', ['reservation' => $reservation->id]) }}" class="btn btn-sm btn-warning mr-2">Upraviť</a>
                                <form action="{{ route('reservations.destroy', ['reservation' => $reservation->id]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Vymazať</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                
                    @foreach($groupReservations as $groupReservation)
                        <tr>
                            <td>Skupinová rezervácia</td>
                            <td>
                                {{ $groupReservation->start_reservation->format('Y-m-d H:i') }} -
                                {{ $groupReservation->end_reservation->format('Y-m-d H:i') }}
                            </td>
                            <td class="d-flex">
                                <a href="{{ route('group_reservations.edit', ['group_reservation' => $groupReservation->id]) }}" class="btn btn-sm btn-warning mr-2">Upraviť</a>
                                <form action="{{ route('group_reservations.destroy', ['group_reservation' => $groupReservation->id]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Vymazať</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-white">Neboli nájdené žiadne rezervácie.</p>
        @endif

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var calendarEl = document.getElementById('calendar');
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'timeGridWeek',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    events: [
                        @foreach($reservations as $reservation)
                            {
                                title: '{{ $reservation->client_id ? $reservation->client->user->last_name : 'Voľná' }}',
                                start: '{{ $reservation->start_reservation->toIso8601String() }}',
                                end: '{{ $reservation->end_reservation->toIso8601String() }}',
                                url: '{{ route('reservations.edit', ['reservation' => $reservation->id]) }}',
                                backgroundColor: '{{ $reservation->client_id ? "#f56954" : "#00a65a" }}', // Zmena farby podľa prítomnosti klienta
                                borderColor: '{{ $reservation->client_id ? "#f56954" : "#00a65a" }}'
                            },
                        @endforeach
                        @foreach($groupReservations as $groupReservation)
                            {
                                title: 'Skupinová rezervácia (' + {{ $groupReservation->participants()->count() }} + '/' + {{ $groupReservation->max_participants }} + ')',
                                start: '{{ $groupReservation->start_reservation->toIso8601String() }}',
                                end: '{{ $groupReservation->end_reservation->toIso8601String() }}',
                                url: '{{ route('group_reservations.edit', ['group_reservation' => $groupReservation->id]) }}',
                                backgroundColor: '#3c8dbc',
                                borderColor: '#3c8dbc'
                            },
                        @endforeach
                    ],
                    eventClick: function(info) {
                        window.location = info.event.url;
                        return false;
                    },
                    editable: true,
                    droppable: true
                });
                calendar.render();
            });
        </script>
    </div>

    <style>
        .btn-outline-secondary {
            color: #ffffff;
            border-color: #ffffff;
        }

        .btn-outline-secondary:hover {
            color: #000000;
            background-color: #ffffff;
            border-color: #ffffff;
        }

        .alert {
            background-color: #343a40;
            color: #ffffff;
            border: none;
        }

        .btn-close-white {
            filter: invert(1);
        }

        .modal-content {
            border-radius: 15px;
        }

        .fc .fc-toolbar {
            margin: 0 0 20px 0;
        }

        .fc .fc-daygrid-day-frame {
            background-color: #343a40;
            border: 1px solid #454d55;
            border-radius: 5px;
        }

        .table {
            background-color: #343a40;
        }

        .table th, .table td {
            color: #ffffff;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-warning {
            background-color: #ffc107;
            border: none;
        }

        .btn-warning:hover {
            background-color: #e0a800;
        }

        .btn-danger {
            background-color: #dc3545;
            border: none;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }
    </style>
@endsection
