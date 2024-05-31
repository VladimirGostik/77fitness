@extends('layouts.app')

@section('title', 'Vytvoriť Používateľa')

@section('content')
    <div class="container mt-5">
        <h1 class="text-white text-center mb-4">Vytvoriť Používateľa</h1>
        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary mb-4 btn-sm">Späť</a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card bg-dark text-white shadow-lg border-0 rounded-lg">
            <div class="card-body p-3">
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf

                    <div class="form-group mb-3">
                        <label for="first_name" class="form-label h6">Meno</label>
                        <input type="text" class="form-control form-control-sm" id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="last_name" class="form-label h6">Priezvisko</label>
                        <input type="text" class="form-control form-control-sm" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="email" class="form-label h6">Email</label>
                        <input type="email" class="form-control form-control-sm" id="email" name="email" value="{{ old('email') }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="password" class="form-label h6">Heslo</label>
                        <input type="password" class="form-control form-control-sm" id="password" name="password" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="phone_number" class="form-label h6">Telefónne číslo</label>
                        <input type="text" class="form-control form-control-sm" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" required>
                    </div>

                    <div class="form-group mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="receive_notifications" name="receive_notifications" value="1" {{ old('receive_notifications') ? 'checked' : '' }}>
                        <label class="form-check-label" for="receive_notifications">Dostávať notifikácie</label>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <button type="submit" class="btn btn-primary btn-sm">Vytvoriť Používateľa</button>
                    </div>
                </form>
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
    </style>
@endsection
