<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | {{ config('settings.system_general.title', 'iNetto') }}</title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    @stack('styles')

    <style>
        a.nav-link i{
            border: 2px solid white;
            border-radius: 15px;
            padding: 3px;
        }
        html, body {
            height: 100%;
        }
        /* Create a flex container */
        .d-flex {
            flex-direction: column;
            min-height: 100vh;
        }
        /* Push the footer to the bottom */
        .footer {
            margin-top: auto;
        }
    </style>

</head>
<body class="d-flex flex-column">

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-light" style="background: #46A6D8;box-shadow: 1px 2px 5px 1px #646e78;">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            @if(config('settings.system_general.logo_path'))
                <img class="w-100" src="{{ asset('storage/' . config('settings.system_general.logo_path')) }}">
            @else
                <i class='bx bx-station text-white' style="font-size: 35px;vertical-align: sub;"></i>
                <span class="app-brand-text demo menu-text fw-bold ms-2 text-white">{{ config('settings.system_general.logo_text', 'iNetto') }}</span>
            @endif
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item my-2">
                    <a class="nav-link text-white fw-bold d-inline" aria-current="page" href="tel:{{ config('settings.system_general.contact_no', '01712345678') }}"><i style="font-size: 1rem;" class='bx bxs-phone me-1'></i> {{ config('settings.system_general.contact_no', '+8801712345678') }}</a>
                </li>
                <li class="nav-item my-2">
                    <a class="nav-link text-white fw-bold d-inline" aria-current="page" href="mailto:{{ config('settings.system_general.contact_email', 'info@inetto.com') }}"><i style="font-size: 1rem;" class='bx bx-envelope me-1'></i> {{ config('settings.system_general.contact_email', 'info@inetto.com') }}</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Services Section -->
<div class="container">
    @yield('content')
</div>

<!-- Footer -->
<footer class="bg-light text-center py-5 footer">
    <p class="mb-0">{!! config('settings.system_general.copyright', 'Developed By <a href="https://codexwp.com" target="_blank" class="footer-link">CodeXwp</a>') !!}</p>
</footer>

<script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>

@stack('scripts')
</body>
</html>
