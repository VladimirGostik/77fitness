@extends('layout')

@section('content')
@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif
    <a href="/articles" class="btn btn-default">Go back</a>
    <h1>{{$article->title}}</h1>
    <p>{{$article->content}}</p>
    <hr>
    <small>Written on {{$article->created_at}}</small>
    <a href="/articles/{{$article->id}}/edit" class="btn btn-default">Edit</a>

    {!! Form::open(['route' => ['articles.destroy', $article->id], 'method' => 'POST', 'class' => 'pull-right']) !!}
        {{Form::hidden('_method','DELETE')}}
        {{Form::submit('Delete',['class' => 'btn btn-danger'])}}
    {!! Form::close() !!}
@endsection
