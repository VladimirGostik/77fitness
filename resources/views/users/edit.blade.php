@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
    <h1>Edit User</h1>
    <a href="{{ route('home') }}" class="btn btn-default">Go back</a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('users.update', ['id' => $user->id]) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="first_name" class="form-label">First Name</label>
            <input type="text" class="form-control" id="first_name" name="first_name" value="{{ $user->first_name }}" required>
        </div>

        <div class="mb-3">
            <label for="last_name" class="form-label">Last Name</label>
            <input type="text" class="form-control" id="last_name" name="last_name" value="{{ $user->last_name }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
        </div>

        <div class="mb-3">
            <label for="phone_number" class="form-label">Phone Number</label>
            <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ $user->phone_number }}" required>
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="receive_notifications" name="receive_notifications" value="1" {{ $user->receive_notifications ? 'checked' : '' }}>
            <label class="form-check-label" for="receive_notifications">Receive Notifications</label>
        </div>

        <button type="submit" class="btn btn-primary">Update User</button>

        <button type="button" class="btn btn-link" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
            Change Password
        </button>

        <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="changePasswordModalLabel">Change Password</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" autocomplete="current-password">
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" minlength="8" autocomplete="new-password">
                        </div>
                        <div class="mb-3">
                            <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" minlength="8" autocomplete="new-password">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="changePasswordButton">Change Password</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
          const changePasswordButton = document.getElementById("changePasswordButton");
        
          changePasswordButton.addEventListener("click", function(event) {
          event.preventDefault(); // Prevent default form submission

          const currentPassword = document.getElementById("current_password").value;
          const newPassword = document.getElementById("new_password").value;
          const newPasswordConfirmation = document.getElementById("new_password_confirmation").value;

          // Basic validation (more can be added)
          if (newPassword !== newPasswordConfirmation) {
              alert("New passwords do not match!");
              document.getElementById("new_password_confirmation").focus(); // Focus on the confirmation field
              return;
          }
        
            // Send Ajax request to change password
            fetch('/users/{{ $user->id }}/password', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Add CSRF token for security
              },
              body: JSON.stringify({
                current_password: currentPassword,
                new_password: newPassword
              })
            })
            .then(response => response.json())
            .then(data => {
              if (data.success) {
                alert("Password changed successfully!");
                // Optionally, close the modal or redirect to a different page
              } else {
                alert("Error changing password: " + data.message);
              }
            })
            .catch(error => {
              console.error("Error:", error);
              alert("An error occurred. Please try again later.");
            });
          });
        });
        </script>
        
@endsection
