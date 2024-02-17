@extends('layout')
@section('title','articles')
@section('content')
    <h1>
        Articles
    </h1>
    
    @if(count($articles) >1 )
    <div class = "card">
        <ul class="list-group list-group-flush">
             <li class="list-group=item">
                @foreach($articles as $article)
                    <h3><a href="/articles/{{$article->id}}"> {{$article->title}}</a></h3>
                    <small>Written on {{$article->created_at}}</small>
                  
                @endforeach
            </li>
        </ul>
    @else
    @endif
@endsection