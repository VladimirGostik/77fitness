@extends('layout')

@section('content')
    <h1>
        Create Article
    </h1>
    
    {!! Form::open(['route' => 'articles.store', 'method' => 'POST']) !!}
        <div class="form-group">
            {{Form::label('title', 'Title')}}
            {{Form::text('title','',['class' => 'form-control', 'placeholder' => 'Title'])}}
        </div>
        <div class="form-group">
            {{Form::label('content', 'Content')}}
            {{Form::textarea('content','',['class' => 'form-control', 'placeholder' => 'Content'])}}
        </div>
        {{Form::submit('Submit',['class' => 'btn btn=primary'])}}
    {!! Form::close() !!}

@endsection
