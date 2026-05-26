<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
use App\Models\Supplier;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class StartUpSeeder extends Seeder
{
    public function run(): void
    {
        $role = Role::firstOrCreate(['name' => 'Admin']);

        $admin = User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('admin'),
                'username' => 'admin',
            ]
        );
        $admin->syncRoles($role);

        $kwan = User::updateOrCreate(
            ['email' => 'kwan@kwan.com'],
            [
                'name' => 'Kwan',
                'password' => bcrypt('kwan'),
                'username' => 'kwan',
            ]
        );
        $kwan->syncRoles($role);

        Customer::firstOrCreate(
            ['phone' => '0800000000'],
            ['name' => 'ลูกค้าหน้าร้าน']
        );
        Supplier::firstOrCreate(
            ['phone' => '0810000000'],
            ['name' => 'ร้านวัตถุดิบหม่าล่า']
        );

        $this->call([
            UnitSeeder::class,
            CurrencySeeder::class,
            RolePermissionSeeder::class,
        ]);
    }
}
