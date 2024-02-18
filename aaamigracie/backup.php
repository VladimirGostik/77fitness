<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">{{ config('app.name', 'Laravel') }}</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText"
            aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link{{ Request::is('/') ? ' active' : '' }}" href="{{ url('/') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/articles">Articles</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/articles/create">Create article</a>
                </li>
                @auth
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}">Logout</a>
                </li>
                @else
                <li class="nav-item">
                    <a class="nav-link{{ Request::is('login') ? ' active' : '' }}" href="{{ route('login') }}">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link{{ Request::is('registration') ? ' active' : '' }}"
                        href="{{ route('registration') }}">Registration</a>
                </li>
                @endauth
            </ul>
            <span class="navbar-text">
                @auth
                Welcome, {{ auth()->user()->first_name }}
                @endauth
            </span>
        </div>
    </div>
</nav>
