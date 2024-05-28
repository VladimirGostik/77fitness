@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-white text-center">Upraviť Článok</h1>
        <a href="/home" class="btn btn-outline-secondary mb-3 btn-sm">Späť</a>

        <div class="mt-5">
            @if($errors->any())
                <div class="col-12">
                    @foreach($errors->all() as $error)
                        <div class="alert alert-danger">{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            @if(session()->has('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
        </div>
        
        <div class="card bg-dark text-white shadow-lg border-0 rounded-lg">
            <div class="card-body p-3">
                {!! Form::open(['route' => ['articles.update', $article->id], 'method' => 'POST','enctype' => 'multipart/form-data']) !!}
                    <div class="form-group mb-3">
                        {{ Form::label('title', 'Názov', ['class' => 'form-label h6']) }}
                        {{ Form::text('title', $article->title, ['class' => 'form-control form-control-sm', 'placeholder' => 'Názov']) }}
                    </div>
                    <div class="form-group mb-3">
                        {{ Form::label('content', 'Obsah', ['class' => 'form-label h6']) }}
                        {{ Form::textarea('content', $article->content, ['class' => 'form-control form-control-sm', 'placeholder' => 'Obsah']) }}
                    </div>
                    <div class="form-group mb-3">
                        {{ Form::label('cover_image', 'Obrázok', ['class' => 'form-label h6']) }}
                        <div class="custom-file">
                            {{ Form::file('cover_image', ['class' => 'custom-file-input', 'id' => 'cover_image']) }}
                            {{ Form::label('cover_image', 'Vyberte súbor', ['class' => 'custom-file-label']) }}
                        </div>
                    </div>
                    {{ Form::hidden('_method', 'PUT') }}
                    {{ Form::submit('Odoslať', ['class' => 'btn btn-primary btn-block btn-sm']) }}
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    <style>
        .form-label {
            color: #adb5bd;
        }
        .form-control {
            background-color: #495057;
            color: #fff;
            border: 1px solid #ced4da;
        }
        .form-control:focus {
            background-color: #495057;
            color: #fff;
            border-color: #80bdff;
        }
        .custom-file-input {
            background-color: #495057;
            color: #fff;
            border: 1px solid #ced4da;
        }
        .custom-file-input:focus {
            background-color: #495057;
            color: #fff;
            border-color: #80bdff;
        }
        .custom-file-label {
            background-color: #495057;
            color: #fff;
            border: 1px solid #ced4da;
            padding: 0.375rem 0.75rem;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .invalid-feedback {
            color: #e3342f;
        }
        .btn-outline-secondary {
            color: #ffffff;
            border-color: #ffffff;
        }
        .btn-outline-secondary:hover {
            color: #000000;
            background-color: #ffffff;
            border-color: #ffffff;
        }
    </style>
@endsection
