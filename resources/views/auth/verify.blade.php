@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Overte svoju emailovú adresu') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('Nový overovací odkaz bol odoslaný na vašu emailovú adresu.') }}
                        </div>
                    @endif

                        {{ __('Pred pokračovaním skontrolujte svoj email pre overovací odkaz.') }}
                        {{ __('Ak ste nedostali email') }},
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('kliknite sem pre vyžiadanie ďalšieho') }}</button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
