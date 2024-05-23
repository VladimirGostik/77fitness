@extends('layouts.app')

@section('title', 'Articles')

@section('content')
    <div class="container mt-4">
        <h1 class="text-white">Articles</h1>
        @if (Auth::check())
            <a href="/home" class="btn btn-outline-secondary mb-3">Go back</a>
        @else
            <a href="/" class="btn btn-outline-secondary mb-3">Go back</a>
        @endif
        
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        
        @if(count($articles) >= 1)
            <div class="articles-list">
                @foreach($articles as $article)
                    <div class="card bg-dark text-white mb-3">
                        <div class="row no-gutters">
                            <div class="col-md-4">
                                <img class="img-fluid rounded-start" src="/storage/cover_images/{{ $article->cover_image }}" alt="{{ $article->title }}">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h3 class="card-title"><a href="/articles/{{ $article->id }}" class="text-decoration-none text-primary">{{ $article->title }}</a></h3>
                                    <small class="text-muted">Written on {{ $article->created_at }}</small>
                                    <p class="card-text mt-2">{{ Str::limit($article->content, 150, '...') }}</p>
                                    <a href="/articles/{{ $article->id }}" class="btn btn-outline-primary">Read More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-white">No articles found.</p>
        @endif
    </div>

    <style>
        .articles-list .card {
            border: none;
            border-radius: 10px; /* Rounded corners */
            overflow: hidden; /* Ensure the rounded corners are applied */
            margin-bottom: 20px; /* Add space between the cards */
        }
        .img-fluid {
            max-height: 200px;
            object-fit: cover;
        }
        h1.text-white {
            font-weight: bold;
        }
        .btn-outline-secondary {
            color: #adb5bd;
            border-color: #adb5bd;
        }
        .btn-outline-secondary:hover {
            color: #fff;
            border-color: #fff;
        }
        .btn-outline-primary {
            color: #00c3ff;
            border-color: #00c3ff;
        }
        .btn-outline-primary:hover {
            color: #fff;
            border-color: #fff;
        }
        .card-body .text-primary:hover {
            text-decoration: underline;
        }
    </style>
@endsection
