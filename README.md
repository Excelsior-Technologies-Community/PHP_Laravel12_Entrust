# PHP_Laravel12_Entrust

This is a simple Laravel 12 project that implements Role-Based Access Control (RBAC) using the Laravel Entrust package.
The goal is to structure the project clearly, explain every step, and help you understand how roles/permissions work in Laravel.


### What You Will Build

- Laravel 12 project setup
- Authentication with Laravel Breeze
- Entrust package integration
- Roles & permissions (Admin, Subadmin)
- Middleware to protect routes
- Example pages for role/permission checks

### Project Overview

- This project allows you to manage users, roles, and permissions in a Laravel 12 application. It includes:


User Authentication

- Implemented using Laravel Breeze for login, registration, password reset, and profile management.

- Role & Permission Management

- Uses the Laravel Entrust package.

- Predefined roles: Admin (full access) and Subadmin (limited access).

- Permissions can be assigned per module, e.g., create-users, read-profile.


Middleware Protection

- Routes are protected using role and permission middleware.

- Example: Only Admin can access admin routes; subadmin sees limited dashboard features.


Dynamic Blade Display

- Navigation and page content dynamically change based on user roles and permissions.

- Example: Admin sees links to manage users, while subadmin sees only their allowed pages.


Database Seeder

- Creates default roles, permissions, and test users (admin@gmail.com, subadmin@gmail.com) for easy testing.


### Prerequisites

Before starting, make sure you have:

- PHP >= 8
- Composer
- Database (MySQL/PostgreSQL/SQLite)
- Node & npm for assets

---


## Step 1: Create Laravel 12 Project

Run this command:

```bash
composer create-project laravel/laravel PHP_Laravel12_Entrust "12.*" 
```

Go into project:

```
cd PHP_Laravel12_Entrust
```

---

## Step 2: Authentication Setup

Install Laravel Breeze for simple auth (login/register etc.):

composer require laravel/breeze --dev
php artisan breeze:install
npm install
npm run dev
php artisan migrate

---

## Step 3: Update .env

```.env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel12_entrust
DB_USERNAME=root
DB_PASSWORD=
```

Run this command to create database

```bash
php artisan migrate
```

---

## Step 4: Install Entrust Package

Install Entrust via Composer:

```
composer require shanmuga/laravel-entrust
```

Publish config files:

```
php artisan vendor:publish --tag="LaravelEntrust"
```

This generates:

config/entrust.php

---

## Step 5: Configure Entrust

Open config/entrust.php and ensure these keys:

// User model
'user_model' => 'App\Models\User',

// Tables
'tables' => [
    'roles' => 'roles',
    'permissions' => 'permissions',
    'role_user' => 'role_user',
    'permission_role' => 'permission_role',
],


Entrust needs relationships defined using pivot tables.

---

## Step 6: Generate Migrations & Seeders

Run setup command:

```
php artisan laravel-entrust:setup
```

This will create:

- roles
- permissions
- role_user
- permission_role migrations
- Seeder for default role/permissions

Next, run:

```
php artisan migrate
php artisan db:seed
```

---

## Step 6: Update User Model (Default)

Open app/Models/User.php and add the Entrust trait:

```
<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Shanmuga\LaravelEntrust\Traits\LaravelEntrustUserTrait;  // add this

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, LaravelEntrustUserTrait;    // add this

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
```

This gives you:

- roles()
- hasRole()
- hasPermission()
- ability() functions.

### Role.php

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Shanmuga\LaravelEntrust\Models\EntrustRole;

class Role extends EntrustRole
{
    use HasFactory;
}
```

### Permission.php

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Shanmuga\LaravelEntrust\Models\EntrustPermission;

class Permission extends EntrustPermission
{
    use HasFactory;
}
```

---

## Step 7: Roles, Permissions & Default Users

This project uses Laravel Entrust to manage roles and permissions.
Default roles, permissions, and test users are automatically created using the LaravelEntrustSeeder and the entrust_seeder.php configuration.

### 7.1 Entrust Seeder Configuration

The file config/entrust_seeder.php defines:

```php
<?php

return [
    'role_structure' => [
        'admin' => [
            'users' => 'c,r,u,d',
            'admin' => 'c,r,u,d',
            'profile' => 'c,r,u,d'
        ],
        'subadmin' => [
            'users' => 'r',         // Subadmin can only read users
            'profile' => 'r,u'      // Subadmin can read and update profile
        ],
    ],
    'user_roles' => [
        'admin' => [
            ['name' => "Admin", "email" => "admin@gmail.com", "password" => '123456'],
        ],
        'subadmin' => [
            ['name' => "Sub Admin", "email" => "subadmin@gmail.com", "password" => '123456'],
        ],
    ],
    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete',
    ],
];
```

Explanation:

role_structure → defines which permissions each role has for each module.

user_roles → defines default users for each role.

permissions_map → maps shorthand letters (c,r,u,d) to full permission names.


### 7.2 Seeder Class

database/seeders/LaravelEntrustSeeder.php  

```php
<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class LaravelEntrustSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('Truncating Roles, Permissions and Users tables');
        $this->truncateEntrustTables();

        $config = config('entrust_seeder.role_structure');
        $userRoles = config('entrust_seeder.user_roles');
        $mapPermission = collect(config('entrust_seeder.permissions_map'));

        foreach ($config as $key => $modules) {

            // Create a new role
            $role = \App\Models\Role::create([
                'name' => $key,
                'display_name' => ucwords(str_replace('_', ' ', $key)),
                'description' => ucwords(str_replace('_', ' ', $key))
            ]);
            $permissions = [];

            $this->command->info('Creating Role '. strtoupper($key));

            // Reading role permission modules
            foreach ($modules as $module => $value) {

                foreach (explode(',', $value) as $p => $perm) {

                    $permissionValue = $mapPermission->get($perm);

                    $permissions[] = \App\Models\Permission::firstOrCreate([
                        'name' => $permissionValue . '-' . $module,
                        'display_name' => ucfirst($permissionValue) . ' ' . ucwords(str_replace('_', ' ', $module)),
                        'description' => ucfirst($permissionValue) . ' ' . ucwords(str_replace('_', ' ', $module)),
                    ])->id;

                    $this->command->info('Creating Permission to '.$permissionValue.' for '. $module);
                }
            }

            // Attach all permissions to the role
            $role->permissions()->sync($permissions);

            if(isset($userRoles[$key])) {
                $this->command->info("Creating '{$key}' users");

                $role_users  = $userRoles[$key];

                foreach ($role_users as $role_user) {
                    if(isset($role_user["password"])) {
                        $role_user["password"] = Hash::make($role_user["password"]);
                    }
                    $user = \App\Models\User::create($role_user);
                    $user->attachRole($role);
                }
            }
        }
    }

    /**
     * Truncates all the entrust tables and the users table
     *
     * @return  void
     */
    public function truncateEntrustTables()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('permission_role')->truncate();
        DB::table('role_user')->truncate();
        DB::table('users')->truncate();

        \App\Models\Role::truncate();
        \App\Models\Permission::truncate();

        Schema::enableForeignKeyConstraints();
    }
}
```

The LaravelEntrustSeeder handles:

- Truncating old roles, permissions, and users.

- Creating roles (admin, subadmin).

- Creating permissions (create-users, read-profile, etc.) and attaching them to roles.

- Creating default users and assigning the correct role.


7.3 Register the Seeder

In database/seeders/DatabaseSeeder.php, the seeder is called:

```php
<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call the Entrust seeder
        $this->call([
            LaravelEntrustSeeder::class,
        ]);
    }
}
```

7.4 Run the Seeder

```
php artisan db:seed
```

After running this:

Roles Table → admin, subadmin.

Permissions Table → all module permissions like create-users, read-profile.

role_user & permission_role → pivot tables populated.

Users Table → default users created (admin@gmail.com, subadmin@gmail.com).

---

## Step 8: Protect Routes with Middleware

Entrust provides two middleware types:

role → Only users with a specific role can access the route.

permission → Only users with a specific permission can access the route.

routes/web.php


```php
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
```

---

## Step 9: Dashboard Controller & Blade Views (Role-Based UI)

In this step, we create dashboard pages and control what Admin and Subadmin users can see using Entrust roles and permissions.


### 9.1 Create Dashboard Controller

Create a controller:

```bash
php artisan make:controller DashboardController
```

Open app/Http/Controllers/DashboardController.php:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Dashboard accessible by Admin & Subadmin
     */
    public function index()
    {
        return view('dashboard.index');
    }

    /**
     * Admin-only dashboard
     */
    public function admin()
    {
        return view('dashboard.admin');
    }
}
```

Explanation:

index() → accessible by both Admin & Subadmin

admin() → accessible only by Admin (protected by role:admin middleware)


### 9.2 Create Dashboard Blade Files

Create folder:

resources/views/dashboard

dashboard/index.blade.php (Admin + Subadmin)

```
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-white">
            <h3>Welcome, {{ Auth::user()->name }}</h3>
            <p>This is the main dashboard accessible by both Admin and Subadmin.</p>
        </div>
    </div>
</div>

</x-app-layout>
```

dashboard/admin.blade.php (Admin only)

```
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Panel') }}
        </h2>
    </x-slot>

 <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h3 class="text-white font-semibold text-lg">
                Welcome, Admin!
            </h3>
            <p class="text-white">
                Only Admin can see this page and manage everything.
            </p>
        </div>
    </div>
</div>

</x-app-layout>
```

### 9.3 Role-Based Links in Navigation (Blade)

Edit:

resources/views/layouts/navigation.blade.php


Add Admin-only menu using Entrust Blade directives:

```
<!-- Navigation Links -->
 <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
     <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
           {{ __('Dashboard') }}
     </x-nav-link>

 <!-- Admin Panel link visible only to admin -->
 @role('admin')
 <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
       {{ __('Admin Panel') }}
 </x-nav-link>
@endrole
</div>
```
Explanation:

@role('admin') → only Admin sees this link

Subadmin will never see Admin Panel, even if they try the URL (middleware blocks it)


---

## Step 10: Start Development Server

Open two terminals

Terminal 1

```bash
php artisan serve
```
Terminal 2

```bash
npm run dev
```

Both must be running


Login Using Test Users

Admin Login:

```
Email: admin@gmail.com
Password: 123456
```

Can access:

- Dashboard

- Admin Panel

All features


Subadmin Login

```
Email: subadmin@gmail.com
Password: 123456
```

Can access:

- Dashboard only

Cannot access:

- Admin Panel (hidden + blocked)

---

## Project Structure

```
PHP_Laravel12_Entrust
│
├── app
│   ├── Http
│   │   └── Controllers
│   │       ├── Auth
│   │       ├── ProfileController.php
│   │       └── DashboardController.php   # Admin & Subadmin dashboards
│   │
│   ├── Models
│   │   ├── User.php        # Uses LaravelEntrustUserTrait
│   │   ├── Role.php        # Extends EntrustRole
│   │   └── Permission.php # Extends EntrustPermission
│   │
│   └── Providers
│       └── AppServiceProvider.php
│
├── bootstrap
│   └── app.php
│
├── config
│   ├── app.php
│   ├── auth.php
│   ├── database.php
│   ├── entrust.php             # Entrust configuration
│   ├── entrust_seeder.php      # Role, permission & user setup
│   ├── filesystems.php
│   ├── logging.php
│   ├── mail.php
│   ├── queue.php
│   ├── services.php
│   └── session.php
│
├── database
│   ├── factories
│   │
│   ├── migrations
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 0001_01_01_000001_create_cache_table.php
│   │   ├── 0001_01_01_000002_create_jobs_table.php
│   │   └── 2026_01_09_051151_laravel_entrust_setup_tables.php
│   │       # creates:
│   │       # roles
│   │       # permissions
│   │       # role_user
│   │       # permission_role
│   │
│   ├── seeders
│   │   ├── DatabaseSeeder.php
│   │   └── LaravelEntrustSeeder.php
│   │
│   └── database.sqlite / MySQL
│
├── resources
│   ├── views
│   │   ├── auth
│   │   │   ├── login.blade.php
│   │   │   ├── register.blade.php
│   │   │   └── forgot-password.blade.php
│   │   │
│   │   ├── dashboard
│   │   │   ├── index.blade.php   # Admin + Subadmin
│   │   │   └── admin.blade.php   # Admin only
│   │   │
│   │   ├── layouts
│   │   │   ├── app.blade.php
│   │   │   ├── guest.blade.php
│   │   │   └── navigation.blade.php  # Role-based links
│   │   │
│   │   ├── components
│   │   │   ├── app-layout.blade.php
│   │   │   └── guest-layout.blade.php
│   │   │
│   │   └── welcome.blade.php
│   │
│   ├── css
│   └── js
│
├── routes
│   ├── web.php        # Role & permission protected routes
│   └── auth.php
│
├── storage
│   └── logs
│
├── tests
│
├── .env
├── composer.json
├── package.json
├── README.md
└── vite.config.js
```

---

## Output

### Sub Admin

<img width="1903" height="1036" alt="Screenshot 2026-01-09 121106" src="https://github.com/user-attachments/assets/ca851f2d-9d3f-4cd8-ab86-679996142b3f" />

### Sub Admin 403 Access Page Permission (Admin Panel)

<img width="1919" height="1030" alt="Screenshot 2026-01-09 121403" src="https://github.com/user-attachments/assets/bbdb1c21-fe99-4298-9e39-8635a1b318cf" />

### Admin

<img width="1919" height="1026" alt="Screenshot 2026-01-09 121146" src="https://github.com/user-attachments/assets/f50a0f29-9279-4d5b-a041-ba3556ca40e9" />

<img width="1919" height="1011" alt="Screenshot 2026-01-09 121254" src="https://github.com/user-attachments/assets/4a7a300c-70c0-4426-acb3-ed1b59086aa7" />

---

Your PHP_Laravel12_Entrust Project is Now Ready!
