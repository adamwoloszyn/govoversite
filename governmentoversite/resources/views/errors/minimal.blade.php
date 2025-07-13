<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=nunito:300,400,500,500i,600,600i,700,800" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        .gologo
        {
            height:30px;
        }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img class="gologo" src="{{asset('images/logo.gif')}}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
        </nav>

        <div class="row mt-5">
            <div class="mt-5">
                &nbsp;
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 text-center">
                An unexpected error has been encountered.
            </div>
            <div class="col-md-12 text-center mt-3">
                If this error continues, please capture the information on this screen and give it to the System Administrator.
            </div>

            <div class="col-md-12 text-center mt-3">
                <a href="/" class="btn btn-success">Return Home</a>
            </div>

            <div class="col-md-1 text-center">
            </div>

            <div class="col-md-10 text-center_">
                <div class="mt-5 row_ card">
                    <div class="col-md-12_ ">
                        <div class="card-header text-center">Error Code: @yield('code')</div>

                        <div class="card-body w-75 mx-auto">
                            <div class="row">
                                <div class="col-md-4">
                                    Error Message:
                                </div>
                                <div class="col-md-8 text-left">
                                    @yield('message')
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    URL Accessed: 
                                </div>
                                <div class="col-md-8 text-left">
                                    {{url()->full()}}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    Time of Incident:
                                </div>
                                <div class="col-md-8 text-left">
                                {{ date('Y-m-d H:i:s') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-1 text-center">
            </div>
        </div>
        <!-- <div style="background:red;" class="h-200 d-flex align-items-center justify-content-center ">
            <div class_="px-4 text-lg text-gray-500 border-r border-gray-400 tracking-wider">
                @yield('code')
            </div>

            <div class_="ml-4 text-lg text-gray-500 uppercase tracking-wider">
                @yield('message')
            </div>
        </div> -->
    </div>
</body>
</html>
