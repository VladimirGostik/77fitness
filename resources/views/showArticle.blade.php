@extends('layouts.app')

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row justify-content-center">
        <div class="col-md-10">
            <a href="{{ route('articles.index') }}" class="btn btn-outline-secondary mb-3">Go back</a>
            <h1 class="text-white">{{ $article->title }}</h1>
            <img class="img-fluid mb-4 article-image" src="/storage/cover_images/{{ $article->cover_image }}" alt="{{ $article->title }}">
        </div>
    </div>

    <div class="row mt-3 justify-content-center">
        <div class="col-md-10">
            <div class="article-content bg-dark text-white p-4 rounded">
                {!! nl2br(e($article->content)) !!}
            </div>
            <hr>
            @auth
                @if(auth()->user()->id === $article->user_id || auth()->user()->role === 3)
                    <small class="text-white">Written on {{ $article->created_at }}</small>
                    <a href="{{ route('articles.edit', ['article' => $article->id]) }}" class="btn btn-outline-secondary ml-3">Edit</a>
                    <form action="{{ route('articles.destroy', ['article' => $article->id]) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger ml-2">Delete</button>
                    </form>
                @endif
            @endauth
        </div>
    </div>

    <div class="row mt-3 justify-content-between">
        <div class="col-md-6">
            @if ($previous)
                <a href="{{ route('articles.show', ['article' => $previous->id]) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Previous Article
                </a>
            @endif
        </div>
        <div class="col-md-6 text-right">
            @if ($next)
                <a href="{{ route('articles.show', ['article' => $next->id]) }}" class="btn btn-outline-secondary">
                    Next Article <i class="fas fa-arrow-right"></i>
                </a>
            @endif
        </div>
    </div>

    <style>
        body {
            background-color: #141619;
            color: #ffffff;
        }

        h1 {
            font-weight: bold;
        }

        .article-image {
            width: 80%; /* Set the image width to 80% */
            margin: auto;
            display: block;
        }

        .article-content p {
            line-height: 1.6;
        }

        .article-content ul, .article-content ol {
            padding-left: 20px;
            margin-bottom: 20px;
        }

        .article-content ul {
            list-style-type: disc;
        }

        .article-content ol {
            list-style-type: decimal;
        }

        .article-content ul li, .article-content ol li {
            margin-bottom: 10px;
        }

        .article-content {
            font-size: 1.1em;
        }

        .btn-outline-secondary {
            color: #adb5bd;
            border-color: #adb5bd;
        }

        .btn-outline-secondary:hover {
            color: #fff;
            border-color: #fff;
        }

        .btn-danger {
            background-color: #e3342f;
            border-color: #e3342f;
        }

        .btn-danger:hover {
            background-color: #cc1f1a;
            border-color: #cc1f1a;
        }
    </style>
@endsection
