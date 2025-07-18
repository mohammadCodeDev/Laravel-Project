<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Product List') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="flex justify-end mb-4">
                <a href="{{ route('admin.products.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 active:bg-blue-600 disabled:opacity-25 transition">
                    {{ __('Add New Product') }}
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if(session('success'))
                    <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                        {{ session('success') }}
                    </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    {{-- The translated headers --}}
                                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">{{ __('Image') }}</th>
                                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">{{ __('Name') }}</th>
                                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">{{ __('Price') }}</th>
                                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">{{ __('Stock') }}</th>
                                    <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($products as $product)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                        @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="h-12 w-12 object-cover rounded">
                                        @else
                                        <span class="text-xs">{{ __('No Image') }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{ $product->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{ number_format($product->price, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{ $product->stock }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                        {{-- The translated action links --}}
                                        <a href="#" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-600">{{ __('Edit') }}</a>

                                        {{-- Delete Button Form --}}
                                        <form method="POST"
                                            action="{{ route('admin.products.destroy', $product) }}"
                                            class="inline-block ms-4 delete-form"
                                            data-confirm-message="{{ __('Are you sure you want to delete this product?') }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-600">
                                                {{ __('Delete') }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500 dark:text-gray-400">
                                        {{ __('No products found.') }}
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $products->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>


<script>
    // Wait for the DOM to be fully loaded before running the script
    document.addEventListener('DOMContentLoaded', function () {
        
        // Find all forms with the 'delete-form' class
        const deleteForms = document.querySelectorAll('.delete-form');

        // Loop through each form and attach an event listener
        deleteForms.forEach(form => {
            form.addEventListener('submit', function (event) {
                // Prevent the form from submitting immediately
                event.preventDefault();

                // Get the confirmation message from the form's data-attribute
                const message = form.dataset.confirmMessage;

                // Show the confirmation dialog
                if (confirm(message)) {
                    // If the user clicks "OK", submit the form
                    this.submit();
                }
            });
        });
    });
</script>

