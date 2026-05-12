<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Permissions</h2>
            @permission('create-permissions')
                <a href="{{ route('admin.permissions.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest">Add Permission</a>
            @endpermission
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 rounded-md bg-green-50 p-4 text-sm text-green-700">{{ session('status') }}</div>
            @endif

            <div class="overflow-hidden bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-300">Permission</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-300">Roles</th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase text-gray-500 dark:text-gray-300">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($permissions as $permission)
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                    <div class="font-medium">{{ $permission->display_name ?: $permission->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $permission->name }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $permission->roles_count }}</td>
                                <td class="px-6 py-4 text-right text-sm">
                                    @permission('update-permissions')
                                        <a href="{{ route('admin.permissions.edit', $permission) }}" class="text-indigo-600 dark:text-indigo-400">Edit</a>
                                    @endpermission
                                    @permission('delete-permissions')
                                        <form method="POST" action="{{ route('admin.permissions.destroy', $permission) }}" class="inline ms-3" onsubmit="return confirm('Delete this permission?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-red-600 dark:text-red-400">Delete</button>
                                        </form>
                                    @endpermission
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">No permissions found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $permissions->links() }}</div>
        </div>
    </div>
</x-app-layout>
