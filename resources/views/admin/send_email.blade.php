@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Send Email</h2>
        <div class="form-group">
            <button type="button" id="send-all-users" class="btn btn-primary mr-2">Send to All Users</button>
            <button type="button" id="send-to-trainers" class="btn btn-primary mr-2">Send to Trainers</button>
            <button type="button" id="send-to-specific-users" class="btn btn-primary mr-2">Send to Specific Users</button>
            <button type="button" id="show-specific-users" class="btn btn-primary">Show Specific Users</button>
        </div>

        <form id="email-form" action="{{ route('admin.sendEmail') }}" method="post" style="display: none;">
            @csrf
            <div id="manual-recipients" class="form-group" style="display: none;">
                <label for="manual-recipients-input">Enter Recipients:</label>
                <input type="text" name="manual-recipients-input" id="manual-recipients-input" class="form-control">
            </div>
            <div id="selected-recipients" class="form-group">
                <!-- Selected recipients will be displayed here -->
            </div>
            <div class="form-group">
                <label for="subject">Subject:</label>
                <input type="text" name="subject" id="subject" class="form-control">
            </div>
            <div class="form-group">
                <label for="content">Content:</label>
                <textarea name="content" id="content" class="form-control" rows="5"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Send Email</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('send-all-users').addEventListener('click', function () {
                document.getElementById('email-form').style.display = 'block';
                document.getElementById('manual-recipients').style.display = 'none';
                document.getElementById('selected-recipients').innerHTML = '<p>Sending email to all users...</p>';
            });

            document.getElementById('send-to-trainers').addEventListener('click', function () {
                document.getElementById('email-form').style.display = 'block';
                document.getElementById('manual-recipients').style.display = 'none';
                document.getElementById('selected-recipients').innerHTML = '<p>Sending email to trainers only...</p>';
            });

            document.getElementById('send-to-specific-users').addEventListener('click', function () {
                document.getElementById('email-form').style.display = 'block';
                document.getElementById('manual-recipients').style.display = 'none';
                document.getElementById('selected-recipients').innerHTML = '';
            });

            document.getElementById('show-specific-users').addEventListener('click', function () {
                document.getElementById('email-form').style.display = 'block';
                document.getElementById('manual-recipients').style.display = 'block';
                document.getElementById('selected-recipients').innerHTML = '';
            });

            document.getElementById('manual-recipients-input').addEventListener('change', function () {
                var selectedRecipientsDiv = document.getElementById('selected-recipients');
                var recipientValue = this.value.trim();
                if (recipientValue !== '') {
                    var newRecipientDiv = document.createElement('div');
                    newRecipientDiv.textContent = recipientValue;
                    selectedRecipientsDiv.appendChild(newRecipientDiv);
                    this.value = ''; // Clear the input field
                }
            });

            document.getElementById('manual-recipients-input').addEventListener('click', function () {
                
                var users = {!! json_encode($users) !!}; // Získajte údaje o používateľoch zo servera
                var dropdownMenu = document.createElement('select');
                dropdownMenu.className = 'form-control';

                // Iterate through users array and create option elements for each user
                for (var i = 0; i < users.length; i++) {
                    var option = document.createElement('option');
                    option.value = users[i].id;
                    option.textContent = users[i].email; // Predpokladám, že používateľ má vlastnosť name
                    dropdownMenu.appendChild(option);
                }

                // Clear any existing dropdown menu and append the new one
                var manualRecipientsInput = document.getElementById('manual-recipients-input');
                manualRecipientsInput.innerHTML = ''; // Clear existing content
                manualRecipientsInput.appendChild(dropdownMenu);
            });
        });
    </script>
@endsection