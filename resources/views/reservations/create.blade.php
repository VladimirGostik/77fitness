@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-white">Vytvoriť rezerváciu</h1>
        <a href="/home" class="btn btn-outline-secondary">Späť</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div id="regular-reservation-fields" class="bg-dark text-white shadow-lg border-0 rounded-lg p-4 mb-4">
        <form method="POST" action="{{ route('reservations.store') }}">
            @csrf

            <div class="form-group mb-3">
                <label for="client_id" class="text-white">Klient:</label>
                <select class="form-select bg-secondary text-white border-0" name="client_id" id="client_id">
                    <option value="">Vyberte klienta</option>
                    @if (isset($clients))
                        @foreach ($clients as $client)
                            <option value="{{ $client->user_id }}">
                                {{ $client->user->first_name }} {{ $client->user->last_name }}
                            </option>
                        @endforeach
                    @else
                        <option disabled>Klienti neboli načítaní</option>
                    @endif
                </select>
            </div>

            <div class="form-group mb-3">
                <label for="start_time" class="text-white">Začiatok:</label>
                <input type="time" name="start_time" required class="form-control bg-secondary text-white border-0">
            </div>

            <div class="form-group mb-3">
                <label for="end_time" class="text-white">Koniec:</label>
                <input type="time" name="end_time" required class="form-control bg-secondary text-white border-0">
            </div>

            <div class="form-group mb-3">
                <label for="reservation_date" class="text-white">Dátum:</label>
                <input type="date" name="reservation_date" required class="form-control bg-secondary text-white border-0">
            </div>

            <div class="form-group mb-3">
                <label for="reservation_price" class="text-white">Cena rezervácie:</label>
                <input type="number" name="reservation_price" id="reservation_price" class="form-control bg-secondary text-white border-0" step="0.01" value="{{ $sessionPrice }}" required>
            </div>

            <button type="submit" class="btn btn-primary btn-block mt-3">Vytvoriť rezerváciu</button>
        </form>
    </div>

    @include('partials._group_reservation_form')

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

    .form-control {
        background-color: #495057;
        color: #ffffff;
        border: 1px solid #ced4da;
    }

    .form-control:focus {
        background-color: #495057;
        color: #ffffff;
        border-color: #80bdff;
    }

    .btn-primary {
        background-color: #007bff;
        border: none;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .card {
        background-color: #343a40;
    }

    label {
        color: #ffffff;
    }

    .container {
        text-align: center;
    }

    #regular-reservation-fields, #group-reservation-fields {
        margin: 0 auto;
        max-width: 500px;
        background-color: #343a40;
        padding: 20px;
        border: 1px solid #454d55;
        border-radius: 10px;
        margin-top: 20px;
    }
</style>
@endsection
