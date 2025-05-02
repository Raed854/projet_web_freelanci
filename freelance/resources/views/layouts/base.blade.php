<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'InnovMatch')</title>

    {{-- Default stylesheets --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    {{-- Additional stylesheets from child views --}}
    @yield('stylesheets')
</head>
<body>
    {{-- Main content section --}}
    @yield('body')
</body>
</html>
