<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('welcome.dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 space-y-12">
        <section aria-labelledby="welcome-message" class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __('welcome.logged_in') }}
                    <div class="mt-4">
                        <a href="/" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900">{{ __('welcome.go_to_welcome') }}</a>
                    </div>
                </div>
            </div>
        </section>

        {{-- Admin Panels --}}
        @if(Auth::user()->role === 'admin')

        <section aria-labelledby="admin-actions-heading" class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 id="admin-actions-heading" class="font-semibold text-lg mb-4">{{ __('Admin Actions') }}</h3>
                    <div class="flex flex-wrap items-center gap-4">
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">{{ __('Manage Products') }}</a>
                        <a href="{{ route('admin.products.create') }}" class="btn btn-blue">{{ __('Add New Product') }}</a>
                        <a href="{{ route('admin.news.index') }}" class="btn btn-green">{{ __('Manage News') }}</a>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-purple">{{ __('Manage Orders') }}</a>
                        <a href="{{ route('admin.settings.index') }}" class="btn btn-yellow">{{ __('Manage Settings') }}</a>
                    </div>
                </div>
            </div>
        </section>


        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="font-semibold text-lg mb-4">{{ __('Manage User Roles') }}</h3>

                        <div class="space-y-4">
                            @foreach ($users as $user)
                            {{-- The manager cannot change his/her role. --}}
                            @if($user->id !== Auth::id())
                            <form method="POST" action="{{ route('admin.users.updateRole', $user) }}" class="flex items-center justify-between p-4 border dark:border-gray-700 rounded-lg">
                                @csrf
                                @method('PATCH')

                                <div>
                                    <span class="font-medium">{{ $user->name }}</span>
                                    <span class="text-sm text-gray-500 dark:text-gray-400 ml-2">({{ __('roles.' . $user->role) }})</span>
                                </div>

                                <div class="flex items-center gap-4">
                                    <select name="role" class="block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                        <option value="customer" @selected($user->role == 'customer')>{{ __('roles.customer') }}</option>
                                        <option value="warehouse_keeper" @selected($user->role == 'warehouse_keeper')>{{ __('roles.warehouse_keeper') }}</option>
                                        <option value="admin" @selected($user->role == 'admin')>{{ __('roles.admin') }}</option>
                                    </select>

                                    <x-primary-button>{{ __('Update') }}</x-primary-button>
                                </div>
                            </form>
                            @endif
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Warehouse Keeper Panel --}}
        @if(Auth::user()->role === 'warehouse_keeper')
        <section aria-labelledby="warehouse-actions-heading" class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 id="warehouse-actions-heading" class="font-semibold text-lg mb-4">{{ __('Warehouse Actions') }}</h3>
                    <div class="flex items-center gap-4">
                        <a href="{{ route('warehouse.orders.index') }}" class="btn btn-orange">{{ __('View Confirmed Orders') }}</a>
                    </div>
                </div>
            </div>
        </section>
        @endif

        {{-- Customer Panel --}}
        @if(Auth::user()->role === 'customer')
        <section aria-labelledby="customer-account-heading" class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 id="customer-account-heading" class="font-semibold text-lg mb-4">{{ __('My Account') }}</h3>
                    <div class="flex items-center gap-4">
                        <a href="{{ route('orders.index') }}" class="btn btn-indigo">{{ __('My Orders') }}</a>
                    </div>
                </div>
            </div>
        </section>
        @endif
    </div>
</x-app-layout>