<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('welcome.dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __('welcome.logged_in') }}

                    <!-- link to welcome page -->
                    <div class="mt-4">
                        <a href="/" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            {{ __('welcome.go_to_welcome') }}
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Admin Actions Panel --}}
    @if(Auth::user()->role === 'admin')
    <div class="pb-0 pt-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="font-semibold text-lg mb-4">{{ __('Admin Actions') }}</h3>
                    <div class="flex items-center gap-4">

                        {{-- Link to Product Management --}}
                        <a href="{{ route('admin.products.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-gray-800 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-500 focus:outline-none transition ease-in-out duration-150">
                            {{ __('Manage Products') }}
                        </a>

                        {{-- Link to Add New Product --}}
                        <a href="{{ route('admin.products.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 active:bg-blue-600 disabled:opacity-25 transition">
                            {{ __('Add New Product') }}
                        </a>

                        {{-- Link to News Management --}}
                        <a href="{{ route('admin.news.index') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 focus:outline-none focus:border-green-700 focus:ring focus:ring-green-200 active:bg-green-600 disabled:opacity-25 transition">
                            {{ __('Manage News') }}
                        </a>

                        {{-- Link to Order Management --}}
                        <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-500 focus:outline-none focus:border-purple-700 focus:ring focus:ring-purple-200 active:bg-purple-600 disabled:opacity-25 transition">
                            {{ __('Manage Orders') }}
                        </a>

                        <a href="{{ route('admin.settings.index') }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 ...">
                            {{ __('Manage Settings') }}
                        </a>

                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- User Role Management Panel --}}
    @if(Auth::user()->role === 'admin')
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

    {{-- Warehouse Keeper Actions Panel --}}
    @if(Auth::user()->role === 'warehouse_keeper')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="font-semibold text-lg mb-4">{{ __('Warehouse Actions') }}</h3>
                    <div class="flex items-center gap-4">
                        <a href="{{ route('warehouse.orders.index') }}" class="inline-flex items-center px-4 py-2 bg-orange-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-500 focus:outline-none focus:border-orange-700 focus:ring focus:ring-orange-200 active:bg-orange-600 disabled:opacity-25 transition">
                            {{ __('View Confirmed Orders') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Customer Actions Panel --}}
    @if(Auth::user()->role === 'customer')
    <div class="pb-0 pt-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="font-semibold text-lg mb-4">{{ __('My Account') }}</h3>
                    <div class="flex items-center gap-4">
                        <a href="{{ route('orders.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:ring focus:ring-indigo-200 active:bg-indigo-600 disabled:opacity-25 transition">
                            {{ __('My Orders') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

</x-app-layout>