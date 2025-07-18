<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Order Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if(session('success'))
                        <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">{{ session('success') }}</div>
                    @endif
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">{{ __('Order ID') }}</th>
                                    <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">{{ __('Customer') }}</th>
                                    <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">{{ __('Total Price') }}</th>
                                    <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">{{ __('Status') }}</th>
                                    <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">{{ __('Date') }}</th>
                                    <th class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($orders as $order)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ $order->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $order->user->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ number_format($order->total_price, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @switch($order->status)
                                                    @case('pending') bg-yellow-100 text-yellow-800 @break
                                                    @case('confirmed') bg-green-100 text-green-800 @break
                                                    @case('delivered') bg-blue-100 text-blue-800 @break
                                                    @case('canceled') bg-red-100 text-red-800 @break
                                                @endswitch">
                                                {{ __("order_statuses.{$order->status}") }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $order->created_at->format('Y-m-d') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                            @if($order->status === 'pending')
                                                <form method="POST" action="{{ route('admin.orders.confirm', $order) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="text-green-600 hover:text-green-900">{{ __('Confirm') }}</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="px-6 py-4 text-center">{{ __('No orders found.') }}</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>