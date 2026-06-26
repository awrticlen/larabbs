<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'LaraBBS') - Laravel 进阶教程</title>
    <meta name="description" content="@yield('description', 'LaraBBS 爱好者社区')" />
    @yield('styles')
</head>

<body class="d-flex flex-column min-vh-100">

    <div id="app" class="{{ route_class() }}-page d-flex flex-column flex-grow-1">

        @include('layouts._header')

        <main class="flex-grow-1">
            <div class="container py-4">

                @include('layouts._messages')

                @yield('content')

            </div>
        </main>

        @include('layouts._footer')

    </div>

    @if (app()->isLocal())
        @include('sudosu::user-selector')
    @endif

    <!-- Scripts -->
    @vite(['resources/js/app.js'])
    @yield('scripts')

</body>

</html>
