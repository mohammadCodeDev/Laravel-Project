<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Shopping Cart') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if ($cart && $cart->items->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">{{ __('Product') }}</th>
                                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">{{ __('Price') }}</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">{{ __('Quantity') }}</th>
                                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">{{ __('Total') }}</th>
                                    <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($cart->items as $item)
                                <tr>
                                    {{-- Product, Price, Quantity cells --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ $item->product->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ number_format($item->product->price) }} {{ __('Toman') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <div class="flex items-center justify-center">
                                            <form action="{{ route('cart.update', $item->id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="action" value="decrease">
                                                <button type="submit" class="px-2 py-1 bg-gray-200 dark:bg-gray-700 rounded-md">-</button>
                                            </form>
                                            <span class="px-4">{{ $item->quantity }}</span>
                                            <form action="{{ route('cart.update', $item->id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="action" value="increase">
                                                <button type="submit" class="px-2 py-1 bg-gray-200 dark:bg-gray-700 rounded-md">+</button>
                                            </form>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ number_format($item->quantity * $item->product->price) }} {{ __('Toman') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                        {{-- MODIFIED: Full Remove Button Form --}}
                                        <form action="{{ route('cart.destroy', $item->id) }}" method="POST" class="remove-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700">{{ __('Remove') }}</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-8 flex flex-col sm:flex-row justify-between items-center gap-4">
                        <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700">
                            {{ __('Continue Shopping') }}
                        </a>
                        <div class="text-right">
                            <p class="text-lg font-semibold">{{ __('Grand Total:') }} {{ number_format($totalPrice) }} {{ __('Toman') }}</p>
                            <form action="{{ route('orders.store') }}" method="POST" class="mt-4">
                                @csrf
                                <x-primary-button type="submit">
                                    {{ __('Finalize Purchase') }}
                                </x-primary-button>
                            </form>
                        </div>
                    </div>

                    @else
                    {{-- Empty cart section --}}
                    <div class="text-center py-12">
                        <p class="text-lg text-gray-600 dark:text-gray-400">{{ __('Your shopping cart is empty.') }}</p>
                        <a href="{{ route('products.index') }}" class="mt-4 inline-block px-6 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                            {{ __('Continue Shopping') }}
                        </a>
                    </div>
                    @endif

                    {{-- NEW: Back to Welcome Page Link --}}
                    <div class="mt-8 border-t dark:border-gray-700 pt-4 text-center">
                        <a href="{{ route('welcome') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:underline">
                            {{ __('Back to Welcome Page') }}
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>

</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const removeForms = document.querySelectorAll('.remove-form');
        const confirmMessage = "{{ __('Are you sure you want to remove this item?') }}";

        removeForms.forEach(form => {
            form.addEventListener('submit', function(event) {
                if (!confirm(confirmMessage)) {
                    event.preventDefault();
                }
            });
        });
    });
</script>