@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Trainer</h1>
        <a href="/trainers" class="btn btn-default">Go back</a>

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

        <form method="POST" action="{{ route('trainers.update', ['trainer' => $trainer->id]) }}" >
            @csrf
            @method('PUT')

            <!-- User Details -->
            <div class="form-group">
                <label for="profile_photo">New Profile Photo:</label>
                <input type="file" name="profile_photo" class="form-control-file">
            </div>

            <div class="form-group">
                <label for="first_name">First Name:</label>
                <input type="text" name="first_name" value="{{ $trainer->user->first_name }}" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="last_name">Last Name:</label>
                <input type="text" name="last_name" value="{{ $trainer->user->last_name }}" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" value="{{ $trainer->user->email }}" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="phone_number">Phone Number:</label>
                <input type="text" name="phone_number" value="{{ $trainer->user->phone_number }}" class="form-control" required>
            </div>

            <!-- Trainer Details -->
            <div class="form-group">
                <label for="specialization">Specialization:</label>
                <input type="text" name="specialization" value="{{ $trainer->specialization }}" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea name="description" class="form-control" required>{{ $trainer->description }}</textarea>
            </div>

            <div class="form-group">
                <label for="experience">Experience:</label>
                <textarea name="experience" class="form-control" required>{{ $trainer->experience }}</textarea>
            </div>

            <div class="form-group">
                <label for="session_price">Session Price:</label>
                <input type="number" name="session_price" value="{{ $trainer->session_price }}" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Update Trainer</button>
        </form>
    </div>
@endsection
