<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manage Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 dark:bg-green-800 text-green-700 dark:text-green-200 rounded-lg">{{ session('success') }}</div>
                    @endif

                    <form method="POST" action="{{ route('admin.settings.store') }}">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <x-input-label for="daily_iron_price" :value="__('Daily Iron Price (per kg)')" />
                                <x-text-input id="daily_iron_price" class="block mt-1 w-full" type="number" name="daily_iron_price" :value="$settings['daily_iron_price'] ?? ''" required autofocus />
                            </div>

                            <div class="flex items-center justify-end">
                                <x-primary-button>
                                    {{ __('Update Price') }}
                                </x-primary-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>