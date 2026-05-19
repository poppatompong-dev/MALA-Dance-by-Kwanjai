<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
use App\Models\Supplier;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class StartUpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $user = User::create([
            'name' => 'ผู้ดูแลร้าน',
            'email' => 'demo@qtecsolution.net',
            'password' => bcrypt(87654321),
            'username' => uniqid()
        ]);
        Customer::create([
            'name' => "ลูกค้าหน้าร้าน",
            'phone' => "0800000000",
        ]);
        Supplier::create([
            'name' => "ร้านวัตถุดิบหม่าล่า",
            'phone' => "0810000000",
        ]);
        $role = Role::create(['name' => 'Admin']);
        $user->syncRoles($role);
        $this->call([
            UnitSeeder::class,
            CurrencySeeder::class,
            RolePermissionSeeder::class,
        ]);
    }
}
