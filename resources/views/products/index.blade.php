<x-public-layout>
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
        <main class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
            @endif

            @guest
            <div class="mb-8 p-4 bg-blue-100 border-l-4 border-blue-500 text-blue-700 dark:bg-blue-900/30 dark:border-blue-700 dark:text-blue-300 rounded-md">
                <p>{{ __('To add products to your cart, please') }} <a href="{{ route('login', ['redirect' => route('products.index')]) }}" class="font-bold hover:underline">{{ __('Log in') }}</a> {{ __('or') }} <a href="{{ route('register', ['redirect' => route('products.index')]) }}" class="font-bold hover:underline">{{ __('Register') }}</a>.</p>
            </div>
            @endguest

            <section aria-labelledby="products-heading">
                <h2 id="products-heading" class="sr-only">{{ __('Products List') }}</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                    @forelse ($products as $product)
                    {{-- Using <article> for better semantics --}}
                    <article class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg flex flex-col transition-transform duration-300 hover:scale-105 border border-transparent dark:border-gray-700">
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">

                        <div class="p-6 flex flex-col flex-grow">
                            <h3 class="font-semibold text-lg text-gray-900 dark:text-gray-200 flex-grow">{{ $product->name }}</h3>
                            <p class="mt-2 text-gray-700 dark:text-gray-300 text-xl font-bold">{{ number_format($product->price, 0) }} <span class="text-sm font-normal">{{ __('Toman') }}</span></p>

                            <div class="mt-6 space-y-3">
                                {{-- NEW: Details Button --}}
                                <button type="button" class="details-btn w-full text-center px-4 py-2 bg-gray-200 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-gray-800 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-500 transition ease-in-out duration-150"
                                    data-product-name="{{ $product->name }}"
                                    data-product-image="{{ asset('storage/' . $product->image) }}"
                                    data-product-price="{{ number_format($product->price, 0) }} {{ __('Toman') }}"
                                    data-product-description="{{ $product->description }}">
                                    {{ __('View Details') }}
                                </button>

                                {{-- Add to Cart Button --}}
                                @if(Auth::check() && Auth::user()->role === 'customer')
                                <form method="POST" action="{{ route('cart.store') }}">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button type="submit" class="w-full text-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none transition ease-in-out duration-150">
                                        {{ __('Add to Cart') }}
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </article>
                    @empty
                    <p class="col-span-full text-center text-gray-500 dark:text-gray-400">{{ __('No products available at the moment.') }}</p>
                    @endforelse
                </div>
            </section>

            <div class="mt-8">
                {{ $products->links() }}
            </div>
        </main>
    </div>

    <div id="productModal" class="fixed inset-0 bg-black bg-opacity-60 z-50 items-center justify-center hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] flex flex-col">
            <header class="flex justify-between items-center p-4 border-b dark:border-gray-700">
                <h3 id="modalTitle" class="text-xl font-semibold text-gray-900 dark:text-gray-100"></h3>
                <button id="closeModal" class="text-2xl text-gray-400 hover:text-gray-600">&times;</button>
            </header>
            <main class="p-6 overflow-y-auto">
                <img id="modalImage" src="" alt="" class="w-full h-64 object-cover rounded-md mb-4">
                <div id="modalDescription" class="text-gray-700 dark:text-gray-300 leading-relaxed"></div>
            </main>
            <footer class="p-4 bg-gray-50 dark:bg-gray-800/50 border-t dark:border-gray-700">
                <p id="modalPrice" class="text-2xl font-bold text-gray-900 dark:text-gray-100"></p>
            </footer>
        </div>
    </div>

</x-public-layout>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('productModal');
        const closeModalBtn = document.getElementById('closeModal');
        const viewBtns = document.querySelectorAll('.details-btn');

        viewBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                // Populate modal with data from button's data-* attributes
                document.getElementById('modalTitle').textContent = this.dataset.productName;
                document.getElementById('modalImage').src = this.dataset.productImage;
                document.getElementById('modalImage').alt = this.dataset.productName;
                document.getElementById('modalDescription').textContent = this.dataset.productDescription;
                document.getElementById('modalPrice').textContent = this.dataset.productPrice;

                // Show modal
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            });
        });

        const closeModal = () => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        };

        closeModalBtn.addEventListener('click', closeModal);
        modal.addEventListener('click', function(event) {
            if (event.target === modal) { // Close if clicked on the overlay
                closeModal();
            }
        });
    });
</script>