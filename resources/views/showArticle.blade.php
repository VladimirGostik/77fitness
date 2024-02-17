@extends('layout')

@section('content')
<a href="/articles" class ="btn btn-default">Go back</a>
<h1>{{$article->title}}</h1>
<p>{{$article->content}}</p>
<hr>
<small>Written on {{$article->created_at}}</small>
@endsection