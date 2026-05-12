@csrf

<div class="grid gap-6 sm:grid-cols-2">
    <div>
        <x-input-label for="name" value="Name" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name ?? '')" required autofocus />
        <x-input-error class="mt-2" :messages="$errors->get('name')" />
    </div>

    <div>
        <x-input-label for="email" value="Email" />
        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email ?? '')" required />
        <x-input-error class="mt-2" :messages="$errors->get('email')" />
    </div>

    <div>
        <x-input-label for="password" value="Password" />
        <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" :required="! isset($user)" />
        <x-input-error class="mt-2" :messages="$errors->get('password')" />
    </div>

    <div>
        <x-input-label for="password_confirmation" value="Confirm Password" />
        <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" :required="! isset($user)" />
    </div>
</div>

<div class="mt-6">
    <x-input-label value="Roles" />
    <div class="mt-3 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($roles as $role)
            <label class="flex items-center gap-2 rounded-md border border-gray-200 p-3 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-200">
                <input type="checkbox" name="roles[]" value="{{ $role->id }}" @checked(in_array($role->id, old('roles', $selectedRoles ?? []))) class="rounded border-gray-300">
                <span>{{ $role->display_name ?: $role->name }}</span>
            </label>
        @endforeach
    </div>
    <x-input-error class="mt-2" :messages="$errors->get('roles')" />
</div>

<div class="mt-6 flex items-center gap-3">
    <x-primary-button>{{ $buttonText }}</x-primary-button>
    <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-600 dark:text-gray-300">Cancel</a>
</div>
