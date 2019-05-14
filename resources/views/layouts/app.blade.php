<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        window.Laravel = { csrfToken: '{{ csrf_token() }}'}
    </script>
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container">
                <!-- Branding Image -->
                @guest
                  <a class="navbar-brand" href="{{ url('/') }}">
                      {{ config('app.name', 'Laravel') }}
                  </a>
                @else
                  <a class="navbar-brand" href="{{ url('/home') }}">
                      {{ config('app.name', 'Laravel') }}
                  </a>
                @endguest
                <!-- Collapsed Hamburger -->
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        @guest
                            &nbsp;
                        @else
                            <li class="nav-item">
                              <a class="nav-link" href="/customers">Customers</a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link" href="/orders">Orders</a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link" href="/products">Products</a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link" href="/items">Sales Inventory</a>
                            </li>
                        @endguest
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                              <a class="nav-link" href="{{ route('login') }}">Login</a>
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu" aria-labelledby="dropdown01">
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container mt-4">
          @if(count($errors) > 0)
            @foreach($errors->all() as $error)
              <div class="alert alert-danger">
                {{$error}}
              </div>
            @endforeach
          @endif

          @if(session("success"))
            <div class="alert alert-success">
              {{session("success")}}
            </div>
          @endif

          @if(session("error"))
            <div class="alert alert-danger">
              {{session("error")}}
            </div>
          @endif
        </div>
        <div class="container">
          @yield('content')
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>
</body>
</html>
