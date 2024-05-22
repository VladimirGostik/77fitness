@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-center">Welcome, {{ Auth::user()->first_name }}, this is an admin page!</h1>
        <div class="row mt-4">
            <div class="col-md-4 mb-4">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <i class="fas fa-user-edit fa-2x mb-3"></i>
                        <h5 class="card-title">Edit User</h5>
                        <a href="{{ route('users.index') }}" class="btn btn-primary">Go</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <i class="fas fa-list fa-2x mb-3"></i>
                        <h5 class="card-title">Edit Articles</h5>
                        <a href="{{ route('articles.index') }}" class="btn btn-primary">Go</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <i class="fas fa-plus-circle fa-2x mb-3"></i>
                        <h5 class="card-title">Create Article</h5>
                        <a href="{{ route('articles.create') }}" class="btn btn-primary">Go</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <i class="fas fa-plus-circle fa-2x mb-3"></i>
                        <h5 class="card-title">Create Trainer</h5>
                        <a href="{{ route('trainers.create') }}" class="btn btn-primary">Go</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <i class="fas fa-user fa-2x mb-3"></i>
                        <h5 class="card-title">Edit Trainers</h5>
                        <a href="{{ route('trainers.index') }}" class="btn btn-primary">Go</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <i class="fas fa-envelope fa-2x mb-3"></i>
                        <h5 class="card-title">Send Email</h5>
                        <a href="{{ route('admin.sendEmail') }}" class="btn btn-primary">Go</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <i class="fas fa-euro-sign fa-2x mb-3"></i>
                        <h5 class="card-title">Manage User Credit</h5>
                        <a href="{{ route('payments.chargeCredit') }}" class="btn btn-primary">Go</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<style>
    .card-body i {
        color: #007bff;
    }
    .card-body .btn {
        margin-top: 10px;
    }
</style>
