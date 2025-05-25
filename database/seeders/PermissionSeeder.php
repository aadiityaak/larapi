<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'menu:dashboard',
            'menu:customers',
            'menu:orders',
            'menu:jobdesks',
            'menu:products',
            'menu:users',
            'menu:roles',
            'menu:settings',

            'customer:create',
            'customer:read',
            'customer:update',
            'customer:delete',

            'order:create',
            'order:read',
            'order:update',
            'order:delete',

            'jobdesk:create',
            'jobdesk:read',
            'jobdesk:update',
            'jobdesk:delete',

            'product:create',
            'product:read',
            'product:update',
            'product:delete',

            'user:create',
            'user:read',
            'user:update',
            'user:delete',

            'role:create',
            'role:read',
            'role:update',
            'role:delete',

            'access:keuangan',

            'setting:update',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // Buat role admin
        $admin = Role::firstOrCreate(['name' => 'admin']);

        // Assign semua permission yang sudah ada ke role admin
        $admin->givePermissionTo(Permission::all());
    }
}
