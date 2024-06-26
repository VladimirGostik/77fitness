<!-- resources/views/trainers/create.blade.php -->

@extends('layouts.app')

@section('title', 'Vytvoriť Trénera')

@section('content')
    <div class="container mt-5">
        <h1 class="text-white text-center mb-4">Vytvoriť Trénera</h1>
        <a href="/trainers" class="btn btn-outline-secondary mb-4">Späť</a>

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
                <form method="POST" action="{{ route('trainers.store') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- Informácie o používateľovi -->
                    <div class="form-group mb-3">
                        <label for="first_name" class="form-label">Meno</label>
                        <input type="text" name="first_name" id="first_name" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="last_name" class="form-label">Priezvisko</label>
                        <input type="text" name="last_name" id="last_name" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="phone_number" class="form-label">Telefónne číslo</label>
                        <input type="text" name="phone_number" id="phone_number" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="password" class="form-label">Heslo</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>

                    <div class="form-group mb-3 form-check">
                        <input type="checkbox" name="receive_notifications" id="receive_notifications" class="form-check-input" value="1">
                        <label for="receive_notifications" class="form-check-label">Dostávať notifikácie</label>
                    </div>

                    <!-- Informácie o trénerovi -->
                    <div class="form-group mb-3">
                        <label for="specialization" class="form-label">Špecializácia</label>
                        <input type="text" name="specialization" id="specialization" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="description" class="form-label">Popis</label>
                        <textarea name="description" id="description" class="form-control" required></textarea>
                    </div>

                    <div class="form-group mb-3">
                        <label for="experience" class="form-label">Skúsenosti</label>
                        <input type="text" name="experience" id="experience" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="session_price" class="form-label">Cena za tréning</label>
                        <input type="text" name="session_price" id="session_price" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="profile_photo" class="form-label">Profilová fotografia</label>
                        <div class="custom-file">
                            <input type="file" name="profile_photo" id="profile_photo" class="custom-file-input">
                            <label class="custom-file-label" for="profile_photo">Vybrať súbor</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">Vytvoriť Trénera</button>
                </form>
            </div>
        </div>
    </div>

    <style>
        .form-label {
            color: #adb5bd;
            font-size: 1rem; /* Upravená veľkosť písma */
        }
        .form-control, .custom-file-input, .custom-file-label {
            background-color: #495057;
            color: #fff;
            border: 1px solid #ced4da;
            font-size: 0.875rem; /* Upravená veľkosť písma */
        }
        .form-control:focus, .custom-file-input:focus, .custom-file-label:focus {
            background-color: #495057;
            color: #fff;
            border-color: #80bdff;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
            font-size: 0.875rem; /* Upravená veľkosť písma */
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
    </style>
@endsection
