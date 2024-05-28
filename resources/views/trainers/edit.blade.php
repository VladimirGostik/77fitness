@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-white text-center mb-4">Upraviť Trénera</h1>
        <a href="/trainers" class="btn btn-outline-secondary mb-4 btn-md">Späť</a>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card bg-dark text-white shadow-lg border-0 rounded-lg">
            <div class="card-body p-4">
                <form method="POST" enctype="multipart/form-data" action="{{ route('trainers.update', ['trainer' => $trainer->user_id]) }}">
                    @csrf
                    @method('PUT')

                    <!-- Detaily používateľa -->
                    <div class="form-group mb-4">
                        {{ Form::label('profile_photo', 'Nová Profilová Fotografia:', ['class' => 'form-label h5']) }}
                        <div class="custom-file">
                            {{ Form::file('profile_photo', ['class' => 'custom-file-input', 'id' => 'profile_photo']) }}
                            {{ Form::label('profile_photo', 'Vybrať súbor', ['class' => 'custom-file-label']) }}
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        {{ Form::label('first_name', 'Meno:', ['class' => 'form-label h5']) }}
                        {{ Form::text('first_name', $trainer->user->first_name, ['class' => 'form-control form-control-sm', 'required']) }}
                    </div>

                    <div class="form-group mb-4">
                        {{ Form::label('last_name', 'Priezvisko:', ['class' => 'form-label h5']) }}
                        {{ Form::text('last_name', $trainer->user->last_name, ['class' => 'form-control form-control-sm', 'required']) }}
                    </div>

                    <div class="form-group mb-4">
                        {{ Form::label('email', 'Email:', ['class' => 'form-label h5']) }}
                        {{ Form::email('email', $trainer->user->email, ['class' => 'form-control form-control-sm', 'required']) }}
                    </div>

                    <div class="form-group mb-4">
                        {{ Form::label('phone_number', 'Telefónne číslo:', ['class' => 'form-label h5']) }}
                        {{ Form::text('phone_number', $trainer->user->phone_number, ['class' => 'form-control form-control-sm', 'required']) }}
                    </div>

                    <!-- Detaily trénera -->
                    <div class="form-group mb-4">
                        {{ Form::label('specialization', 'Špecializácia:', ['class' => 'form-label h5']) }}
                        {{ Form::text('specialization', $trainer->specialization, ['class' => 'form-control form-control-sm', 'required']) }}
                    </div>

                    <div class="form-group mb-4">
                        {{ Form::label('description', 'Popis:', ['class' => 'form-label h5']) }}
                        {{ Form::textarea('description', $trainer->description, ['class' => 'form-control form-control-sm', 'required']) }}
                    </div>

                    <div class="form-group mb-4">
                        {{ Form::label('experience', 'Skúsenosti:', ['class' => 'form-label h5']) }}
                        {{ Form::textarea('experience', $trainer->experience, ['class' => 'form-control form-control-sm', 'required']) }}
                    </div>

                    <div class="form-group mb-4">
                        {{ Form::label('session_price', 'Cena za tréning:', ['class' => 'form-label h5']) }}
                        {{ Form::number('session_price', $trainer->session_price, ['class' => 'form-control form-control-sm', 'required']) }}
                    </div>

                    <div class="form-group mb-4">
                        {{ Form::label('gallery_photos', 'Fotografie galérie (voliteľné):', ['class' => 'form-label h5']) }}
                        <div class="custom-file">
                            {{ Form::file('gallery_photos[]', ['multiple', 'class' => 'custom-file-input', 'id' => 'gallery_photos']) }}
                            {{ Form::label('gallery_photos', 'Vybrať súbory', ['class' => 'custom-file-label']) }}
                        </div>

                        @if ($trainer->profilePhotos->count() > 0)
                            <h2 class="mt-3 h6">Fotografie Galérie</h2>
                            <div>
                                @foreach ($trainer->profilePhotos as $photo)
                                    <img src="{{ asset('storage/trainer_gallery_photos/' . $photo->filename) }}" alt="{{ $photo->filename }}" class="img-thumbnail" style="max-width: 100px; max-height: 100px; margin-right: 5px;">
                                @endforeach
                            </div>
                        @else
                            <p>Žiadne fotografie galérie nenájdené pre tohto trénera.</p>
                        @endif
                    </div>

                    {{ Form::submit('Aktualizovať Trénera', ['class' => 'btn btn-primary btn-block btn-md']) }}
                </form>
            </div>
        </div>
    </div>

    <style>
        .form-label {
            color: #adb5bd;
        }
        .form-control, .custom-file-input {
            background-color: #495057;
            color: #fff;
            border: 1px solid #ced4da;
        }
        .form-control:focus, .custom-file-input:focus {
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
        .btn-outline-secondary {
            color: #ffffff;
            border-color: #ffffff;
        }
        .btn-outline-secondary:hover {
            color: #000000;
            background-color: #ffffff;
            border-color: #ffffff;
        }
        .alert {
            background-color: #343a40;
            color: #ffffff;
            border: none;
        }
        .card {
            border-radius: 15px;
        }
    </style>
@endsection
