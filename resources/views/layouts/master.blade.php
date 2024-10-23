<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Task Project</title>

    <!-- Reference to CSS file -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <livewire:styles/>
</head>
<body>
@yield('content')

<livewire:scripts/>
<!-- Reference to JavaScript file -->
<script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
