<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Orders') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 dark:bg-green-800 text-green-700 dark:text-green-200 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif

                    @forelse($orders as $order)
                        <div class="mb-6 p-4 border dark:border-gray-700 rounded-lg">
                            <div class="flex justify-between items-center mb-4">
                                <div>
                                    <h3 class="font-semibold">{{ __('Order') }} #{{ $order->id }}</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $order->created_at->format('Y-m-d H:i') }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="px-3 py-1 text-sm font-semibold rounded-full 
                                        @if($order->status == 'pending') bg-yellow-200 text-yellow-800 @elseif($order->status == 'confirmed') bg-blue-200 text-blue-800 @elseif($order->status == 'delivered') bg-green-200 text-green-800 @endif">
                                        {{ __('order_status.' . $order->status) }}
                                    </span>
                                    <p class="font-bold mt-1">{{ number_format($order->total_price) }} {{ __('Toman') }}</p>
                                </div>
                            </div>
                            
                            <div class="border-t dark:border-gray-600 pt-4">
                                <h4 class="font-semibold mb-2">{{ __('Items') }}</h4>
                                <ul class="list-disc ps-5">
                                    @foreach($order->items as $item)
                                        <li>{{ $item->product->name }} ({{ __('Quantity') }}: {{ $item->quantity }})</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @empty
                        <p>{{ __("You haven't placed any orders yet.") }}</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>