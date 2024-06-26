@extends('layouts.app')

@section('title', 'Nabíjanie kreditu')

@section('content')

<div class="container mt-5">
  <h1 class="text-center text-white mb-4">Nabíjanie kreditu</h1>

  @if (session()->has('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  @if (session()->has('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  <div class="card bg-dark text-white shadow-lg border-0 rounded-lg p-4">
    <div class="card-body">
      <form method="POST" action="{{ route('payments.chargeAdmin') }}">
        @csrf

        <div class="form-group mb-3">
          <label for="user_id" class="form-label">Užívateľ:</label>
          <select id="user_id" name="user_id" class="form-control" required>
            @foreach($clients as $client)
              <option value="{{ $client->user_id }}">{{ $client->user->first_name }}</option>
            @endforeach
          </select>
        </div>

        <div class="form-group mb-3">
          <label for="amount" class="form-label">Suma:</label>
          <input type="number" id="amount" name="amount" min="1" step="1" class="form-control" required>
        </div>

        <input type="hidden" name="external_transaction_id" value="">
        <input type="hidden" name="client_id" id="client_id">
        <input type="hidden" name="admin_id" value="{{ Auth::user()->id }}">
        <input type="hidden" name="currency" value="EUR">
        <input type="hidden" name="payment_method" value="cash">
        <input type="hidden" name="payment_status" value="PAID">

        <button type="button" class="btn btn-outline-primary btn-block" data-bs-toggle="modal" data-bs-target="#confirmationModal">Nabite kredit</button>

        <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content bg-dark text-white">
              <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Potvrďte nabitie kreditu</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <p>Ste si istý, že chcete nabiť kredit užívateľovi <span id="selectedUserName"></span> o sumu <span id="selectedAmount"></span> €?</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zrušiť</button>
                <button type="submit" class="btn btn-primary">Potvrdiť</button>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    const submitButton = document.querySelector('.btn-outline-primary');
    const selectedUserNameSpan = document.getElementById('selectedUserName');
    const selectedAmountSpan = document.getElementById('selectedAmount');

    submitButton.addEventListener('click', function () {
      const selectedUserId = document.getElementById('user_id').value;
      const selectedUser = document.querySelector(`option[value="${selectedUserId}"]`).textContent;
      const selectedAmount = document.getElementById('amount').value;

      selectedUserNameSpan.textContent = selectedUser;
      selectedAmountSpan.textContent = selectedAmount;
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
  .btn-outline-primary {
    color: #ffffff;
    border-color: #ffffff;
  }
  .btn-outline-primary:hover {
    color: #000000;
    background-color: #ffffff;
    border-color: #ffffff;
  }
  .btn-close-white {
    filter: invert(1);
  }
  .alert {
    background-color: #343a40;
    color: #ffffff;
    border: none;
  }
  .card {
    border-radius: 15px;
  }
  .modal-content {
    border-radius: 15px;
  }
</style>

@endsection
