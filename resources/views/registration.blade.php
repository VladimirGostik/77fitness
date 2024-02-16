@extends('layout')
@section('title','Registration')
@section('content')
<div class="container">
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
    <form action="{{route('registration.post')}}" method="POST" class="ms-auto me-auto mt-3" style="width: 500px">
        @csrf
            <div class="mb-3">
                <label class="form-label">First name</label>
                <input type="firstname" class="form-control" name="firstname">
            </div>
            <div class="mb-3">
                <label class="form-label">Last name</label>
                <input type="lastname" class="form-control" name="lastname">
            </div>

            <div class="mb-3">
                 <label for="exampleInputEmail1" class="form-label">Email address</label>
                <input type="email" class="form-control" name="email">
            </div>

            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Password</label>
                <input type="password" class="form-control" name="password">
            </div>
        
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
@endsection