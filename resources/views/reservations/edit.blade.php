@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="text-white">Upraviť rezerváciu</h1>
            <a href="/reservations" class="btn btn-outline-secondary">Späť</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card bg-dark text-white shadow-lg border-0 rounded-lg p-4">
            <form method="POST" action="{{ route('reservations.update', ['reservation' => $reservation->id]) }}">
                @csrf
                @method('PUT')

                <div class="form-group mb-3">
                    <label for="start_time" class="text-white">Začiatok:</label>
                    <input type="time" name="start_time" class="form-control bg-secondary text-white border-0" value="{{ date('H:i', strtotime($reservation->start_reservation)) }}" required>
                </div>

                <div class="form-group mb-3">
                    <label for="end_time" class="text-white">Koniec:</label>
                    <input type="time" name="end_time" class="form-control bg-secondary text-white border-0" value="{{ date('H:i', strtotime($reservation->end_reservation)) }}" required>
                </div>

                <div class="form-group mb-3">
                    <label for="reservation_price" class="text-white">Cena rezervácie:</label>
                    <input type="number" name="reservation_price" class="form-control bg-secondary text-white border-0" value="{{ $reservation->reservation_price }}" step="0.01" required>
                </div>

                <!-- Add other fields as needed -->

                <button type="submit" class="btn btn-primary">Upraviť rezerváciu</button>
            </form>
        </div>
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
            color: #ffffff; /* Ensure labels are visible */
        }
    </style>
@endsection
