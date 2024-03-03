@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Welcome, {{ Auth::user()->first_name }}, this is your profile page!</h1>

        <div class="actions">
            <a href="{{ route('trainers.edit', ['trainer' => Auth::user()->trainer->id]) }}">
                <i class="fas fa-user-edit"></i> Edit Profile
            </a> <br>

            <a href="{{ route('articles.create') }}">
                <i class="fas fa-plus-circle"></i> Create Article
            </a> <br>

            <a href="{{ route('articles.index') }}">
                <i class="fas fa-list"></i> Edit Articles
            </a> <br>
      
            <a href="{{ route('reservations.create') }}">
                <i class="fas fa-calendar-plus"></i> Create Reservation
            </a> <br>

            <a href="{{ route('reservations.index') }}">All Reservation</a>

      {{-- 
            <a href="{{ route('group-reservations.create') }}">
                <i class="fas fa-users"></i> Create Group Reservation
            </a> <br>
--}}
            <!-- Add more actions as needed -->
        </div>
    </div>
@endsection
