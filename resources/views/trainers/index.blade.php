@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Trainers List</h1>
        @if (Auth::check())
            <a href="/home" class="btn btn-primary">Go back</a>
        @else
            <a href="/" class="btn btn-primary">Go back</a>
         @endif
         
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(count($trainers) >= 1)
            <div class="row row-cols-1 row-cols-md-3 g-4">
                @foreach($trainers as $trainer)
                    <div class="col">
                        <div class="card h-100">
                            <div class="profile-picture-container" style="max-height: 300px; overflow: hidden; display: flex; justify-content: center; align-items: center;">
                                @if($trainer->profile_photo)
                                    <img src="{{ asset('storage/profile_photos/' . $trainer->profile_photo) }}" alt="Profile Picture" style="max-width: 100%; max-height: 100%;">
                                @else
                                    <span class="text-center">No Profile Photo</span>
                                @endif
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">{{ $trainer->user->first_name }} {{ $trainer->user->last_name }}</h5>
                                <p class="card-text">{{ $trainer->user->email }}</p>
                                <p class="card-text">{{ $trainer->specialization }}</p>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('trainers.edit', ['trainer' => $trainer->id]) }}" class="btn btn-primary">Edit Trainer</a>
                                <form action="{{ route('trainers.destroy', ['trainer' => $trainer->id]) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete Trainer</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p>No trainers found.</p>
        @endif
    </div>
@endsection
