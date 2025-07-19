<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('welcome.title', ['default' => 'شرکت تعاونی در و پنجره سازان دزفول']) }}</title>
    
    {{-- Vite handles CSS/JS bundling --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    @inertia
</body>

</html>