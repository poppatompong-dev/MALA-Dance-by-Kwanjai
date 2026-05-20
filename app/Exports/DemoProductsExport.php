<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class DemoProductsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return collect([
            [
                'name' => 'หมูสามชั้นหม่าล่า',
                'sku' => 'IMPORT-MALA-PORK-BELLY',
                'description' => 'หมูสามชั้นเสียบไม้สำหรับขายหน้าร้าน',
                'category' => 'หม่าล่าเสียบไม้',
                'brand' => 'ครัวกลาง',
                'unit' => 'ไม้',
                'price' => 15,
                'discount' => 0,
                'discount_type' => 'fixed',
                'purchase_price' => 8,
                'quantity' => 100,
                'expire_date' => '',
                'status' => 1,
                'image' => 'assets/images/demo/products/mala-pork-belly.svg',
            ],
            [
                'name' => 'ชานมเย็น',
                'sku' => 'IMPORT-DRINK-THAI-MILK-TEA',
                'description' => 'เครื่องดื่มเย็นขายเป็นแก้ว',
                'category' => 'เครื่องดื่มเย็น',
                'brand' => 'เครื่องดื่มสด',
                'unit' => 'แก้ว',
                'price' => 35,
                'discount' => 0,
                'discount_type' => 'fixed',
                'purchase_price' => 15,
                'quantity' => 60,
                'expire_date' => '',
                'status' => 1,
                'image' => 'assets/images/demo/products/drink-thai-milk-tea.svg',
            ],
            [
                'name' => 'น้ำเปล่า',
                'sku' => 'IMPORT-DRINK-WATER',
                'description' => 'น้ำดื่มบรรจุขวดสำหรับขายหน้าร้าน',
                'category' => 'เครื่องดื่มเย็น',
                'brand' => 'หน้าร้าน',
                'unit' => 'ขวด',
                'price' => 10,
                'discount' => 0,
                'discount_type' => 'fixed',
                'purchase_price' => 5,
                'quantity' => 100,
                'expire_date' => '',
                'status' => 1,
                'image' => 'assets/images/demo/products/drink-water.svg',
            ],
        ]);
    }

    public function headings(): array
    {
        return ['name', 'sku', 'description', 'category', 'brand', 'unit', 'price', 'discount', 'discount_type', 'purchase_price', 'quantity', 'expire_date', 'status', 'image'];
    }
}
