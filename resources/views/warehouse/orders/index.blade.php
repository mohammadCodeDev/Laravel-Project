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
                    @elseif(session('error'))
                        <div class="mb-4 font-medium text-sm text-red-600 dark:text-red-400">{{ session('error') }}</div>
                    @endif

                    {{-- THIS IS THE TABLE THAT WAS MISSING --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">{{ __('Order ID') }}</th>
                                    <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">{{ __('Customer') }}</th>
                                    <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">{{ __('Status') }}</th>
                                    <th class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($orders as $order)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $order->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $order->user->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($order->status === 'delivered')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-200 text-gray-800">
                                            {{ __('order_status.delivered') }}
                                        </span>
                                        @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            {{ __('order_status.confirmed') }}
                                        </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                        @if($order->status === 'confirmed')
                                            <button type="button" class="view-details-btn text-blue-600 hover:text-blue-900 font-semibold"
                                                    data-order-id="{{ $order->id }}"
                                                    data-order-items="{{ json_encode($order->items->map(fn($item) => ['name' => $item->product->name, 'quantity' => $item->quantity])) }}"
                                                    data-action-url="{{ route('warehouse.orders.deliver', $order) }}">
                                                {{ __('View Details & Deliver') }}
                                            </button>
                                        @endif
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

    <div id="detailsModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-2xl">
            <div class="flex justify-between items-center border-b pb-3 dark:border-gray-700">
                <h3 id="modalTitle" class="text-lg font-semibold text-gray-900 dark:text-gray-100"></h3>
                <button id="closeModal" class="text-gray-400 hover:text-gray-600">&times;</button>
            </div>
            <div id="modalBody" class="mt-4 text-gray-800 dark:text-gray-200 max-h-96 overflow-y-auto"></div>
            <div class="mt-6 pt-4 border-t dark:border-gray-700 text-center">
                <form id="deliverForm" method="POST" action="">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="w-full px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                        {{ __('Confirm and Finalize Delivery') }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    
</x-app-layout>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('detailsModal');
            if (modal) {
                const closeModalBtn = document.getElementById('closeModal');
                const modalTitle = document.getElementById('modalTitle');
                const modalBody = document.getElementById('modalBody');
                const deliverForm = document.getElementById('deliverForm');
                const viewBtns = document.querySelectorAll('.view-details-btn');

                viewBtns.forEach(btn => {
                    btn.addEventListener('click', function() {
                        const orderId = this.dataset.orderId;
                        const items = JSON.parse(this.dataset.orderItems);
                        const actionUrl = this.dataset.actionUrl;
                        modalTitle.textContent = `{{ __('Order Details for Order #') }}${orderId}`;
                        let content = '<div class="space-y-3">';
                        if (items.length > 0) {
                            items.forEach(item => {
                                content += `<div class="flex justify-between items-center p-2 border-b dark:border-gray-700"><span>${item.name}</span><span class="font-mono bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">{{ __('Quantity') }}: ${item.quantity}</span></div>`;
                            });
                        } else {
                            content += '<p>{{ __("No items in this order.") }}</p>';
                        }
                        content += '</div>';
                        modalBody.innerHTML = content;
                        deliverForm.action = actionUrl;
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
                    if (event.target === modal) {
                        closeModal();
                    }
                });
            }
        });
    </script>