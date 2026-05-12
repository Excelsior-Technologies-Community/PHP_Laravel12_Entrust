@csrf

<div class="grid gap-6 sm:grid-cols-2">
    <div>
        <x-input-label for="name" value="Permission Name" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $permission->name ?? '')" required />
        <x-input-error class="mt-2" :messages="$errors->get('name')" />
    </div>

    <div>
        <x-input-label for="display_name" value="Display Name" />
        <x-text-input id="display_name" name="display_name" type="text" class="mt-1 block w-full" :value="old('display_name', $permission->display_name ?? '')" />
        <x-input-error class="mt-2" :messages="$errors->get('display_name')" />
    </div>
</div>

<div class="mt-6">
    <x-input-label for="description" value="Description" />
    <x-text-input id="description" name="description" type="text" class="mt-1 block w-full" :value="old('description', $permission->description ?? '')" />
    <x-input-error class="mt-2" :messages="$errors->get('description')" />
</div>

<div class="mt-6 flex items-center gap-3">
    <x-primary-button>{{ $buttonText }}</x-primary-button>
    <a href="{{ route('admin.permissions.index') }}" class="text-sm text-gray-600 dark:text-gray-300">Cancel</a>
</div>
