<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $suppliers = [
            ['name' => 'ร้านวัตถุดิบสด', 'phone' => '0811111111', 'address' => 'ตลาดสด'],
            ['name' => 'ร้านเครื่องดื่มและบรรจุภัณฑ์', 'phone' => '0812222222', 'address' => 'โซนค้าส่ง'],
            ['name' => 'ร้านซอสและเครื่องปรุง', 'phone' => '0813333333', 'address' => 'แหล่งวัตถุดิบประจำ'],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::updateOrCreate(
                ['phone' => $supplier['phone']],
                $supplier
            );
        }
    }
}
