@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-center text-white mb-4 center">Vitaj {{ Auth::user()->first_name }}, toto je tvoja profilová stránka Trénera!</h1>
        <div class="row mt-4">

            <div class="col-md-4 mb-4">
                <div class="card bg-dark text-white text-center shadow-sm">
                    <div class="card-body">
                        <i class="fas fa-envelope fa-2x mb-3"></i>
                        <h5 class="card-title"> Upraviť profil</h5>
                        <a href="{{ route('trainers.edit', ['trainer' => Auth::user()->trainer->user_id])}}" class="btn btn-primary">Choď</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card bg-dark text-white text-center shadow-sm">
                    <div class="card-body">
                        <i class="fas fa-envelope fa-2x mb-3"></i>
                        <h5 class="card-title"> Vytvoriť článok</h5>
                        <a href="{{ route('articles.create') }}" class="btn btn-primary">Choď</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card bg-dark text-white text-center shadow-sm">
                    <div class="card-body">
                        <i class="fas fa-envelope fa-2x mb-3"></i>
                        <h5 class="card-title"> Upraviť články</h5>
                        <a href="{{ route('articles.index') }}" class="btn btn-primary">Choď</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card bg-dark text-white text-center shadow-sm">
                    <div class="card-body">
                        <i class="fas fa-envelope fa-2x mb-3"></i>
                        <h5 class="card-title">Vytvoriť rezerváciu</h5>
                        <a href="{{ route('reservations.create') }}" class="btn btn-primary">Choď</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card bg-dark text-white text-center shadow-sm">
                    <div class="card-body">
                        <i class="fas fa-envelope fa-2x mb-3"></i>
                        <h5 class="card-title">Všetky rezervácie</h5>
                        <a href="{{ route('reservations.index') }}" class="btn btn-primary">Choď</a>
                    </div>
                </div>
            </div>
      {{-- 
            <a href="{{ route('group-reservations.create') }}">
                <i class="fas fa-users"></i> Vytvoriť skupinovú rezerváciu
            </a> <br>
--}}
            <!-- Pridajte ďalšie akcie podľa potreby -->
        </div>
    </div>

    <style>
  
        h1 {
            color: #ffffff;
        }
    
        .card {
            background-color: #343a40;
            border: none;
            border-radius: 10px;
        }
    
        .card-body i {
            color: #007bff6b;
        }
    
        .card-body .btn {
            margin-top: 20px;
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
