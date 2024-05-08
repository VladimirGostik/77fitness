@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Welcome, {{ Auth::user()->first_name }}, this is a admin page!</h1>
        <div class="actions">
            <a href="{{ route('users.index') }}"><i class="fas fa-user-edit"></i> Edit User</a> <br>
            <a href="{{ route('articles.index') }}"><i class="fas fa-list"></i> Edit Articles</a> <br>
            <a href="{{ route('articles.create') }}"><i class="fas fa-plus-circle"></i> Create Article</a> <br>
            <a href="{{ route('trainers.create') }}"><i class="fas fa-plus-circle"></i> Create Trainer</a> <br>
            <a href="{{ route('trainers.index') }}"><i class="fas fa-user"></i> Edit Trainers</a><br>
            <a href="{{ route('admin.sendEmail') }}"><i class="fas fa-user"></i> Send email</a>
            <!-- Add more actions as needed -->
        </div>
    </div>
@endsection
