@extends('layouts.app')
@section('title', 'Users List')
@section('content')
    <div class="container mt-5 rounded bg-dark text-white p-4 shadow-lg">
        <h1 class="text-white text-center mb-4">Users List</h1>
        <a href="/home" class="btn btn-outline-secondary mb-4">Go back</a>

        <form action="{{ route('users.index') }}" method="GET" class="mb-4">
            @csrf
            <div class="input-group">
                <input type="text" name="query" class="form-control" placeholder="Search users" value="{{ $query }}">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </form>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(count($users) >= 1)
            <div class="list-group">
                @foreach($users as $user)
                    <div class="list-group-item list-group-item-dark mb-3 rounded d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">{{ $user->first_name }} {{ $user->last_name }}</h5>
                            <p class="mb-1">{{ $user->email }}</p>
                        </div>
                        <div class="btn-group" role="group">
                            <a href="{{ route('users.edit', ['id' => $user->id]) }}" class="btn btn-primary btn-sm">Edit</a>
                            <form action="{{ route('users.destroy', ['id' => $user->id]) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-white">No users found.</p>
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

        .list-group-item {
            background-color: #343a40;
            border: 1px solid #454d55;
            padding: 15px;
        }

        .list-group-item h5, .list-group-item p {
            margin: 0;
        }

        .list-group-item .btn-group {
            flex-shrink: 0;
        }

        .input-group .form-control, .input-group .btn-primary {
            background-color: #495057;
            color: #fff;
            border: 1px solid #ced4da;
        }

        .input-group .form-control:focus, .input-group .btn-primary:focus {
            background-color: #495057;
            color: #fff;
            border-color: #80bdff;
        }

        .container {
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
    </style>
@endsection
