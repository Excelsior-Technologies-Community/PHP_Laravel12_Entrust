<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Roles</h2>
            @permission('create-roles')
                <a href="{{ route('admin.roles.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest">Add Role</a>
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
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-300">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-300">Users</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-300">Permissions</th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase text-gray-500 dark:text-gray-300">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($roles as $role)
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                    <div class="font-medium">{{ $role->display_name ?: $role->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $role->name }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $role->users_count }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $role->permissions_count }}</td>
                                <td class="px-6 py-4 text-right text-sm">
                                    @permission('update-roles')
                                        <a href="{{ route('admin.roles.edit', $role) }}" class="text-indigo-600 dark:text-indigo-400">Edit</a>
                                    @endpermission
                                    @permission('delete-roles')
                                        <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" class="inline ms-3" onsubmit="return confirm('Delete this role?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-red-600 dark:text-red-400">Delete</button>
                                        </form>
                                    @endpermission
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">No roles found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $roles->links() }}</div>
        </div>
    </div>
</x-app-layout>
