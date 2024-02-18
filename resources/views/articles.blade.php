@extends('layouts.app')
@section('title','articles')
@section('content')
    <h1>
        Articles
    </h1>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    @if(count($articles) >= 1 )
        <div class = "card">
            <ul class="list-group list-group-flush">
                
                    @foreach($articles as $article)

                    <div class="row">
                        <div class="col-md-4">
                            <img style="width: 100%" src="/storage/cover_images/{{$article->cover_image}}" alt="">
                        </div>
                        <div class="col-md-8">
                            <h3><a href="/articles/{{$article->id}}"> {{$article->title}}</a></h3>
                            <small>Written on {{$article->created_at}}</small>
                        </div>

                    </div>

                

                    @endforeach
            </ul>
    @else
    @endif
@endsection