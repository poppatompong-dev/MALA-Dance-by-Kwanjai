<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $customers = [
            ['name' => 'ลูกค้าหน้าร้าน', 'phone' => '0800000000', 'address' => 'ซื้อหน้าร้าน'],
            ['name' => 'ลูกค้าประจำ', 'phone' => '0801111111', 'address' => 'พื้นที่ใกล้ร้าน'],
            ['name' => 'ออเดอร์เดลิเวอรี', 'phone' => '0802222222', 'address' => 'ช่องทางเดลิเวอรี'],
        ];

        foreach ($customers as $customer) {
            Customer::updateOrCreate(
                ['phone' => $customer['phone']],
                $customer
            );
        }
    }
}
