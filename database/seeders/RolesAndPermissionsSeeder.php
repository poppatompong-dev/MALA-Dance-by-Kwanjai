<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $owner = Role::firstOrCreate(['name' => 'Owner', 'guard_name' => 'web']);
        $admin = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $cashier = Role::firstOrCreate(['name' => 'Cashier', 'guard_name' => 'web']);

        $permissions = [
            'dashboard_view',
            'pos_access',
            'order_void',
            'inventory_manage',
            'reports_sales',
            'reports_inventory',
            'reports_summary',
            'settings_manage',
            'users_manage',
            // Default ones that might exist
            'purchase_view',
            'purchase_create',
            'purchase_update',
            'purchase_delete'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $owner->syncPermissions(Permission::all());
        
        $admin->syncPermissions([
            'dashboard_view', 'pos_access', 'order_void', 'inventory_manage', 
            'reports_sales', 'reports_inventory', 'reports_summary',
            'purchase_view', 'purchase_create', 'purchase_update', 'purchase_delete'
        ]);
        
        $cashier->syncPermissions([
            'pos_access'
        ]);
    }
}
