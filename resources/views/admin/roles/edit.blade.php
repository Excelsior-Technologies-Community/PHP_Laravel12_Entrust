<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Edit Role</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 p-6 shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('admin.roles.update', $role) }}">
                    @method('PUT')
                    @include('admin.roles._form', ['buttonText' => 'Update Role'])
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
