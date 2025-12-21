<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'manage users',
            'manage posts',
            'manage events',
            'manage courses',
            'manage subscriptions',
            'view posts',
            'create posts',
            'edit posts',
            'delete posts',
            'view events',
            'create events',
            'edit events',
            'delete events',
            'view courses',
            'create courses',
            'edit courses',
            'delete courses',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        $instructorRole = Role::create(['name' => 'instructor']);
        $instructorRole->givePermissionTo([
            'view courses',
            'create courses',
            'edit courses',
            'view posts',
            'create posts',
        ]);

        $memberRole = Role::create(['name' => 'member']);
        $memberRole->givePermissionTo([
            'view posts',
            'view events',
            'view courses',
        ]);

        Role::create(['name' => 'user']);
    }
}
