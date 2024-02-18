@extends('layouts.app')

@section('content')
    <h1>
        Edit Article
    </h1>
    <div class="mt-5">
        @if($errors->any())
            <div class="col-12">
                @foreach($errors->all() as $error)
                <div class="alert alert-danger">{{$error}}</div>
                @endforeach
            </div>
        @endif

        @if(session()->has('error'))
            <div class="alert alert-danger">{{session('error')}}</div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">{{session('success')}}</div>
        @endif
    </div>
    
    {!! Form::open(['route' => ['articles.update', $article->id], 'method' => 'POST','enctype' => 'multipart/form-data']) !!}
        <div class="form-group">
            {{Form::label('title', 'Title')}}
            {{Form::text('title', $article->title, ['class' => 'form-control', 'placeholder' => 'Title'])}}
        </div>
        <div class="form-group">
            {{Form::label('content', 'Content')}}
            {{Form::textarea('content', $article->content, ['class' => 'form-control', 'placeholder' => 'Content'])}}
        </div>

        <div class="form-group">
            {{Form::file('cover_image')}}
        </div>

        {{Form::hidden('_method', 'PUT')}}
        {{Form::submit('Submit', ['class' => 'btn btn-primary'])}}
    {!! Form::close() !!}
@endsection
