@extends('layouts.app')
@section('title', 'Edit User')
@section('content')
    <h1>Edit User</h1>
    <a href="/users" class="btn btn-default">Go back</a>


    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('users.update', ['id' => $user->id]) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Display the current user's information -->
        <div class="mb-3">
            <label for="first_name" class="form-label">First Name</label>
            <input type="text" class="form-control" id="first_name" name="first_name" value="{{ $user->first_name }}" required>
        </div>

        <div class="mb-3">
            <label for="last_name" class="form-label">Last Name</label>
            <input type="text" class="form-control" id="last_name" name="last_name" value="{{ $user->last_name }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
        </div>

        <div class="mb-3">
            <label for="phone_number" class="form-label">Phone Number</label>
            <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ $user->phone_number }}" required>
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="receive_notifications" name="receive_notifications" value="1" {{ $user->receive_notifications ? 'checked' : '' }}>
            <label class="form-check-label" for="receive_notifications">Receive Notifications</label>
        </div>

        <!-- Add more form fields for other attributes you want to edit -->

        <button type="submit" class="btn btn-primary">Update User</button>
    </form>
@endsection
