<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <script>
        window.AwesomeForum = {!! json_encode([
            'csrfToken' => csrf_token(),
            'isSignedIn' => Auth::check(),
            'user' => auth()->user(),
            'algolia' => [
                'id' => config('scout.algolia.id'),
                'secret' => config('scout.algolia.secret'),
            ]
        ]) !!}
    </script>
    @yield('head')
</head>
<body>
<div id="app">
    @include('layouts._nav')

    <main class="py-4">
        @yield('content')
    </main>

    <flash message="{{ session('flash') }}" flash-level="{{ session('level') }}"></flash>
</div>
@yield('scripts')
</body>
</html>
