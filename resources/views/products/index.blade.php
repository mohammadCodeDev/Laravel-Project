<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('All Products') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse ($products as $product)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg flex flex-col">
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
                        <div class="p-6 flex flex-col flex-grow">
                            <h3 class="font-semibold text-lg text-gray-900 dark:text-gray-200">{{ $product->name }}</h3>
                            <p class="mt-2 text-gray-600 dark:text-gray-400">{{ number_format($product->price, 0) }} {{ __('Toman') }}</p>
                            
                            <div class="mt-4 pt-4 border-t dark:border-gray-700 flex-grow flex items-end">
                                @auth
                                    @if(Auth::user()->role === 'customer')
                                        <form method="POST" action="{{ route('cart.store') }}">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <x-primary-button>
                                                {{ __('Add to Cart') }}
                                            </x-primary-button>
                                        </form>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="text-sm text-blue-500 hover:underline">{{ __('Login to purchase') }}</a>
                                @endauth
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
</x-app-layout>