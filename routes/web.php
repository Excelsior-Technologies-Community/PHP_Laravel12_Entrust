<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
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
        ->middleware('permission:read-users') // Subadmin has read access
        ->name('dashboard');

    // Admin-only page
    Route::get('/admin-dashboard', [DashboardController::class, 'admin'])
        ->middleware('role:admin')
        ->name('admin.dashboard');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
