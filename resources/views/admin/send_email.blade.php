@extends('layouts.app')

@section('content')
<div class="container mt-5">
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

    <h2 class="text-white text-center mb-4">Send Email</h2>

    <div class="card bg-dark text-white shadow-lg border-0 rounded-lg p-4">
        <div class="card-body">
            <div class="form-group mb-4 text-center">
                <button type="button" id="send-all-users" class="btn btn-outline-primary mr-2 mb-2">Send to All Users</button>
                <button type="button" id="send-to-trainers" class="btn btn-outline-primary mr-2 mb-2">Send to Trainers</button>
                <button type="button" id="send-to-clients" class="btn btn-outline-primary mr-2 mb-2">Send to Clients</button>
                <button type="button" id="send-to-specific-users" class="btn btn-outline-primary mr-2 mb-2">Send to One user...</button>
            </div>

            <form id="email-form" action="{{ route('admin.sendEmail') }}" method="post">
                @csrf
                <div class="form-group mb-3">
                    <label for="recipient" class="form-label">Recipient:</label>
                    <input id="recipient" name="recipient" class="form-control" readonly> <!-- Hidden input to store recipient type -->
                </div>
                <div class="form-group mb-3">
                    <label for="subject" class="form-label">Subject:</label>
                    <input type="text" name="subject" id="subject" class="form-control" required>
                </div>
                <div class="form-group mb-3">
                    <label for="content" class="form-label">Content:</label>
                    <textarea name="content" id="content" class="form-control" rows="5" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Send Email</button>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const users = {!! json_encode($users) !!};

        document.getElementById('send-all-users').addEventListener('click', function() {
            document.getElementById('recipient').value = 'All Users';
            document.getElementById('recipient').readOnly = true;
        });

        document.getElementById('send-to-trainers').addEventListener('click', function() {
            document.getElementById('recipient').value = 'Trainers';
            document.getElementById('recipient').readOnly = true;
        });

        document.getElementById('send-to-clients').addEventListener('click', function() {
            document.getElementById('recipient').value = 'Clients';
            document.getElementById('recipient').readOnly = true;
        });

        document.getElementById('send-to-specific-users').addEventListener('click', function() {
            document.getElementById('recipient').value = '';
            document.getElementById('recipient').readOnly = false;
        });
    });
</script>

<style>
    .form-label {
        color: #adb5bd;
    }

    .form-control, .btn-outline-primary {
        background-color: #495057;
        color: #fff;
        border: 1px solid #ced4da;
    }

    .form-control:focus, .btn-outline-primary:focus {
        background-color: #495057;
        color: #fff;
        border-color: #80bdff;
    }

    .btn-outline-primary:hover {
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
