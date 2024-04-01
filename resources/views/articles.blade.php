@extends('layouts.app')

@section('title', 'Articles')

@section('content')
    <h1>Articles</h1>

    @if (Auth::check())
        <a href="/home" class="btn btn-primary">Go back</a>
    @else
        <a href="/" class="btn btn-primary">Go back</a>
    @endif
   

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(count($articles) >= 1)
        <div class="card">
            <ul class="list-group list-group-flush">
                @foreach($articles as $article)
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <img class="img-fluid" src="/storage/cover_images/{{ $article->cover_image }}" alt="{{ $article->title }}">
                        </div>
                        <div class="col-md-8">
                            <h3><a href="/articles/{{ $article->id }}">{{ $article->title }}</a></h3>
                            <small>Written on {{ $article->created_at }}</small>
                        </div>
                    </div>
                @endforeach
            </ul>
        </div>
    @else
        <p>No articles found.</p>
    @endif
@endsection
