<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Order Details') }} #{{ $order->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    {{-- Customer Information --}}
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold border-b pb-2 mb-4">{{ __('Customer Information') }}</h3>
                        <p><strong>{{ __('Customer Name') }}:</strong> {{ $order->user->name }}</p>
                        <p><strong>{{ __('Phone Number') }}:</strong> {{ $order->user->phoneNumber }}</p>
                    </div>

                    {{-- Order Items --}}
                    <div>
                        <h3 class="text-lg font-semibold border-b pb-2 mb-4">{{ __('Order Items') }}</h3>
                        <div class="space-y-4">
                            @foreach($order->orderItems as $item)
                                <div class="flex justify-between items-center p-4 border rounded-lg">
                                    <div>
                                        <p class="font-semibold">{{ $item->product->name }}</p>
                                        <p class="text-sm text-gray-500">{{ __('Quantity') }}: {{ $item->quantity }}</p>
                                    </div>
                                    <p class="font-semibold">{{ number_format($item->price, 2) }} {{ __('Toman') }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    {{-- Action Button --}}
                    <div class="mt-8 text-center">
                        <form method="POST" action="{{ route('warehouse.orders.deliver', $order) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="w-full px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                                {{ __('Confirm Delivery and Mark as Delivered') }}
                            </button>
                        </form>
                    </div>

                </div>
            </div>
             {{-- Back to list link --}}
            <div class="mt-4 text-start">
                <a href="{{ route('warehouse.orders.index') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:underline">
                    {{ __('Back to Confirmed Orders') }}
                </a>
            </div>
        </div>
    </div>
</x-app-layout>