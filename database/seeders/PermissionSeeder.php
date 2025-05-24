<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'menu:dashboard',
            'menu:customers',
            'menu:orders',
            'menu:products',
            'menu:users',
            'menu:roles',
            'menu:settings',

            'customer:read',
            'customer:update',
            'customer:delete',

            'order:read',
            'order:update',
            'order:delete',

            'product:read',
            'product:update',
            'product:delete',

            'user:read',
            'user:update',
            'user:delete',

            'role:read',
            'role:update',
            'role:delete',

            'setting:update',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }
    }
}
