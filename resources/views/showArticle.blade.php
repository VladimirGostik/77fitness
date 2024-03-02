@extends('layouts.app')

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <a href="{{ route('articles.index') }}" class="btn btn-default">Go back</a>
            <h1>{{ $article->title }}</h1>
            <img class="img-fluid" src="/storage/cover_images/{{ $article->cover_image }}" alt="{{ $article->title }}">
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            <p>{{ $article->content }}</p>
            <hr>
            @auth
                @if(auth()->user()->id === $article->user_id || auth()->user()->role === 3)
                    <small>Written on {{ $article->created_at }}</small>
                    <a href="{{ route('articles.edit', ['article' => $article->id]) }}" class="btn btn-default">Edit</a>
                    <form action="{{ route('articles.destroy', ['article' => $article->id]) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                @endif
            @endauth
        </div>
    </div>
@endsection
