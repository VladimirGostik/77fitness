<!-- create.blade.php -->

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

        <!-- Regular Reservation Form -->
        <form method="POST" action="{{ route('reservations.store') }}">
            @csrf

            <!-- Add form fields for regular reservation -->
            <label for="start_time">Start Time:</label>
            <input type="time" name="start_time" required class="form-control">

            <label for="end_time">End Time:</label>
            <input type="time" name="end_time" required class="form-control">

            <label for="reservation_date">Date:</label>
            <input type="date" name="reservation_date" required class="form-control">

            <!-- Add other fields as needed -->

            <div class="form-group">
                <label for="reservation_price">Reservation Price</label>
                <input type="number" name="reservation_price" id="reservation_price" class="form-control" step="0.01" value="{{ $sessionPrice }}" required>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Create Regular Reservation</button>
        </form>

        <!-- Group Reservation Form -->
        @include('partials._group_reservation_form')

    </div>

    <style>
        .container {
            text-align: center; /* Center align the content */
        }

        #group-reservation-fields {
            margin: 0 auto; /* Center the fields */
            max-width: 400px; /* Adjust the maximum width as needed */
            background-color: #f9f9f9; /* Light background color */
        }
    </style>
@endsection
