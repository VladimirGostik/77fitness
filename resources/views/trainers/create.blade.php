<!-- resources/views/trainers/create.blade.php -->

@extends('layouts.app')

@section('title', 'Create Trainer')

@section('content')
    <div class="container">
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <h1>Create Trainer</h1>
        <a href="/trainers" class="btn btn-default">Go back</a>


        <form method="POST" action="{{ route('trainers.store') }}">
            @csrf

            <!-- User Information -->
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" name="first_name" id="first_name" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" name="last_name" id="last_name" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="phone_number">Phone Number</label>
                <input type="text" name="phone_number" id="phone_number" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="receive_notifications">Receive Notifications</label>
                <input type="checkbox" name="receive_notifications" id="receive_notifications" value="1">
            </div>

            <!-- Trainer Information -->
            <div class="form-group">
                <label for="specialization">Specialization</label>
                <input type="text" name="specialization" id="specialization" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control" required></textarea>
            </div>

            <div class="form-group">
                <label for="experience">Experience</label>
                <input type="text" name="experience" id="experience" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="session_price">Session Price</label>
                <input type="text" name="session_price" id="session_price" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Create Trainer</button>
        </form>
    </div>
@endsection
