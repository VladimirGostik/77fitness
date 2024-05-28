@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card bg-dark">
                <div class="card-header bg-dark text-white text-center">
                    <h2 class="mb-0">{{ __('Registrácia') }}</h2>
                </div>

                <div class="card-body bg-dark text-white">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group row mb-3">
                            <label for="first_name" class="col-md-4 col-form-label text-md-end">{{ __('Meno') }}</label>
                            <div class="col-md-6">
                                <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" required autocomplete="first_name" autofocus>
                                @error('first_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="last_name" class="col-md-4 col-form-label text-md-end">{{ __('Priezvisko') }}</label>
                            <div class="col-md-6">
                                <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" required autocomplete="last_name">
                                @error('last_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="phone_number" class="col-md-4 col-form-label text-md-end">{{ __('Telefónne číslo') }}</label>
                            <div class="col-md-6">
                                <input id="phone_number" type="text" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number" value="{{ old('phone_number') }}" autocomplete="phone_number">
                                @error('phone_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Emailová adresa') }}</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Heslo') }}</label>
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Potvrdiť heslo') }}</label>
                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="receive_notifications" class="col-md-4 col-form-label text-md-end">{{ __('Prijímať notifikácie') }}</label>
                            <div class="col-md-6">
                                <select id="receive_notifications" class="form-control @error('receive_notifications') is-invalid @enderror" name="receive_notifications" required>
                                    <option value="1" {{ old('receive_notifications') == 1 ? 'selected' : '' }}>Áno</option>
                                    <option value="0" {{ old('receive_notifications') == 0 ? 'selected' : '' }}>Nie</option>
                                </select>
                                @error('receive_notifications')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4 d-flex justify-content-center">
                                <button type="submit" class="btn btn-primary px-5 py-2">
                                    {{ __('Registrácia') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
    .card {
        background-color: #141619;
        background-repeat: no-repeat;
        background-size: cover;
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
    .invalid-feedback {
        color: #e3342f;
    }
</style>
