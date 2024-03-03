@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Create Reservation</h1>
        <a href="/home" class="btn btn-default">Go back</a>

        @if(session('success'))
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

        <form method="POST" action="{{ route('reservations.store') }}">
            @csrf

            <!-- Add form fields here, for example: -->
            <label for="start_time">Start Time:</label>
            <input type="time" name="start_time" required>

            <label for="end_time">End Time:</label>
            <input type="time" name="end_time" required>


            <label for="reservation_date">Date:</label>
            <input type="date" name="reservation_date" required>

            <!-- Add other fields as needed -->

            <div class="form-group">
                <label for="reservation_price">Reservation Price</label>
                <input type="number" name="reservation_price" id="reservation_price" class="form-control" step="0.01" value="{{ $sessionPrice }}" required>
            </div>

            <button type="submit" class="btn btn-primary">Create Reservation</button>
        </form>
    </div>
@endsection
