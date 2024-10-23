<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Task Project</title>
    <!-- Include Tailwind CSS from CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <!-- Reference to CSS file -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    @livewireStyles
</head>
<body>
@yield('content')

@livewireScripts
<!-- Reference to JavaScript file -->
<script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
