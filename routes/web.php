<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
|
*/

// Home / Welcome page
Route::get('/', function () {
    return view('welcome');
});

// Dashboard for all authenticated users
Route::middleware(['auth', 'verified'])->group(function () {

    // User Dashboard (everyone with auth can access)
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Admin-only page
    Route::get('/admin-dashboard', [DashboardController::class, 'admin'])
        ->middleware('role:admin')
        ->name('admin.dashboard');

    Route::get('/admin/users', [UserController::class, 'index'])->middleware('permission:read-users')->name('admin.users.index');
    Route::get('/admin/users/create', [UserController::class, 'create'])->middleware('permission:create-users')->name('admin.users.create');
    Route::post('/admin/users', [UserController::class, 'store'])->middleware('permission:create-users')->name('admin.users.store');
    Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])->middleware('permission:update-users')->name('admin.users.edit');
    Route::put('/admin/users/{user}', [UserController::class, 'update'])->middleware('permission:update-users')->name('admin.users.update');
    Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->middleware('permission:delete-users')->name('admin.users.destroy');

    Route::get('/admin/roles', [RoleController::class, 'index'])->middleware('permission:read-roles')->name('admin.roles.index');
    Route::get('/admin/roles/create', [RoleController::class, 'create'])->middleware('permission:create-roles')->name('admin.roles.create');
    Route::post('/admin/roles', [RoleController::class, 'store'])->middleware('permission:create-roles')->name('admin.roles.store');
    Route::get('/admin/roles/{role}/edit', [RoleController::class, 'edit'])->middleware('permission:update-roles')->name('admin.roles.edit');
    Route::put('/admin/roles/{role}', [RoleController::class, 'update'])->middleware('permission:update-roles')->name('admin.roles.update');
    Route::delete('/admin/roles/{role}', [RoleController::class, 'destroy'])->middleware('permission:delete-roles')->name('admin.roles.destroy');

    Route::get('/admin/permissions', [PermissionController::class, 'index'])->middleware('permission:read-permissions')->name('admin.permissions.index');
    Route::get('/admin/permissions/create', [PermissionController::class, 'create'])->middleware('permission:create-permissions')->name('admin.permissions.create');
    Route::post('/admin/permissions', [PermissionController::class, 'store'])->middleware('permission:create-permissions')->name('admin.permissions.store');
    Route::get('/admin/permissions/{permission}/edit', [PermissionController::class, 'edit'])->middleware('permission:update-permissions')->name('admin.permissions.edit');
    Route::put('/admin/permissions/{permission}', [PermissionController::class, 'update'])->middleware('permission:update-permissions')->name('admin.permissions.update');
    Route::delete('/admin/permissions/{permission}', [PermissionController::class, 'destroy'])->middleware('permission:delete-permissions')->name('admin.permissions.destroy');

    Route::get('/products', [ProductController::class, 'index'])->middleware('permission:read-products')->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->middleware('permission:create-products')->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->middleware('permission:create-products')->name('products.store');
    Route::get('/products/{product}', [ProductController::class, 'show'])->middleware('permission:read-products')->name('products.show');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->middleware('permission:update-products')->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->middleware('permission:update-products')->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->middleware('permission:delete-products')->name('products.destroy');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
