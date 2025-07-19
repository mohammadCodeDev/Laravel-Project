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
                    {{-- ... Success message ... --}}

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">{{ __('Order ID') }}</th>
                                    <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">{{ __('Customer') }}</th>
                                    <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">{{ __('Total Price') }}</th>
                                    <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">{{ __('Date') }}</th>
                                    <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">{{ __('Status') }}</th>
                                    <th class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($orders as $order)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ $order->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $order->user->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ number_format($order->total_price, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $order->created_at->format('Y-m-d') }}</td>

                                    {{-- MODIFIED: Status Column with more details --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($order->status === 'confirmed')
                                        <span class="font-semibold text-blue-500">{{ __('Awaiting Warehouse') }}</span>
                                        @elseif($order->status === 'delivered' && $order->deliverer)
                                        <span class="font-semibold text-green-500">{{ __('Delivered by') }}: {{ $order->deliverer->name }}</span>
                                        @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    @switch($order->status)
                                                        @case('pending') bg-yellow-100 text-yellow-800 @break
                                                        @case('delivered') bg-green-100 text-green-800 @break
                                                    @endswitch">
                                            {{ __('order_status.' . $order->status) }}
                                        </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                        <div class="flex items-center justify-end gap-4">
                                            @if($order->status === 'pending')
                                            <form method="POST" action="{{ route('admin.orders.confirm', $order) }}">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="text-green-600 hover:text-green-900">{{ __('Confirm') }}</button>
                                            </form>
                                            @endif

                                            {{-- NEW: 3-dot button to open modal --}}
                                            <button type="button" class="view-items-btn text-gray-400 hover:text-gray-600"
                                                data-order-id="{{ $order->id }}"
                                                data-order-customer="{{ $order->user->name }}"
                                                data-order-items="{{ json_encode($order->items->map(fn($item) => ['name' => $item->product->name, 'quantity' => $item->quantity])) }}">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center">{{ __('No orders found.') }}</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>

    <div id="itemsModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-md">
            <div class="flex justify-between items-center border-b pb-3 dark:border-gray-700">
                <h3 id="modalTitle" class="text-lg font-semibold text-gray-900 dark:text-gray-100"></h3>
                <button id="closeModal" class="text-gray-400 hover:text-gray-600">&times;</button>
            </div>
            <div id="modalBody" class="mt-4 text-gray-800 dark:text-gray-200">
            </div>
        </div>
    </div>

</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('itemsModal');
        const closeModalBtn = document.getElementById('closeModal');
        const modalTitle = document.getElementById('modalTitle');
        const modalBody = document.getElementById('modalBody');
        const viewBtns = document.querySelectorAll('.view-items-btn');

        viewBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const orderId = this.dataset.orderId;
                const customerName = this.dataset.orderCustomer;
                const items = JSON.parse(this.dataset.orderItems);

                modalTitle.textContent = `{{ __('Order Items for Order #') }}${orderId} (${customerName})`;

                let content = '<ul class="list-disc space-y-2 ps-5">';
                if (items.length > 0) {
                    items.forEach(item => {
                        content += `<li>${item.name} ({{ __('Quantity') }}: ${item.quantity})</li>`;
                    });
                } else {
                    content += '<li>{{ __("No items in this order.") }}</li>';
                }
                content += '</ul>';
                modalBody.innerHTML = content;

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
    });
</script>