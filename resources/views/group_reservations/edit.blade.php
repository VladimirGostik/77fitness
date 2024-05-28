@extends('layouts.app')

@section('content')
<div class="container mt-5">
  <h1 class="text-white text-center mb-4">Upraviť skupinovú rezerváciu</h1>
  <a href="/reservations" class="btn btn-outline-secondary mb-4 btn-md">Späť</a>

  <div class="row">
    <div class="col-md-6">
      <div class="card bg-dark text-white shadow-lg border-0 rounded-lg">
        <div class="card-body p-4">
          <form id="updateGroupReservationForm" action="{{ route('group_reservations.update', ['group_reservation' => $groupReservation->id]) }}" method="POST">
            @csrf
            @method('PUT')

            @if(session()->has('success'))
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Zavrieť"></button>
              </div>
            @endif

            <div class="form-group mb-3">
              <label for="start_time" class="form-label h5">Začiatok:</label>
              <input type="time" class="form-control form-control-sm" id="start_time" name="start_time" value="{{ date('H:i', strtotime($groupReservation->start_reservation)) }}" required>
            </div>

            <div class="form-group mb-3">
              <label for="end_time" class="form-label h5">Koniec:</label>
              <input type="time" class="form-control form-control-sm" id="end_time" name="end_time" value="{{ date('H:i', strtotime($groupReservation->end_reservation)) }}" required>
            </div>

            <div class="form-group mb-3">
              <label for="max_participants" class="form-label h5">Maximálny počet účastníkov:</label>
              <input type="number" class="form-control form-control-sm" id="max_participants" name="max_participants" value="{{ old('max_participants', $groupReservation->max_participants) }}" required>
            </div>

            <div class="form-group mb-3">
              <label for="room_id" class="form-label h5">Miestnosť:</label>
              <select class="form-select form-control-sm" id="room_id" name="room_id" required>
                @foreach($rooms as $room)
                  <option value="{{ $room->id }}" {{ $groupReservation->room_id == $room->id ? 'selected' : '' }}>
                    {{ $room->name }}
                  </option>
                @endforeach
              </select>
            </div>

            <button type="submit" class="btn btn-primary btn-block btn-md">Uložiť zmeny</button>
          </form>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card bg-dark text-white shadow-lg border-0 rounded-lg p-4">
        @if ($groupReservation->participants->count() > 0)
          <h4 class="mb-3 h5">Registrovaní účastníci</h4>
          <ul class="list-group">
            @foreach($groupReservation->participants as $participant)
              <li class="list-group-item d-flex justify-content-between bg-dark text-white">
                {{ $participant->name }}
                @if($participant->client_id)
                  <span class="badge bg-primary rounded-pill">ID klienta: {{ $participant->client_id }}</span>
                @else
                  <span class="badge bg-secondary rounded-pill">N/A</span>
                @endif
              </li>
            @endforeach
          </ul>
        @else
          <p class="text-white">Zatiaľ nie sú registrovaní žiadni účastníci.</p>
        @endif

        <h5 class="mt-4 h5">Pridať účastníka</h5>
        @if ($groupReservation->participants_count < $groupReservation->max_participants)
          <form action="{{ route('group_reservations.add_participant', $groupReservation->id) }}" method="POST">
            @csrf

            <div class="form-group mb-3">
              <div class="input-group">
                <select class="form-select form-control-sm" aria-label="Pridať účastníka" name="participant_id" id="participant_id" required>
                  <option value="" selected>Vyberte účastníka</option>
                  @if(isset($clients))
                    @foreach($clients as $client)
                      <option value="{{ $client->user->id }}">
                        {{ $client->user->first_name }} {{ $client->user->last_name }}
                      </option>
                    @endforeach
                  @else
                    <option disabled>Klienti nie sú načítaní</option>
                  @endif
                </select>
                <a href="{{ route('group_reservations.download_pdf', $groupReservation->id) }}" class="btn btn-primary ms-2 btn-sm">Stiahnuť PDF</a>
              </div>
            </div>

            <button type="submit" class="btn btn-success btn-block btn-md">Pridať účastníka</button>
          </form>
        @else
          <p class="text-muted">Bol dosiahnutý maximálny limit účastníkov.</p>
        @endif
      </div>
    </div>
  </div>
</div>

<style>
  .form-label {
    color: #adb5bd;
  }
  .form-control, .custom-file-input {
    background-color: #495057;
    color: #fff;
    border: 1px solid #ced4da;
  }
  .form-control:focus, .custom-file-input:focus {
    background-color: #495057;
    color: #fff;
    border-color: #80bdff;
  }
  .custom-file-label {
    background-color: #495057;
    color: #fff;
    border: 1px solid #ced4da;
    padding: 0.375rem 0.75rem;
  }
  .btn-primary {
    background-color: #007bff;
    border: none;
  }
  .btn-primary:hover {
    background-color: #0056b3;
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
  .card {
    border-radius: 15px;
  }
  .list-group-item {
    background-color: #343a40;
    border: 1px solid #454d55;
  }
  .btn-close-white {
    filter: invert(1);
  }
</style>
@endsection
