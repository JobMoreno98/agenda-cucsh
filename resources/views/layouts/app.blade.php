<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
<link rel="stylesheet" href="{{ asset('css/app.min.css') }}">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <!-- Styles -->
    @livewireStyles
</head>

<body class="font-sans antialiased">

    @include('layouts.navbar')

    <div class="container">
        @yield('content')
    </div>

<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/popper.min.js') }}"></script>



    <script src="{{ asset('vendor/sweetalert/sweetalert.all.js') }}"></script>

    <script>
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            showCloseButton: true
        });
    </script>
    @if (session('success'))
        <script>
            Toast.fire({
                title: "{!! session('message') !!}",
                icon: "success"
            });
        </script>
    @endif
    @if (session('error'))
        <script>
            Toast.fire({
                title: "{!! session('message') !!}",
                icon: "error"
            });
        </script>
    @endif
    @yield('js')
</body>

</html>
