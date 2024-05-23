@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-white text-center mb-4">Trainers List</h1>
        @if (Auth::check())
            <a href="/home" class="btn btn-outline-secondary mb-4">Go back</a>
        @else
            <a href="/" class="btn btn-outline-secondary mb-4">Go back</a>
        @endif
         
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(count($trainers) >= 1)
            <div class="row row-cols-1 row-cols-md-3 g-4">
                @foreach($trainers as $trainer)
                    <div class="col">
                        <div class="card h-100 bg-dark text-white shadow-lg border-0 rounded-lg" style="border-radius: 15px;">
                            <div class="profile-picture-container" style="max-height: 300px; overflow: hidden; display: flex; justify-content: center; align-items: center;">
                                @if($trainer->profile_photo)
                                    <img src="{{ asset('storage/profile_photos/' . $trainer->profile_photo) }}" alt="Profile Picture" class="img-fluid" style="max-width: 100%; max-height: 100%; border-radius: 15px 15px 0 0;">
                                @else
                                    <span class="text-center">No Profile Photo</span>
                                @endif
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">{{ $trainer->user->first_name }} {{ $trainer->user->last_name }}</h5>
                                <p class="card-text">{{ $trainer->user->email }}</p>
                                <p class="card-text">{{ $trainer->specialization }}</p>
                            </div>
                            <div class="card-footer d-flex justify-content-between">
                                @if (Auth::check())
                                    <a href="{{ route('trainers.edit', ['trainer' => $trainer->user_id]) }}" class="btn btn-primary btn-sm btn-block" style="width: 48%;">Edit</a>
                                    <form action="{{ route('trainers.destroy', ['trainer' => $trainer->user_id]) }}" method="POST" style="display:inline; width: 48%;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm btn-block">Delete</button>
                                    </form>
                                    @endif
                                </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-white text-center">No trainers found.</p>
        @endif
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

        .card-title {
            color: #ffffff;
        }

        .card-text {
            color: #adb5bd;
        }

        .card-footer .btn {
            margin-top: 10px;
            width: 100%; /* Make buttons full width */
        }

        .alert {
            background-color: #343a40;
            color: #ffffff;
            border: none;
        }

        .card {
            border-radius: 15px;
        }

        .btn {
            width: 100%; /* Ensure buttons are full width */
        }

    </style>
@endsection
