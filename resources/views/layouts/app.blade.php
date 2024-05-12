<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel') }}</title>

  <link rel="dns-prefetch" href="//fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">


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
      /* Remove align-items: center; */
      justify-content: center;
    }

    footer {
      width: 90%;
      background-color: #f8f9fa;
      text-align: center;
      padding: 20px 0;
      margin-top: auto; /* Ensure footer stays at the bottom */
      margin-left: auto; /* Center horizontally */
      margin-right: auto; /* Center horizontally */
    }
  </style>
</head>
<body>
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
                <a class="dropdown-item" href="#">Dobit Kredit</a> </div>
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

    <footer class="py-3 text-muted text-center">
      <h2>Links & Contact</h2>
      <ul class="list-unstyled d-flex flex-wrap justify-content-between mb-0">
        <li>
          <a href="https://www.facebook.com/" target="_blank" class="text-dark">
            <i class="bi bi-facebook me-1"></i> Facebook
          </a>
        </li>
        <li>
          <a href="https://www.instagram.com/" target="_blank" class="text-dark">
            <i class="bi bi-instagram me-1"></i> Instagram
          </a>
        </li>
        <li>
          <a href="mailto:youremail@example.com" class="text-dark">
            <i class="bi bi-envelope me-1"></i> youremail@example.com
          </a>
        </li>
        <li>
          <i class="bi bi-geo-alt me-1"></i> Location: Your Business Address
        </li>
        <li>
          <i class="bi bi-clock me-1"></i> Open Hours: Monday-Friday: 9:00 AM - 5:00 PM, Saturday: 10:00 AM - 4:00 PM (Closed on Sundays)
        </li>
      </ul>
    </footer>
  </div>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERFvqQZ8hhWOBX0jNpdeeN5hwchEjIpVu6wIkKPTrpCl7sGhayoGeHsqEt4zT/t" crossorigin="anonymous"></script>

</body>
</html>
