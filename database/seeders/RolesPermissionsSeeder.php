<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Permissions Create गर्ने
        $permissions = [
            'create-post',
            'edit-post',
            'delete-post',
            'manage-users'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 2. Roles Create गर्ने
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        // 3. Permissions Role लाई असाइन गर्ने (Optional)
        $adminRole->givePermissionTo(Permission::all());
        $userRole->givePermissionTo(['create-post', 'edit-post']);
    }
}
