<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>
    <meta name="description" content="@yield('description', 'Default Description')">
    <meta name="keywords" content="@yield('keywords', 'default, keywords')">
    <link rel="canonical" href="@yield('canonical', '')">
    <meta name="author" content="@yield('author', 'Default Author')">
    <meta name="og:title" content="@yield('og_title', 'Default OG Title')">
    <meta name="og:description" content="@yield('og_description', 'Default OG Description')">
    <meta name="twitter:title" content="@yield('twitter_title', 'Default Twitter Title')">
    <meta name="twitter:description" content="@yield('twitter_description', 'Default Twitter Description')">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=nunito:300,400,500,500i,600,600i,700,800" rel="stylesheet" />

    {{-- <script type="text/javascript" src="https://unpkg.com/trix@2.0.0/dist/trix.umd.min.js"></script> --}}
    <script src="{{asset('js/jquery.min.js')}}"></script>
    <script src="{{asset('js/bootstrap.min.js')}}"></script>
    {{-- <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.0/dist/trix.css"> --}}

    <script src="https://cdn.ckeditor.com/ckeditor5/27.1.0/classic/ckeditor.js"></script>
    
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        body{
            touch-action: manipulation;
            overflow-x: hidden;
        }
        .gologo
        {
            height:30px;
        }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img class="gologo" src="{{asset('images/logo.gif')}}">
                    GovernmentOversite.com
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    @guest
                        <!-- Left Side Of Navbar -->
                        <ul class="navbar-nav me-auto">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('contact') }}">Contact</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('donate') }}">Donate</a>
                            </li>
                        </ul>
                    @else
                        @if (Auth::user()->isSubscriber())
                            <ul class="navbar-nav me-auto">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/') }}">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/Livestream') }}">Livestream</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('contact') }}">Contact</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('donate') }}">Donate</a>
                            </li>
                            {{-- <li class="nav-item">
                                <a class="nav-link" href="{{ route('UserSettings') }}">Profile</a>
                            </li> --}}
                            {{-- <li class="nav-item">
                                    <input type="text" id="q" name="q" class="p-2 pe-3 ps-3 w-100 rounded-3 border border-1" placeholder="Search videos by title, keyword, date">
                                </li> --}}
                        </ul>
                        @endif

                        @if (Auth::user()->isAdmin())
                            <!-- Left Side Of Navbar -->
                            <ul class="navbar-nav me-auto">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{url('/')}}">Home</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{url('/Keywords')}}">Keywords</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{url('/Users')}}">Users</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{url('/Video')}}">Videos</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{url('/Channels')}}">Channels</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{url('/agendaItemTypes')}}">Agenda Item Templates</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{url('/old-video-import')}}">Import Old Videos</a>
                                </li>
                            </ul>
                        @endif
                    @endguest
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Sign Up') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item">
                                <!-- Display User Settings -->
                                <a class="nav-link" href="{{ route('UserSettings') }}">
                                    User Settings
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <!-- Display Logged In User's name -->
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <!-- Display Logout option -->
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
