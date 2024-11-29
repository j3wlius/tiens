<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing roles, permissions, and users
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('role_has_permissions')->truncate();
        DB::table('model_has_roles')->truncate();
        DB::table('model_has_permissions')->truncate();
        DB::table('roles')->truncate();
        DB::table('permissions')->truncate();
        DB::table('users')->truncate(); // Clear all users
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'manage roles',
            'view roles',
            'create roles',
            'edit roles',
            'delete roles',
            'manage users',
            'view users',
            'create users',
            'edit users',
            'delete users',
            'manage payments',
            'view payments',
            'create payments',
            'edit payments',
            'delete payments',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create Super Admin role and assign all permissions
        $superAdminRole = Role::create(['name' => 'super-admin']);
        $superAdminRole->givePermissionTo(Permission::all());

        // Create only the super admin user
        $superAdmin = User::create([
            'name' => 'Super Administrator',
            'email' => 'superadmin@admin.com',
            'password' => Hash::make('Admin@123#'),
            'email_verified_at' => now(),
        ]);

        $superAdmin->assignRole('super-admin');
    }
}
