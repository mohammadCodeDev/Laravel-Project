<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ __('welcome.title') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>


<body class="antialiased">
    <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white">

        <div class="sm:fixed sm:top-0 sm:left-0 p-6 z-10">
            <a href="{{ route('language.switch', 'fa') }}" class="font-semibold text-gray-400">فارسی</a> |
            <a href="{{ route('language.switch', 'en') }}" class="font-semibold text-gray-400">English</a>
        </div>

        @if (Route::has('login'))
        <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right z-10">
            @auth
            <a href="{{ url('/dashboard') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">{{ __('welcome.dashboard') }}</a>
            @else
            <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">{{ __('welcome.login') }}</a>

            @if (Route::has('register'))
            <a href="{{ route('register') }}" class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">{{ __('welcome.register') }}</a>
            @endif
            @endauth
        </div>
        @endif

        <div class="max-w-7xl mx-auto p-6 lg:p-8">

            <h1 class="text-2xl dark:text-white">{{ __('welcome.main_heading') }}</h1>

            @if($dailyIronPrice)
            <div class="mt-8 p-6 bg-white dark:bg-gray-800/50 rounded-lg shadow-lg text-center">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Today\'s Iron Price') }}</h3>
                <p class="text-3xl font-bold text-blue-600 dark:text-blue-400 mt-2">
                    {{ number_format($dailyIronPrice) }}
                    <span class="text-base text-gray-500 dark:text-gray-300">{{ __('Toman / kg') }}</span>
                </p>
            </div>
            @endif

            <div class="mt-6">
                <a href="{{ route('products.index') }}" class="inline-block px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 transition">
                    {{ __('View All Products') }}
                </a>
            </div>

            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ __('Latest News') }}</h2>

            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
                @forelse($news as $newsItem)
                <div class="scale-100 p-6 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $newsItem->title }}</h3>
                        <p class="mt-4 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                            {{ $newsItem->content }}
                        </p>
                    </div>
                    <p class="mt-4 text-xs text-gray-400 dark:text-gray-500">{{ $newsItem->created_at->format('Y-m-d') }}</p>
                </div>
                @empty
                <p class="text-gray-500 dark:text-gray-400">{{ __('No news to display.') }}</p>
                @endforelse
            </div>
        </div>

    </div>

</body>

</html>