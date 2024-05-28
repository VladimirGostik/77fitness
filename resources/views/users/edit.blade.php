@extends('layouts.app')

@section('title', 'Upraviť Používateľa')

@section('content')
    <div class="container mt-5">
        <h1 class="text-white text-center mb-4">Upraviť Používateľa</h1>
        <a href="{{ route('home') }}" class="btn btn-outline-secondary mb-4 btn-sm">Späť</a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card bg-dark text-white shadow-lg border-0 rounded-lg">
            <div class="card-body p-3">
                <form action="{{ route('users.update', ['id' => $user->id]) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group mb-3">
                        <label for="first_name" class="form-label h6">Meno</label>
                        <input type="text" class="form-control form-control-sm" id="first_name" name="first_name" value="{{ $user->first_name }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="last_name" class="form-label h6">Priezvisko</label>
                        <input type="text" class="form-control form-control-sm" id="last_name" name="last_name" value="{{ $user->last_name }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="email" class="form-label h6">Email</label>
                        <input type="email" class="form-control form-control-sm" id="email" name="email" value="{{ $user->email }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="phone_number" class="form-label h6">Telefónne číslo</label>
                        <input type="text" class="form-control form-control-sm" id="phone_number" name="phone_number" value="{{ $user->phone_number }}" required>
                    </div>

                    <div class="form-group mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="receive_notifications" name="receive_notifications" value="1" {{ $user->receive_notifications ? 'checked' : '' }}>
                        <label class="form-check-label" for="receive_notifications">Dostávať notifikácie</label>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <button type="submit" class="btn btn-primary btn-sm">Upraviť Používateľa</button>
                        <button type="button" class="btn btn-link text-primary btn-sm" data-bs-toggle="modal" data-bs-target="#changePasswordModal">Zmeniť Heslo</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title" id="changePasswordModalLabel">Zmeniť Heslo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body bg-dark text-white">
                        <div class="form-group mb-2">
                            <label for="current_password" class="form-label h6">Aktuálne Heslo</label>
                            <input type="password" class="form-control form-control-sm" id="current_password" name="current_password" autocomplete="current-password">
                        </div>
                        <div class="form-group mb-2">
                            <label for="new_password" class="form-label h6">Nové Heslo</label>
                            <input type="password" class="form-control form-control-sm" id="new_password" name="new_password" minlength="8" autocomplete="new-password">
                        </div>
                        <div class="form-group mb-2">
                            <label for="new_password_confirmation" class="form-label h6">Potvrďte Nové Heslo</label>
                            <input type="password" class="form-control form-control-sm" id="new_password_confirmation" name="new_password_confirmation" minlength="8" autocomplete="new-password">
                        </div>
                    </div>
                    <div class="modal-footer bg-dark text-white">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Zatvoriť</button>
                        <button type="button" class="btn btn-primary btn-sm" id="changePasswordButton">Zmeniť Heslo</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                    alert("Nové heslá sa nezhodujú!");
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
                        alert("Heslo bolo úspešne zmenené!");
                        // Optionally, close the modal or redirect to a different page
                    } else {
                        alert("Chyba pri zmene hesla: " + data.message);
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("Vyskytla sa chyba. Skúste to prosím neskôr.");
                });
            });
        });
    </script>

    <style>
        .form-label {
            color: #adb5bd;
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
        .btn-link {
            color: #007bff;
            text-decoration: none;
        }
        .btn-link:hover {
            color: #0056b3;
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
        .modal-content {
            background-color: #343a40;
            color: #ffffff;
        }
        .modal-header, .modal-footer {
            border: none;
        }
    </style>
@endsection
