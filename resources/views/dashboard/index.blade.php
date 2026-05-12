<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

<div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Welcome, {{ Auth::user()->name }}</h3>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Your dashboard shows only the areas you have permission to access.</p>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @permission('read-users')
                <a href="{{ route('admin.users.index') }}" class="bg-white dark:bg-gray-800 p-5 shadow-sm sm:rounded-lg">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Users</p>
                    <p class="mt-2 text-3xl font-semibold text-gray-900 dark:text-gray-100">{{ $totalUsers }}</p>
                </a>
            @endpermission

            @permission('read-roles')
                <a href="{{ route('admin.roles.index') }}" class="bg-white dark:bg-gray-800 p-5 shadow-sm sm:rounded-lg">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Roles</p>
                    <p class="mt-2 text-3xl font-semibold text-gray-900 dark:text-gray-100">{{ $totalRoles }}</p>
                </a>
            @endpermission

            @permission('read-permissions')
                <a href="{{ route('admin.permissions.index') }}" class="bg-white dark:bg-gray-800 p-5 shadow-sm sm:rounded-lg">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Permissions</p>
                    <p class="mt-2 text-3xl font-semibold text-gray-900 dark:text-gray-100">{{ $totalPermissions }}</p>
                </a>
            @endpermission

            @permission('read-products')
                <a href="{{ route('products.index') }}" class="bg-white dark:bg-gray-800 p-5 shadow-sm sm:rounded-lg">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Products</p>
                    <p class="mt-2 text-3xl font-semibold text-gray-900 dark:text-gray-100">{{ $totalProducts }}</p>
                </a>
            @endpermission
        </div>
    </div>
</div>

</x-app-layout>
