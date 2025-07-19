<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'fa' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('welcome.title', ['default' => 'شرکت تعاونی در و پنجره سازان دزفول']) }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900 relative">
        
        <header class="absolute top-0 left-0 p-6 z-10">
            <a href="{{ route('language.switch', 'fa') }}" class="font-semibold text-gray-500 dark:text-gray-400">فارسی</a> |
            <a href="{{ route('language.switch', 'en') }}" class="font-semibold text-gray-500 dark:text-gray-400">English</a>
        </header>

        <main class="w-full flex-grow flex flex-col sm:justify-center items-center">
            <div>
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </main>
        
        <footer class="w-full py-6 text-center">
            <a href="/" class="text-sm font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                @if(app()->getLocale() == 'en') &larr; @endif
                {{ __('welcome.back_to_home') }}
                @if(app()->getLocale() == 'fa') &rarr; @endif
            </a>
        </footer>

    </div>
</body>
</html>