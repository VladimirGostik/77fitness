@extends('layouts.app')

@section('content')
@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif
    <a href="/articles" class="btn btn-default">Go back</a>
    <h1>{{$article->title}}</h1>

    <div class="row">
       <div class="col-md-12">
            <img style="width: 100%" src="/storage/cover_images/{{$article->cover_image}}" alt="">
       </div>
    </div>

    <p>{{$article->content}}</p>
    <hr>
    @if(!Auth::guest())
        @if(Auth::user()->id == $article->user_id)
            <small>Written on {{$article->created_at}}</small>
            <a href="/articles/{{$article->id}}/edit" class="btn btn-default">Edit</a>
            {!! Form::open(['route' => ['articles.destroy', $article->id], 'method' => 'POST', 'class' => 'pull-right']) !!}
                {{Form::hidden('_method','DELETE')}}
                {{Form::submit('Delete',['class' => 'btn btn-danger'])}}
            {!! Form::close() !!}
        @endif
    @endif
@endsection
