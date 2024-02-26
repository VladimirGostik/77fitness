@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    @if(Auth::user()->role === 1)
                        {{-- Client Section --}}
                        @include('profile.client')
                    @elseif(Auth::user()->role === 2)
                        {{-- Trainer Section --}}
                        @include('profile.trainer')
                    @elseif(Auth::user()->role === 3)
                        {{-- Admin Section --}}
                        @include('profile.admin')
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection