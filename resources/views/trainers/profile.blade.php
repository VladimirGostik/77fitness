@extends('layouts.app')

@section('title', 'Profil Trénera')

@section('content')
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-4">
                <h1 class="text-white text-center mb-4">Profil Trénera</h1>
                @if (Auth::check())
                    <a href="/home" class="btn btn-outline-secondary mb-4">Späť</a>
                @else
                    <a href="/" class="btn btn-outline-secondary mb-4">Späť</a>
                @endif
                <div class="card bg-dark text-white shadow-lg border-0 rounded-lg p-4">
                    @if($trainer->profile_photo)
                        <img src="{{ asset('storage/profile_photos/' . $trainer->profile_photo) }}" alt="Profilová Fotka" class="img-fluid rounded mb-4">
                    @else
                        <span class="text-white">Bez Profilovej Fotky</span>
                    @endif
                    <h3>Meno: {{ $trainer->user->first_name }} {{ $trainer->user->last_name }}</h3>
                    <p><strong>Špecializácia: </strong>{{ $trainer->specialization }}</p>
                    <p><strong>Popis: </strong>{{ $trainer->description }}</p>
                    <p><strong>Cena za Tréning: </strong>{{ $trainer->session_price }} EUR</p>
                </div>
            </div>
            <div class="col-md-8">
                <h2 class="text-white text-center mb-4">Tréningový Kalendár</h2>
                <div id='calendar' class="bg-light p-3 rounded"></div>
                @if ($trainer->profilePhotos->count() > 0)
                    <h3 class="text-white text-center mt-4">Fotografie z Galérie</h3>
                    <div class="d-flex flex-wrap justify-content-center">
                        @foreach ($trainer->profilePhotos as $photo)
                            <img src="{{ asset('storage/trainer_gallery_photos/' . $photo->filename) }}" alt="{{ $photo->filename }}" class="img-thumbnail m-2" style="max-width: 150px; max-height: 150px;">
                        @endforeach
                    </div>
                @else
                    <p class="text-white text-center mt-4">Žiadne galérie fotiek neboli nájdené pre tohto trénera.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="modal fade" id="reservationModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Detaily Rezervácie</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zatvoriť</button>
                    <button id="submitReservation" class="btn btn-primary">Odoslať Rezerváciu</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="groupReservationModal" tabindex="-1" aria-labelledby="groupReservationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title" id="groupReservationModalLabel">Detaily Skupinovej Rezervácie</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="group-reservation-details">
                        <p><strong>Tréner:</strong> <span id="trainer-name"></span></p>
                        <p><strong>Čas Začiatku:</strong> <span id="start-time"></span></p>
                        <p><strong>Čas Konca:</strong> <span id="end-time"></span></p>
                        <p><strong>Cena za účastníka:</strong> 12 EUR</p>
                        <p><strong>Celková Cena:</strong> <span id="total-price">12</span> EUR</p>
                        <ul id="participant-list"></ul>
                    </div>
                    <div id="participant-container"></div>
                    <div class="participant-input mt-2">
                        <button type="button" class="btn btn-success btn-sm add-participant">Pridať účastníka +</button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zatvoriť</button>
                    <button type="button" class="btn btn-primary" id="submitGroupReservation">Odoslať Zmeny</button>
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
            color: white !important; /* Upraviť farbu textu pre udalosti */
        }

        .fc-daygrid-day-number {
            color: white !important; 
        }

        .fc-toolbar-title, .fc-toolbar button {
            color: white !important;
        }

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

        .card {
            background-color: #343a40;
        }

        .card h3, .card p {
            color: #ffffff;
        }

        .card .btn-primary {
            background-color: #007bff;
            border: none;
        }

        .card .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var pricePerParticipant = 12; // Cena za účastníka
            var totalPriceElement = document.getElementById('total-price');
            var participantCountElement = document.getElementById('participant-count');
            var participantListElement = document.getElementById('participant-list');
            var addParticipantButtons = document.querySelectorAll('.add-participant');

            addParticipantButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    var participantContainer = document.querySelector('#participant-container');

                    if (participantContainer) {
                        var newInput = document.createElement('div');
                        newInput.classList.add('participant-input', 'd-flex', 'align-items-center', 'mt-2');
                        newInput.innerHTML = '<input type="text" name="participant_name[]" placeholder="Meno Účastníka" class="form-control form-control-sm mr-2">' +
                            '<button type="button" class="btn btn-danger btn-sm remove-participant">-</button>';
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
                initialView: 'timeGridWeek',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: [
                    @if (Auth::check())
                        @foreach($reservations->where('trainer_id', $trainer->user_id) as $reservation)
                        {
                            title: '{{ Auth::user()->role == 2 ? ($reservation->client_id ? $reservation->client->user->last_name : 'Voľné') : ($reservation->client_id ? 'Rezervované' : 'Voľné') }}',
                            start: '{{ $reservation->start_reservation->toIso8601String() }}',
                            end: '{{ $reservation->end_reservation->toIso8601String() }}',
                            data: {!! json_encode($reservation) !!},
                            type: 'reservation',
                            backgroundColor: '{{ $reservation->client_id ? '#f56954' : '#00a65a' }}', // Nastavenie farby podľa prítomnosti klienta
                            borderColor: '{{ $reservation->client_id ? '#f56954' : '#00a65a' }}'
                        },
                        @endforeach
                    @else
                    @foreach($reservations->where('trainer_id', $trainer->user_id) as $reservation)
                        {
                            title: '{{ $reservation->client_id ? 'Rezervované' : 'Voľné' }}',
                            start: '{{ $reservation->start_reservation->toIso8601String() }}',
                            end: '{{ $reservation->end_reservation->toIso8601String() }}',
                            data: {!! json_encode($reservation) !!},          
                            type: 'reservation',                  
                            backgroundColor: '{{ $reservation->client_id ? '#f56954' : '#00a65a' }}', // Nastavenie farby podľa prítomnosti klienta
                            borderColor: '{{ $reservation->client_id ? '#f56954' : '#00a65a' }}'
                        },
                        @endforeach
                    @endif

                    @foreach($groupReservations->where('trainer_id', $trainer->user_id) as $groupReservation)
                    {
                        title: '{{ $groupReservation->participants_count }} / {{ $groupReservation->max_participants }}',
                        start: '{{ $groupReservation->start_reservation->toIso8601String() }}',
                        end: '{{ $groupReservation->end_reservation->toIso8601String() }}',
                        data: {!! json_encode($groupReservation) !!},
                        type: 'group_reservation',
                        @if ($groupReservation->participants_count >= $groupReservation->max_participants)
                            backgroundColor: 'red' // Nastavenie červenej farby pre plné rezervácie
                        @else
                            backgroundColor: 'lightgreen' // Nastavenie zelenej farby pre dostupné skupinové rezervácie
                        @endif
                    },
                    @endforeach
                ],
                eventClick: function(info) {
                    var now = new Date();
                    var eventEnd = new Date(info.event.end);

                    if (eventEnd < now ) {
                        return;
                    }
                    @if(Auth::check())
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
                    @endif
                }
            });

            calendar.render();

            function openModal(data, type) {
                $('#reservationModal').modal('show');
                if (type === 'reservation') {
                    var startTime = formatDateTime(data.start_reservation);
                    var endTime = formatDateTime(data.end_reservation);
                    $('#reservationModal .modal-title').text('Detaily Rezervácie');
                    $('#reservationModal .modal-body').html(
                        '<p><strong>Tréner:</strong> {{ $trainer->user->first_name }} {{ $trainer->user->last_name }}</p>' +
                        '<p><strong>Čas Začiatku:</strong> ' + startTime + '</p>' +
                        '<p><strong>Čas Konca:</strong> ' + endTime + '</p>' +
                        '<p><strong>Cena za Tréning:</strong> ' + data.reservation_price + ' EUR</p>'
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
                                alert('Rezerváciu sa nepodarilo odoslať.');
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
                    participantListElement.innerHTML = ''; // Vyčistiť existujúcich účastníkov

                    $.ajax({
                        url: '/group_reservation/' + data.id + '/participants',
                        type: 'GET',
                        success: function(response) {
                            if (response.length > 0) {
                                initializeTotalPrice(1);
                            } else {
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
                                        alert('Skupinovú Rezerváciu sa nepodarilo odoslať.');
                                    }
                                });
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                            alert('Nepodarilo sa načítať účastníkov skupiny.');
                        }
                    });
                } else {
                    console.error("Neplatný typ alebo údaje účastníkov.");
                }
            }

            function initializeTotalPrice(initialCount) {
                // Inicializovať celkovú cenu s daným počiatočným počtom účastníkov
                var totalPrice = 12;
                totalPriceElement.textContent = totalPrice;
            }

            function updateTotalPrice() {
                var participantCount = document.querySelectorAll('#participant-container .participant-input').length;
                var totalPrice = 12 + participantCount * pricePerParticipant;
                totalPriceElement.textContent = totalPrice;
            }

            function formatDateTime(dateTime) {
                if (dateTime instanceof Date) {
                    // Existujúca logika pre formátovanie objektu Date
                } else {
                    var dateObj = new Date(dateTime); // Konvertovať reťazec na objekt Date
                    if (dateObj.getHours) { // Skontrolovať, či konverzia bola úspešná (voliteľné)
                        var hours = dateObj.getHours().toString().padStart(2, '0');
                        var minutes = dateObj.getMinutes().toString().padStart(2, '0');
                        var day = dateObj.getDate().toString().padStart(2, '0');
                        var month = (dateObj.getMonth() + 1).toString().padStart(2, '0');
                        var year = dateObj.getFullYear();
                        return hours + ':' + minutes + '  ' + day + '.' + month + '.' + year;
                    } else {
                        console.error("Neplatný formát dátumu prenesený do formatDateTime:", dateTime);
                        return ""; // Alebo vrátiť nejakú predvolenú hodnotu
                    }
                }
            }
        });
    </script>
@endsection
