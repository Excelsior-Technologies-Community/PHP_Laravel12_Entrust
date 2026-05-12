@csrf

<div class="grid gap-6 sm:grid-cols-2">
    <div>
        <x-input-label for="name" value="Role Name" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $role->name ?? '')" required />
        <x-input-error class="mt-2" :messages="$errors->get('name')" />
    </div>

    <div>
        <x-input-label for="display_name" value="Display Name" />
        <x-text-input id="display_name" name="display_name" type="text" class="mt-1 block w-full" :value="old('display_name', $role->display_name ?? '')" />
        <x-input-error class="mt-2" :messages="$errors->get('display_name')" />
    </div>
</div>

<div class="mt-6">
    <x-input-label for="description" value="Description" />
    <x-text-input id="description" name="description" type="text" class="mt-1 block w-full" :value="old('description', $role->description ?? '')" />
    <x-input-error class="mt-2" :messages="$errors->get('description')" />
</div>

<div class="mt-6">
    <x-input-label value="Permissions" />
    <div class="mt-3 space-y-4">
        @foreach ($permissions as $module => $modulePermissions)
            <div class="rounded-md border border-gray-200 p-4 dark:border-gray-700">
                <h3 class="mb-3 text-sm font-semibold uppercase text-gray-700 dark:text-gray-200">{{ $module }}</h3>
                <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($modulePermissions as $permission)
                        <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-200">
                            <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" @checked(in_array($permission->id, old('permissions', $selectedPermissions ?? []))) class="rounded border-gray-300">
                            <span>{{ $permission->display_name ?: $permission->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
    <x-input-error class="mt-2" :messages="$errors->get('permissions')" />
</div>

<div class="mt-6 flex items-center gap-3">
    <x-primary-button>{{ $buttonText }}</x-primary-button>
    <a href="{{ route('admin.roles.index') }}" class="text-sm text-gray-600 dark:text-gray-300">Cancel</a>
</div>
