@extends('layouts.app')

@section('content')
<div class="mt-5">
    @if($errors->any())
        <div class="col-12">
            @foreach($errors->all() as $error)
            <div class="alert alert-danger">{{$error}}</div>
            @endforeach
        </div>
    @endif

    @if(session()->has('error'))
            <div class="alert alert-danger">{{session('error')}}

            </div>
    @endif

    @if(session('success'))
            <div class="alert alert-success">{{session('success')}}

            </div>
    @endif
</div>

    <div class="container">
        <h2>Send Email</h2>
        <div class="form-group">
            <button type="button" id="send-all-users" class="btn btn-primary mr-2">Send to All Users</button>
            <button type="button" id="send-to-trainers" class="btn btn-primary mr-2">Send to Trainers</button>
            <button type="button" id="send-to-clients" class="btn btn-primary mr-2">Send to Clients</button>
            <button type="button" id="send-to-specific-users" class="btn btn-primary mr-2">Send to One user...</button>

        </div>

        <form id="email-form" action="{{ route('admin.sendEmail') }}" method="post">
            @csrf
            <div>                
                <label for="subject">Recepient:</label>
                <input id="recipient" name="recipient" class="form-control"> <!-- Hidden input to store recipient type -->
            </div>
            <div class="form-group">
                <label for="subject">Subject:</label>
                <input type="text" name="subject" id="subject" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="content">Content:</label>
                <textarea name="content" id="content" class="form-control" rows="5" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Send Email</button>
        </form>
    </div>
    <script>
      document.addEventListener("DOMContentLoaded", function() {
        const users = {!! json_encode($users) !!};

            document.getElementById('send-all-users').addEventListener('click', function() {
            // Send email to all users
            document.getElementById('recipient').value = 'All Users';
            document.getElementById('recipient').readOnly = true; // Disable input
            });

            document.getElementById('send-to-trainers').addEventListener('click', function() {

            const trainers = users.filter(user => user.role === 2);
            document.getElementById('recipient').value = 'Trainers';
            document.getElementById('recipient').readOnly = true; // Disable input
            });

            document.getElementById('send-to-clients').addEventListener('click', function() {
            const clients = users.filter(user => user.role === 1);
            document.getElementById('recipient').value = 'Clients';
            document.getElementById('recipient').readOnly = true; // Disable input

            });

            document.getElementById('send-to-specific-users').addEventListener('click', function() {
            // Clear recipient field and enable input
            document.getElementById('recipient').value = '';
            document.getElementById('recipient').readOnly = false;

            });


    });

    </script>
@endsection
