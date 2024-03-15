@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Reservation</h1>
        <a href="/reservations" class="btn btn-default">Go back</a>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('reservations.update', ['reservation' => $reservation->id]) }}">
            @csrf
            @method('PUT')

            <!-- Add form fields here, for example: -->
            <div class="form-group">
                <label for="start_time">Start Time:</label>
                <input type="time" name="start_time" value="{{ date('H:i', strtotime($reservation->start_reservation)) }}" required>
            </div>

            <div class="form-group">
                <label for="end_time">End Time:</label>
                <input type="time" name="end_time" value="{{ date('H:i', strtotime($reservation->end_reservation)) }}" required>
            </div>

            <div class="form-group">
                <label for="reservation_price">Reservation Price:</label>
                <input type="number" name="reservation_price" value="{{ $reservation->reservation_price }}" step="0.01" required>
            </div>

            <!-- Add other fields as needed -->

            <button type="submit" class="btn btn-primary">Update Reservation</button>
        </form>
    </div>
@endsection
