<!-- resources/views/reservations/index.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>All Reservations</h1>
        <a href="/home" class="btn btn-default">Go back</a>
        <a href="{{ route('reservations.create') }}">
            <i class="fas fa-calendar-plus"></i> Create Reservation
        </a> <br>
        @if(count($reservations) > 0)
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
                </tbody>
            </table>
        @else
            <p>No reservations found.</p>
        @endif
    </div>
@endsection
