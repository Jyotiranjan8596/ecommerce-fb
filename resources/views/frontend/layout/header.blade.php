<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Sridipta Research & Development Consultancy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('frontend/style.css') }}" />
    <style>
        a {
            text-decoration: none;
        }

        .cart_count {
            position: absolute;
            margin-left: 31px;
            padding: 0px 5px;
            border: 1px solid;
            border-radius: 11px;
            background-color: red;
            color: white;
            font-size: 11px;
        }
    </style>
</head>

<body>
    <!-- nav bar start here -->
    <header>
        <nav class="navbar  navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ route('frontend.index') }}"><img
                        src="{{ asset('frontend/img/logo_srdc.png') }}" alt="Logo" /></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="{{ route('frontend.index') }}">HOME</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">APIs</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('frontend.sector') }}">SECTOR</a>
                        </li>
                        <li class="nav-item d-flex">
                            @auth
                                <a href="{{ route('user.index') }}" style="display: inline;">
                                    <button type="button" class="nav-link btn btn-link">DASHBOARD</button>
                                </a>
                                <a href="{{ route('logout') }}" style="display: inline;"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <button type="button" class="nav-link btn btn-link">LOGOUT</button></a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                                {{-- <a href="{{ route('cart.index') }}" style="display: inline;" class="border-left">
                                    <span class="cart_count">
                                        @php
                                            $cart_count = count(
                                                App\Models\Cart::where('user_id', Auth::user()->id)->get(),
                                            );
                                        @endphp
                                        {{ $cart_count }}
                                    </span>
                                    <button type="button" class="nav-link btn btn-link">Cart</button>
                                </a> --}}
                            @else
                                <a class="nav-link" href="{{ route('login') }}">LOGIN</a>
                                <a class="nav-link" href="{{ route('register') }}">REGISTER</a>
                            @endauth
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <!-- nav bar end here -->
