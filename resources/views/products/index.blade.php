<x-guest-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('All Products') }}
            </h2>
            <a href="{{ route('welcome') }}" class="text-sm text-blue-500 hover:underline">
                {{ __('Back to Welcome Page') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @guest
                <div class="mb-6 p-4 bg-blue-100 border-l-4 border-blue-500 text-blue-700 dark:bg-blue-900/30 dark:border-blue-700 dark:text-blue-300">
                    <p>{{ __('To add products to your cart, please') }} <a href="{{ route('login') }}" class="font-bold hover:underline">{{ __('Log in') }}</a> {{ __('or') }} <a href="{{ route('register') }}" class="font-bold hover:underline">{{ __('Register') }}</a>.</p>
                </div>
            @endguest

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                @forelse ($products as $product)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg flex flex-col transition-transform duration-300 hover:scale-105 border border-transparent dark:border-gray-700">
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
                        <div class="p-6 flex flex-col flex-grow">
                            <h3 class="font-semibold text-lg text-gray-900 dark:text-gray-200 flex-grow">{{ $product->name }}</h3>
                            <p class="mt-2 text-gray-700 dark:text-gray-300 text-xl font-bold">{{ number_format($product->price, 0) }} <span class="text-sm font-normal">{{ __('Toman') }}</span></p>
                            
                            <div class="mt-6">
                                @if(Auth::check() && Auth::user()->role === 'customer')
                                    <form method="POST" action="{{ route('cart.store') }}">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <button type="submit" class="w-full text-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                            {{ __('Add to Cart') }}
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="col-span-full text-center text-gray-500 dark:text-gray-400">{{ __('No products available at the moment.') }}</p>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</x-guest-layout>