@extends('layouts.app')
@section('title','77fitness')
@section('content')

<div class="container mt-4">
    <h2 class="text-center mb-5">Recent Articles</h2>
    <div id="articleCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            @foreach ($articles as $index => $article)
                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                    <div class="card bg-dark text-white">
                        <div class="card-img-wrapper">
                            @if($article->cover_image)
                                <img src="{{ asset('storage/cover_images/' . $article->cover_image) }}" class="card-img article-img" alt="{{ $article->title }}">
                            @else
                                <img src="{{ asset('images/default_article_image.jpg') }}" class="card-img article-img" alt="Default Image">
                            @endif
                            <div class="card-img-overlay d-flex flex-column justify-content-end gradient-overlay">
                                <h5 class="card-title">{{ $article->title }}</h5>
                                <p class="card-text">{{ Str::limit($article->content, 100, '...') }}</p>
                                <a href="{{ route('articles.show', ['article' => $article->id]) }}" class="btn btn-primary btn-block">Read More</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#articleCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#articleCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
    
    <div class="text-center mt-4">
        <a href="{{ route('articles.index') }}" class="btn btn-outline-primary">See More Articles</a>
    </div>

    <h2 class="text-center mt-5 mb-5">Available Trainers</h2>
    <div id="trainerCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            @foreach ($trainers->chunk(3) as $chunk)
                <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                    <div class="row">
                        @foreach ($chunk as $trainer)
                            <div class="col-md-4">
                                <div class="card bg-dark text-white h-100">
                                    @if($trainer->profile_photo)
                                        <img src="{{ asset('storage/profile_photos/' . $trainer->profile_photo) }}" class="card-img-top img-fluid trainer-img" alt="Trainer Photo">
                                    @else
                                        <img src="{{ asset('images/default_trainer_photo.jpg') }}" class="card-img-top img-fluid trainer-img" alt="Default Trainer Photo">
                                    @endif
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $trainer->user->first_name }} {{ $trainer->user->last_name }}</h5>
                                        <p class="card-text">{{ $trainer->specialization }}</p>
                                    </div>
                                    <div class="card-footer">
                                        <a href="{{ route('trainer.profile', ['trainer' => $trainer->user_id]) }}" class="btn btn-primary btn-block">View Profile</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#trainerCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#trainerCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <div class="text-center mt-4">
        <a href="/trainers" class="btn btn-outline-primary">See More Trainers</a>
    </div>

    <div class="text-center mt-5">
        <a href="{{ route('home') }}" class="btn btn-success btn-lg">Make a Reservation</a>
    </div>
</div>

<style>
    .card-img-wrapper {
        position: relative;
        display: flex;
        height: 300px; /* Fixed height */
        overflow: hidden;
    }

    .article-img {
        width: 100%; /* Ensure image fills the width */
        height: 100%; /* Ensure image fills the height */
        object-fit: cover; /* Ensure image covers the area without distortion */
    }

    .card-img-overlay {
        position: absolute;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7); /* Semi-transparent background */
        color: white;
        width: 100%;
        padding: 20px;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8); /* Add text shadow for better readability */
        transition: background 0.5s ease; /* Smooth background transition */
    }

    .card:hover .card-img-overlay {
        background: rgba(0, 0, 0, 0.9); /* Darken background on hover */
    }

    .carousel-inner .carousel-item {
        display: flex;
    }

    .carousel-inner .carousel-item > div {
        flex: 1;
    }

    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        background-color: rgba(0, 0, 0, 0.5); /* Darken the carousel control icons */
    }

    .card {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Add box shadow for depth */
        transition: transform 0.3s ease; /* Smooth hover effect */
    }

    .card:hover {
        transform: translateY(-10px); /* Lift card on hover */
    }

    h2.text-center {
        font-weight: bold;
        color: #ffffff;    
    }

    .trainer-img {
        height: 250px; /* Set height for trainer images */
        object-fit: cover; /* Ensure image covers the area without distortion */
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const trainerCarousel = document.querySelector('#trainerCarousel');
        const trainers = trainerCarousel.querySelectorAll('.carousel-item .col-md-4');

        let visibleItems = 3;
        let itemWidth = 100 / visibleItems;

        trainers.forEach((trainer, index) => {
            trainer.style.minWidth = `${itemWidth}%`;
            trainer.style.maxWidth = `${itemWidth}%`;
        });

        trainerCarousel.addEventListener('slide.bs.carousel', function (e) {
            const items = e.target.querySelectorAll('.carousel-item');
            const totalItems = items.length;

            if (e.direction === 'left') {
                const end = parseInt(items[totalItems - 1].getAttribute('data-index')) + 1;
                const start = parseInt(items[0].getAttribute('data-index'));
                items[start].setAttribute('data-index', end);
                items[start].parentNode.appendChild(items[start]);
            } else {
                const start = parseInt(items[0].getAttribute('data-index')) - 1;
                const end = parseInt(items[totalItems - 1].getAttribute('data-index'));
                items[end].setAttribute('data-index', start);
                items[end].parentNode.insertBefore(items[end], items[0]);
            }
        });
    });
</script>

@endsection
