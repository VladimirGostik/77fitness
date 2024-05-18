@extends('layouts.app')
@section('title','77fitness')
@section('content')

<h2>Recent Articles</h2>
    <div class="row">
        @if (count($articles) > 0)
            @foreach ($articles as $article)
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">{{ $article->title }}</h5>
                            <p class="card-text">{{ $article->content }}</p>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('articles.show', ['article' => $article->id]) }}" class="btn btn-primary">Read More</a>
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="col-md-12">
                <a href="{{ route('articles.index') }}" class="btn btn-outline-primary">See More Articles</a>
            </div>
        @else
            <p>No articles found.</p>
        @endif
    </div>

    <h2 class="mt-5">Available Trainers</h2>
    <div class="row">
        @if (count($trainers) > 0)
            @foreach ($trainers as $trainer)
                <div class="col-md-3 mb-4">
                    <div class="card">
                        @if($trainer->profile_photo)
                            <img src="{{ asset('storage/profile_photos/' . $trainer->profile_photo) }}" class="card-img-top" alt="Trainer Photo">
                        @else
                            <img src="{{ asset('images/default_trainer_photo.jpg') }}" class="card-img-top" alt="Default Trainer Photo">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $trainer->user->first_name }} {{ $trainer->user->last_name }}</h5>
                            <p class="card-text">{{ $trainer->specialization }}</p>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('trainer.profile', ['trainer' => $trainer->user_id]) }}" class="btn btn-primary">View Profile</a>
                        </div>
                        
                    </div>
                </div>
            @endforeach
            <div class="col-md-12">
                <a href="/trainers" class="btn btn-outline-primary">See More Trainers</a>
            </div>
        @else
            <p>No trainers available.</p>
        @endif
    </div>
    <a href="{{ route('home')}}" class="btn btn-primary">Make a Reservation</a>


@endsection
