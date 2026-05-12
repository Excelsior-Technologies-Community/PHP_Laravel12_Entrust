<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RoleController extends Controller
{
    public function index(): View
    {
        $roles = Role::withCount(['users', 'permissions'])->latest()->paginate(10);

        return view('admin.roles.index', compact('roles'));
    }

    public function create(): View
    {
        $permissions = Permission::orderBy('name')->get()->groupBy(fn ($permission) => Str::after($permission->name, '-'));

        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:roles,name'],
            'display_name' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'permissions' => ['array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'display_name' => $validated['display_name'] ?: Str::headline($validated['name']),
            'description' => $validated['description'],
        ]);

        $role->permissions()->sync($validated['permissions'] ?? []);

        return redirect()->route('admin.roles.index')->with('status', 'Role created successfully.');
    }

    public function edit(Role $role): View
    {
        $permissions = Permission::orderBy('name')->get()->groupBy(fn ($permission) => Str::after($permission->name, '-'));
        $selectedPermissions = $role->permissions()->pluck('permissions.id')->all();

        return view('admin.roles.edit', compact('role', 'permissions', 'selectedPermissions'));
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:roles,name,'.$role->id],
            'display_name' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'permissions' => ['array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);

        $role->update([
            'name' => $validated['name'],
            'display_name' => $validated['display_name'] ?: Str::headline($validated['name']),
            'description' => $validated['description'],
        ]);
        $role->permissions()->sync($validated['permissions'] ?? []);

        return redirect()->route('admin.roles.index')->with('status', 'Role updated successfully.');
    }

    public function destroy(Role $role): RedirectResponse
    {
        if ($role->name === 'admin') {
            return back()->with('status', 'Admin role cannot be deleted.');
        }

        $role->delete();

        return redirect()->route('admin.roles.index')->with('status', 'Role deleted successfully.');
    }
}
