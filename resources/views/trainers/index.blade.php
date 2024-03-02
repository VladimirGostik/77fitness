@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Trainers List</h1>
        <a href="/home" class="btn btn-default">Go back</a>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(count($trainers) >= 1)
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Specialization</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($trainers as $trainer)
                        <tr>
                            <td>{{ $trainer->user->first_name }} {{ $trainer->user->last_name }}</td>
                            <td>{{ $trainer->user->email }}</td>
                            <td>{{ $trainer->specialization }}</td>
                            <td>
                                <a href="{{ route('trainers.edit', ['trainer' => $trainer->id]) }}" class="btn btn-primary">Edit Trainer</a>
                                
                                <!-- Add a delete button -->
                                <form action="{{ route('trainers.destroy', ['trainer' => $trainer->id]) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete Trainer</button>
                                </form>
                                <!-- End delete button -->

                                <!-- Add other actions as needed -->
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No trainers found.</p>
        @endif
    </div>
@endsection
