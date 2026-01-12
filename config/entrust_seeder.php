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
