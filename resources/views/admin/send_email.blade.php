@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Send Email</h2>
        <div class="form-group">
            <button type="button" id="send-all-users" class="btn btn-primary mr-2">Send to All Users</button>
            <button type="button" id="send-to-trainers" class="btn btn-primary mr-2">Send to Trainers</button>
            <button type="button" id="send-to-specific-users" class="btn btn-primary mr-2">Send to Clients</button>
            <button class="btn btn-primary dropdown-toggle" type="button" id="show-specific-users-dropdown">Show Specific Users</button>
        </div>

        <form id="email-form" action="{{ route('admin.sendEmail') }}" method="post" style="display: block;">
            @csrf
            <div id="manual-recipients" class="form-group" style="display: none;">
                <label for="manual-recipients-input">Enter Recipients:</label>
                <input type="text" id="manual-recipients-input" class="form-control" placeholder="Search for users...">
                <div id="autocomplete-suggestions" class="input-group-append"></div>
            </div>
            <div id="selected-recipients" class="form-group">   

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
    <style>.dropdown-menu .dropdown-item {
      font-size: 16px;
      line-height: 1.5;
      padding: 5px 10px;
      background-color: #f5f5f5;
    }

    /* New CSS rule for positioning */
    #show-specific-users-dropdown .dropdown-menu {
      position: absolute;
      z-index: 1; /* Make sure the dropdown is above other elements */
    }
      </style>
      
      
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sendAllUsersBtn = document.getElementById('send-all-users');
            const sendToTrainersBtn = document.getElementById('send-to-trainers');
            const sendToSpecificUsersBtn = document.getElementById('send-to-specific-users');

            sendAllUsersBtn.addEventListener('click', function () {
                document.getElementById('email-form').style.display = 'block';
                document.getElementById('manual-recipients').style.display = 'none';
                document.getElementById('selected-recipients').innerHTML = '<p>Sending email to all users...</p>';
            });

            sendToTrainersBtn.addEventListener('click', function () {
                document.getElementById('email-form').style.display = 'block';
                document.getElementById('manual-recipients').style.display = 'none';
                document.getElementById('selected-recipients').innerHTML = '<p>Sending email to trainers only...</p>';
            });

            sendToSpecificUsersBtn.addEventListener('click', function () {
                document.getElementById('email-form').style.display = 'block';
                document.getElementById('manual-recipients').style.display = 'none';
                document.getElementById('selected-recipients').innerHTML  = '<p>Sending email to clients only...</p>';
            });

            // This function handles the selection of specific users
            function showSpecificUsers() {
                document.getElementById('email-form').style.display = 'block';
                document.getElementById('manual-recipients').style.display = 'block';
                document.getElementById('selected-recipients').innerHTML  = '';

                // Your logic to fetch users from the server goes here
                const users = {!! json_encode($users) !!};

                const inputField = document.getElementById('manual-recipients-input');
                const suggestionsContainer = document.getElementById('autocomplete-suggestions');
                const selectedUsersList = document.getElementById('selected-recipients');

                function handleInput(searchTerm) {
                    const suggestions = users.filter(user => user.email.toLowerCase().includes(searchTerm.toLowerCase()));
                    showSuggestions(suggestions);
                }

                function showSuggestions(suggestions) {
                    console.log("kokot");
                    suggestionsContainer.innerHTML = '';
                    const suggestionsList = document.createElement('ul');
                    suggestions.forEach(suggestion => {
                        console.log("kokot2");
                        const suggestionItem = document.createElement('li');
                        suggestionItem.textContent = suggestion.email;
                        suggestionItem.classList.add('dropdown-item');
                        suggestionItem.addEventListener('click', () => {
                            console.log("kokot3");
                            const selectedUserItem = document.createElement('li');
                            selectedUserItem.textContent = suggestion.email;
                            selectedUserItem.dataset.userId = suggestion.id; // Add user ID for potential form submission
                            selectedUserItem.classList.add('selected-recipient');
                            selectedUserItem.onclick = () => removeSelectedUser(selectedUserItem);
                            selectedUsersList.appendChild(selectedUserItem);
                            inputField.value = suggestion.email; // Set input field value to selected user's email
                            hideSuggestions(); // Hide suggestions after selection
        
                        });
                        suggestionsList.appendChild(suggestionItem);
                    });
                    suggestionsContainer.appendChild(suggestionsList);
                    suggestionsContainer.style.display = 'block';
                }

                function hideSuggestions() {
                    suggestionsContainer.style.display = 'none';
                }

                inputField.addEventListener('input', () => handleInput(inputField.value));

                document.addEventListener('click', (event) => {
                    if (!event.target.closest('#manual-recipients-input') && !event.target.closest('#autocomplete-suggestions')) {
                        hideSuggestions();
                    }
                });

                function removeSelectedUser(selectedUserItem) {
                    selectedUsersList.removeChild(selectedUserItem);
                }

                inputField.addEventListener('blur', hideSuggestions);
            }

            document.getElementById('show-specific-users-dropdown').addEventListener('click', showSpecificUsers);
        });
    </script>
@endsection
