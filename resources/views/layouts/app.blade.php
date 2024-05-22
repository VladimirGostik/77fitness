<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@latest/main.min.css">
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@latest/main.min.js"></script>
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel') }}</title>

  <link rel="dns-prefetch" href="//fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css">
  @vite(['resources/sass/app.scss', 'resources/js/app.js'])
  <style>
    body {
      margin: 0;
      padding: 0;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    #app {
      flex: 1;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    footer {
      background-color: #343a40;
      color: white;
      padding: 40px 0;
      margin-top: auto;
    }

    .footer-links {
      list-style: none;
      padding: 0;
    }

    .footer-links li {
      margin-bottom: 10px;
    }

    .footer-links a {
      color: #adb5bd;
      text-decoration: none;
    }

    .footer-links a:hover {
      color: #fff;
      text-decoration: underline;
    }

    .social-icons a {
      color: #adb5bd;
      margin-right: 15px;
      font-size: 24px;
      text-decoration: none;
    }

    .social-icons a:hover {
      color: #fff;
    }
  </style>
</head>
<body>
  <script type="text/javascript" src="https://gw.sandbox.gopay.com/gp-gw/js/embed.js"></script>
  <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>

  <div id="app">
    <nav class="navbar navbar-expand-md navbar-inverse shadow-sm">
      <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">{{ config('app.name', 'Laravel') }}</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto">
          </ul>

          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <a class="nav-link{{ Request::is('/') ? ' active' : '' }}" href="{{ url('/home') }}">Home</a>
            </li>

            @guest
              @if (Route::has('login'))
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                </li>
              @endif

              @if (Route::has('register'))
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                </li>
              @endif
            @else
            <li class="nav-item dropdown">
              <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                {{ Auth::user()->first_name }}
              </a>

              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="{{ route('logout') }}"
                   onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">
                  {{ __('Logout') }}
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                  @csrf
                </form>

                <a class="dropdown-item" href="{{ route('users.edit', Auth::user()->id) }}">Upraviť Profil</a> 
                @if(Auth::user()->role === 1)
                    <a class="dropdown-item" href="{{ route('payments.index') }}">Dobit Kredit</a>
                @endif
              </div>
            </li>
            @if(Auth::user()->role === 1)
              <li class="nav-item">
                <span class="nav-link">Credit: {{ sprintf('%d€', Auth::user()->Client->credit ?? 0) }}</span>
              </li>
            @endif
            @endguest
          </ul>
        </div>
      </div>
    </nav>
    <div class="container">
      @if(session()->has('error'))
        <div class="alert alert-danger">{{session('error')}}</div>
      @endif
      <main class="py-4">
        @yield('content')
      </main>
    </div>

    <footer class="footer mt-auto py-3">
      <div class="container">
        <div class="row">
          <div class="col-md-4">
            <h5>Links</h5>
            <ul class="footer-links">
              <li><a href="https://www.facebook.com/77fitness.sk" target="_blank"><i class="bi bi-facebook"></i> Facebook</a></li>
              <li><a href="https://www.instagram.com/77fitness.sk/" target="_blank"><i class="bi bi-instagram"></i> Instagram</a></li>
              <li><a href="mailto:77fitness@gmail.com"><i class="bi bi-envelope"></i> 77fitness@gmail.com</a></li>
              <li><a href="https://www.youtube.com/channel/UCgumAghelhdahPVrik__liw" target="_blank"><i class="bi bi-youtube"></i> YouTube</a></li>
            </ul>
          </div>
          <div class="col-md-4">
            <h5>Contact</h5>
            <ul class="footer-links">
              <li><a href="https://maps.app.goo.gl/VVZhQ86zmW1foxgB7" target="_blank"><i class="bi bi-geo-alt"></i> Bajkalska 2/i, Bratislava, Slovakia</a></li>
              <li><i class="bi bi-clock"></i> Open Hours:
                <ul class="footer-links">
                  <li>Po - Pia: 5:30 – 22:00</li>                  
                  <li>So - Ne, Sviatky: 8:00 – 22:00</li>
                </ul>
              </li>
            </ul>
          </div>
          <div class="col-md-4">
            <h5>Follow Us</h5>
            <div class="social-icons">
              <a href="https://www.facebook.com/77fitness.sk" target="_blank"><i class="bi bi-facebook"></i></a>
              <a href="https://www.instagram.com/77fitness.sk/" target="_blank"><i class="bi bi-instagram"></i></a>
              <a href="https://www.youtube.com/channel/UCgumAghelhdahPVrik__liw" target="_blank"><i class="bi bi-youtube"></i></a>

            </div>
          </div>
        </div>
      </div>
    </footer>
  </div>
</body>
</html>
