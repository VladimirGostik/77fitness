@extends('layouts.app')
@section('title', 'Users List')
@section('content')
    <h1>Users List</h1>
    <a href="/home" class="btn btn-default">Go back</a>

    <form action="{{ route('users.index') }}" method="GET">
        @csrf
        <input type="text" name="query" placeholder="Search users" value="{{ $query }}">
        <button type="submit">Search</button>
    </form>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(count($users) >= 1)
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <a href="{{ route('users.edit', ['id' => $user->id]) }}" class="btn btn-primary">Edit</a>
                            <form action="{{ route('users.destroy', ['id' => $user->id]) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No users found.</p>
    @endif
@endsection
