<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Confirmed Orders') }}
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
                            {{-- Table headers --}}
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">{{ __('Order ID') }}</th>
                                    <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">{{ __('Customer') }}</th>
                                    <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">{{ __('Status') }}</th>
                                    <th class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            {{-- Table body --}}
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($orders as $order)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $order->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $order->user->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            {{ __("order_statuses.{$order->status}") }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                        <a href="{{ route('warehouse.orders.show', $order) }}" class="text-blue-600 hover:text-blue-900 font-semibold">
                                            {{ __('View Details & Deliver') }}
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center">{{ __('No confirmed orders to display.') }}</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>