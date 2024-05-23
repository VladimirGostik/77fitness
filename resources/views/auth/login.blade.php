@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-lg bg-dark text-white">
                <div class="card-header bg-dark text-white text-center">
                    <h2 class="mb-0">{{ __('Login') }}</h2>
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="email" class="col-form-label">{{ __('Email Address') }}</label>
                            <div>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="password" class="col-form-label">{{ __('Password') }}</label>
                            <div>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>
                        </div>

                        <div class="form-group mb-3 text-center">
                            <div class="col-md-12">
                                <p>If you are not yet registered, please <a href="{{ route('register') }}" class="text-primary">register here</a>.</p>
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link text-primary" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card-header {
        background-color: #343a40;
    }
    .card-body {
        background-color: #343a40;
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

    h2.text-center {
        font-weight: bold;
        color: #ffffff;    
    }

    h1.text-center {
        color: #ffffff;    
        font-weight: bold;

    }
</style>
@endsection
