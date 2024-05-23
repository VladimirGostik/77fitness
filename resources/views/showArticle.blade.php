@extends('layouts.app')

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <a href="{{ route('articles.index') }}" class="btn btn-outline-secondary mb-3">Go back</a>
            <h1 class="text-white">{{ $article->title }}</h1>
            <img class="img-fluid mb-4" src="/storage/cover_images/{{ $article->cover_image }}" alt="{{ $article->title }}">
        </div>
    </div>

    <div class="row mt-3 center">
        <div class="col-md-12">
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

    <style>
        body {
            background-color: #141619;
            color: #ffffff;
        }

        h1 {
            font-weight: bold;
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
