<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.index', [
            'totalUsers' => User::count(),
            'totalRoles' => Role::count(),
            'totalPermissions' => Permission::count(),
            'totalProducts' => Product::count(),
        ]);
    }

    public function admin()
    {
        return view('dashboard.admin');
    }
}
