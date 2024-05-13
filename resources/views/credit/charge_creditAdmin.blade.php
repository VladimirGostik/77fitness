@extends('layouts.app')

@section('title', 'Nabíjanie kreditu')

@section('content')

<div class="container mt-5">
  <h1>Nabíjanie kreditu</h1>

  @if (session()->has('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  @if (session()->has('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  <form method="POST" action="{{ route('payments.chargeAdmin') }}">
    @csrf

    <div class="form-group mb-3">
      <label for="user_id">Užívateľ:</label>
      <select id="user_id" name="user_id" class="form-control" required>
        @foreach($clients as $client)
          <option value="{{ $client->user_id }}">{{ $client->user->first_name }}</option>
        @endforeach
      </select>
    </div>

    <div class="form-group mb-3">
      <label for="amount">Suma:</label>
      <input type="number" id="amount" name="amount" min="1" step="1" class="form-control" required>
    </div>

    <input type="hidden" name="external_transaction_id" value="">
    <input type="hidden" name="client_id" id="client_id">
    <input type="hidden" name="admin_id" value="{{ Auth::user()->id }}">
    <input type="hidden" name="currency" value="EUR">
    <input type="hidden" name="payment_method" value="cash">
    <input type="hidden" name="payment_status" value="PAID">

    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#confirmationModal">Nabite kredit</button>

    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="confirmationModalLabel">Potvrďte nabitie kreditu</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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

<script>
  // Assuming you have Bootstrap and jQuery enabled
  const submitButton = document.querySelector('.btn-primary');
  const selectedUserNameSpan = document.getElementById('selectedUserName');
  const selectedAmountSpan = document.getElementById('selectedAmount');

  submitButton.addEventListener('click', function () {
    const selectedUserId = document.getElementById('user_id').value;
    const selectedUser = document.querySelector(`option[value="${selectedUserId}"]`).textContent;
    const selectedAmount = document.getElementById('amount').value;

    selectedUserNameSpan.textContent = selectedUser;
    selectedAmountSpan.textContent = selectedAmount;
  });
</script>

@endsection
