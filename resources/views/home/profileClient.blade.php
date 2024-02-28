@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                </div>
                <h1>Vitaj na stranke 77fitness... TOTO je home page client </h1>
                @if($articles && count($articles) > 0)
                <table class="table table-striped">
                    <tr>
                        <th>Title</th>
                        <th></th>
                        <th></th>

                    </tr>
                    @foreach($articles as $article)
                        <tr>
                            <th>{{$article->title}}</th>
                            <th><a href="/articles/{{$article->id}}/edit" class="btn btn-default">Edit</a></th>
                            <th></th>

                        </tr>
                    @endforeach
                </table>
                @else
                <p>You have no articles.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection