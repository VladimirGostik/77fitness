@extends('layouts.app')

@section('title', 'Dobitie kreditu')
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
    <div class="alert alert-danger">{{session('error')}}</div>
  @endif

  @if(session()->has('success'))
    <div class="alert alert-success">{{session('success')}}</div>
  @endif
</div>

<div class="container d-flex justify-content-center">
  <div class="col-md-6"> <h1>Dobite si kredit</h1>

    <form method="POST" action="{{ route('payments.charge') }}">
      @csrf

      <div class="form-group mb-3">
        <label for="amount">Suma:</label>
        <div class="input-group">
          <input type="number" id="amount" name="amount" min="1" step="1" class="form-control" required>
          <span class="input-group-text">€</span>
        </div>
      </div>

      <div class="mb-3">
        <p class="text-muted">Alebo vyberte preddefinovanú sumu:</p>
        <div class="btn-group d-flex justify-content-center" role="group" aria-label="Predefined amounts">
          <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('amount').value = 25">25 €</button>
          <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('amount').value = 50">50 €</button>
          <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('amount').value = 100">100 €</button>
        </div>
      </div>

      <button type="submit" class="btn btn-primary d-block mx-auto">Dobite kredit</button>
    </form>
  </div>
</div>

@endsection
