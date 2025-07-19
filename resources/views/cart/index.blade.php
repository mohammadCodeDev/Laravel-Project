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
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">{{ $item->product->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{ number_format($item->product->price) }} {{ __('Toman') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                {{-- Quantity Controls --}}
                                                <div class="flex items-center justify-center">
                                                    {{-- Decrease Button --}}
                                                    <form action="{{ route('cart.update', $item->id) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="action" value="decrease">
                                                        <button type="submit" class="px-2 py-1 bg-gray-200 dark:bg-gray-700 rounded-md">-</button>
                                                    </form>

                                                    <span class="px-4">{{ $item->quantity }}</span>

                                                    {{-- Increase Button --}}
                                                    <form action="{{ route('cart.update', $item->id) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="action" value="increase">
                                                        <button type="submit" class="px-2 py-1 bg-gray-200 dark:bg-gray-700 rounded-md">+</button>
                                                    </form>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{ number_format($item->quantity * $item->product->price) }} {{ __('Toman') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                                {{-- Full Remove Button --}}
                                                <form action="{{ route('cart.destroy', $item->id) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to remove this item?') }}');">
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
                            {{-- Continue Shopping Button --}}
                            <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('Continue Shopping') }}
                            </a>
                            
                            {{-- Totals and Finalize Button --}}
                            <div class="text-right">
                                <p class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                                    {{ __('Grand Total:') }} {{ number_format($totalPrice) }} {{ __('Toman') }}
                                </p>
                                <form action="#" method="POST" class="mt-4">
                                    @csrf
                                    <x-primary-button>
                                        {{ __('Finalize Purchase') }}
                                    </x-primary-button>
                                </form>
                            </div>
                        </div>

                    @else
                        <div class="text-center py-12">
                            <p class="text-lg text-gray-600 dark:text-gray-400">{{ __('Your shopping cart is empty.') }}</p>
                            <a href="{{ route('products.index') }}" class="mt-4 inline-block px-6 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                                {{ __('Continue Shopping') }}
                            </a>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>