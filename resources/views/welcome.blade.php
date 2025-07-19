<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'fa' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('welcome.title', ['default' => 'شرکت تعاونی در و پنجره سازان دزفول']) }}</title>

    {{-- Vite handles CSS/JS bundling --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="antialiased bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200">

    {{-- HEADER: Contains top navigation --}}
    <header class="absolute top-0 left-0 right-0 p-6 z-10 flex justify-between items-center">
        {{-- Language Switcher --}}
        <div class="space-x-4">
            <a href="{{ route('language.switch', 'fa') }}" class="font-semibold text-gray-500 hover:text-white">فارسی</a>
            <span class="text-gray-600">|</span>
            <a href="{{ route('language.switch', 'en') }}" class="font-semibold text-gray-500 hover:text-white">English</a>
        </div>

        {{-- Auth Links --}}
        @if (Route::has('login'))
        <div class="space-x-4">
            @auth
            <a href="{{ url('/dashboard') }}" class="font-semibold hover:text-white">{{ __('welcome.dashboard') }}</a>
            @else
            <a href="{{ route('login') }}" class="font-semibold hover:text-white">{{ __('welcome.login') }}</a>
            @if (Route::has('register'))
            <a href="{{ route('register') }}" class="font-semibold hover:text-white">{{ __('welcome.register') }}</a>
            @endif
            @endauth
        </div>
        @endif
    </header>

    {{-- MAIN CONTENT --}}
    <main class="min-h-screen flex flex-col items-center justify-center bg-dots-darker bg-center dark:bg-dots-lighter selection:bg-red-500 selection:text-white">
        <div class="max-w-7xl mx-auto p-6 lg:p-8 text-center">

            {{-- Welcome Heading --}}
            <section aria-labelledby="main-heading">
                <h1 id="main-heading" class="text-3xl md:text-4xl font-bold dark:text-white">{{ __('welcome.main_heading') }}</h1>
            </section>

            {{-- Daily Price Section --}}
            @if($dailyIronPrice)
            <section aria-labelledby="price-heading" class="mt-8 p-6 bg-white dark:bg-gray-800/50 rounded-lg shadow-lg">
                <h2 id="price-heading" class="text-xl font-semibold dark:text-white">{{ __("Today's Iron Price") }}</h2>
                <p class="text-4xl font-bold text-blue-500 mt-2">
                    {{ number_format($dailyIronPrice) }}
                    <span class="text-lg font-medium text-gray-500 dark:text-gray-400">{{ __('Toman / kg') }}</span>
                </p>
            </section>
            @endif

            {{-- Call to Action Button --}}
            <div class="mt-10">
                <a href="{{ route('products.index') }}" class="inline-block px-8 py-4 bg-blue-600 text-white font-bold text-lg rounded-lg shadow-md hover:bg-blue-700 transition-transform transform hover:scale-105">
                    {{ __('View All Products') }}
                </a>
            </div>

        </div>
    </main>

    {{-- NEWS SECTION --}}
    <section aria-labelledby="news-heading" class="w-full bg-gray-200 dark:bg-gray-900/50 py-16">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <h2 id="news-heading" class="text-3xl font-bold text-center mb-12 dark:text-white">{{ __('Latest News') }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @forelse($news as $newsItem)
                    <article class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-xl flex flex-col">
                        <header>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $newsItem->title }}</h3>
                            <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">{{ $newsItem->created_at->format('Y-m-d') }}</p>
                        </header>
                        <p class="mt-4 text-gray-600 dark:text-gray-400 leading-relaxed flex-grow">
                            {{ Str::limit($newsItem->content, 150) }}
                        </p>
                        <footer class="mt-6">
                            <button type="button" class="read-more-btn font-semibold text-blue-500 hover:text-blue-700 dark:hover:text-blue-300"
                                    data-news-title="{{ $newsItem->title }}"
                                    data-news-content="{{ $newsItem->content }}">
                                {{ __('Read More') }} &rarr;
                            </button>
                        </footer>
                    </article>
                @empty
                    <p class="text-gray-500 dark:text-gray-400 md:col-span-2 text-center">{{ __('No news to display.') }}</p>
                @endforelse
            </div>
        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="text-center p-6 text-sm text-gray-500 dark:text-gray-400">
        <p>&copy; {{ date('Y') }} {{ __('welcome.title', ['default' => 'شرکت تعاونی در و پنجره سازان دزفول']) }}. {{ __('All rights reserved.') }}</p>
    </footer>

    <div id="newsModal" class="fixed inset-0 bg-black bg-opacity-70 z-50 items-center justify-center hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-3xl max-h-[90vh] flex flex-col m-4">
            <header class="flex justify-between items-center p-4 border-b dark:border-gray-700">
                <h3 id="newsModalTitle" class="text-xl font-semibold text-gray-900 dark:text-gray-100"></h3>
                <button id="closeNewsModal" class="text-3xl text-gray-400 hover:text-gray-600">&times;</button>
            </header>
            <main class="p-6 overflow-y-auto">
                <p id="newsModalContent" class="text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-wrap"></p>
            </main>
        </div>
    </div>

</body>
</html>

<script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('newsModal');
            if (modal) {
                const closeModalBtn = document.getElementById('closeNewsModal');
                const modalTitle = document.getElementById('newsModalTitle');
                const modalContent = document.getElementById('newsModalContent');
                const readMoreBtns = document.querySelectorAll('.read-more-btn');

                readMoreBtns.forEach(btn => {
                    btn.addEventListener('click', function () {
                        modalTitle.textContent = this.dataset.newsTitle;
                        modalContent.textContent = this.dataset.newsContent;
                        modal.classList.remove('hidden');
                        modal.classList.add('flex');
                    });
                });

                const closeModal = () => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                };

                closeModalBtn.addEventListener('click', closeModal);
                modal.addEventListener('click', function (event) {
                    if (event.target === modal) {
                        closeModal();
                    }
                });
            }
        });
    </script>