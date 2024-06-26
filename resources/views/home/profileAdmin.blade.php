@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center text-white mb-4 center">Vitajte, {{ Auth::user()->first_name }}, toto je admin stránka!</h1>
        <div class="row mt-4">
            <div class="col-md-4 mb-4">
                <div class="card bg-dark text-white text-center shadow-sm">
                    <div class="card-body">
                        <i class="fas fa-user-edit fa-2x mb-3"></i>
                        <h5 class="card-title">Upraviť používateľa</h5>
                        <a href="{{ route('users.index') }}" class="btn btn-primary">Choď</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card bg-dark text-white text-center shadow-sm">
                    <div class="card-body">
                        <i class="fas fa-list fa-2x mb-3"></i>
                        <h5 class="card-title">Upraviť články</h5>
                        <a href="{{ route('articles.index') }}" class="btn btn-primary">Choď</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card bg-dark text-white text-center shadow-sm">
                    <div class="card-body">
                        <i class="fas fa-plus-circle fa-2x mb-3"></i>
                        <h5 class="card-title">Vytvoriť článok</h5>
                        <a href="{{ route('articles.create') }}" class="btn btn-primary">Choď</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card bg-dark text-white text-center shadow-sm">
                    <div class="card-body">
                        <i class="fas fa-plus-circle fa-2x mb-3"></i>
                        <h5 class="card-title">Vytvoriť trénera</h5>
                        <a href="{{ route('trainers.create') }}" class="btn btn-primary">Choď</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card bg-dark text-white text-center shadow-sm">
                    <div class="card-body">
                        <i class="fas fa-plus-circle fa-2x mb-3"></i>
                        <h5 class="card-title">Vytvoriť používateľa</h5>
                        <a href="{{ route('users.create') }}" class="btn btn-primary">Choď</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card bg-dark text-white text-center shadow-sm">
                    <div class="card-body">
                        <i class="fas fa-user fa-2x mb-3"></i>
                        <h5 class="card-title">Upraviť trénerov</h5>
                        <a href="{{ route('trainers.index') }}" class="btn btn-primary">Choď</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card bg-dark text-white text-center shadow-sm">
                    <div class="card-body">
                        <i class="fas fa-envelope fa-2x mb-3"></i>
                        <h5 class="card-title">Odoslať email</h5>
                        <a href="{{ route('admin.sendEmail') }}" class="btn btn-primary">Choď</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card bg-dark text-white text-center shadow-sm">
                    <div class="card-body">
                        <i class="fas fa-euro-sign fa-2x mb-3"></i>
                        <h5 class="card-title">Spravovať kredit používateľa</h5>
                        <a href="{{ route('payments.chargeCredit') }}" class="btn btn-primary">Choď</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

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
