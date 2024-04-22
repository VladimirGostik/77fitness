@extends('layouts.app')

@section('content')
<div class="container">
  <h1>Create Reservation</h1>
  <a href="/home" class="btn btn-default">Go back</a>

  @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if ($errors->any())
    <div class="alert alert-danger">
      <ul>
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div id="regular-reservation-fields" style="padding: 20px; border: 1px solid #ccc; border-radius: 10px; margin-top: 20px;">
    <form method="POST" action="{{ route('reservations.store') }}">
      @csrf

      <label for="client_id">Client:</label>
      <select class="form-select" aria-label="Select Client" name="client_id" id="client_id">
        <option value="">Select Client</option>
        @if (isset($clients))
          @foreach ($clients as $client)
            <option value="{{ $client->user_id }}">
              {{ $client->user->first_name }} {{ $client->user->last_name }}
            </option>
          @endforeach
        @else
          <option disabled>Clients not loaded</option>
        @endif
      </select>

      <label for="start_time">Start Time:</label>
      <input type="time" name="start_time" required class="form-control">

      <label for="end_time">End Time:</label>
      <input type="time" name="end_time" required class="form-control">

      <label for="reservation_date">Date:</label>
      <input type="date" name="reservation_date" required class="form-control">

      <div class="form-group">
        <label for="reservation_price">Reservation Price</label>
        <input type="number" name="reservation_price" id="reservation_price" class="form-control" step="0.01" value="{{ $sessionPrice }}" required>
      </div>

      <button type="submit" class="btn btn-primary mt-3">Create Regular Reservation</button>
    </form>
  </div>

  @include('partials._group_reservation_form')

</div>

<style>
  .container {
    text-align: center; /* Center align the content */
  }

  #regular-reservation-fields, #group-reservation-fields {
    margin: 0 auto; /* Center the fields */
    max-width: 400px; /* Adjust the maximum width as needed */
    background-color: #f9f9f9; /* Light background color */
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 10px;
    margin-top: 20px;
  }
</style>
@endsection
