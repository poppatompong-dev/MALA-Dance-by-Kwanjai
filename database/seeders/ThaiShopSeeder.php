<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Unit;
use App\Models\Product;
use Illuminate\Support\Str;

class ThaiShopSeeder extends Seeder
{
    public function run(): void
    {
        $unit = Unit::firstOrCreate(['title' => 'ไม้', 'short_name' => 'ไม้']);
        $unitBowl = Unit::firstOrCreate(['title' => 'ชาม', 'short_name' => 'ชาม']);
        $brand = Brand::firstOrCreate(['name' => 'MALA Dance', 'status' => 1]);

        $categories = [
            'เนื้อสัตว์' => [
                ['name' => 'หมูสามชั้น', 'price' => 15],
                ['name' => 'เนื้อวัวสไลด์', 'price' => 20],
                ['name' => 'ไส้กรอกไก่', 'price' => 10],
                ['name' => 'เบคอนพันเห็ดเข็มทอง', 'price' => 15],
            ],
            'ซีฟู้ด' => [
                ['name' => 'กุ้งสด', 'price' => 20],
                ['name' => 'ปลาหมึกกรอบ', 'price' => 15],
                ['name' => 'ลูกชิ้นปลา', 'price' => 10],
            ],
            'ผักและเห็ด' => [
                ['name' => 'เห็ดออรินจิ', 'price' => 10],
                ['name' => 'ข้าวโพดหวาน', 'price' => 10],
                ['name' => 'ฟองเต้าหู้', 'price' => 10],
                ['name' => 'รากบัว', 'price' => 10],
            ],
            'เมนูพิเศษ' => [
                ['name' => 'หม่าล่าทั่ง (ชามใหญ่)', 'price' => 159, 'unit' => $unitBowl->id],
            ]
        ];

        foreach ($categories as $catName => $items) {
            $category = Category::firstOrCreate(['name' => $catName, 'status' => 1]);

            foreach ($items as $item) {
                Product::firstOrCreate([
                    'name' => $item['name']
                ],[
                    'sku' => strtoupper(Str::random(6)),
                    'category_id' => $category->id,
                    'brand_id' => $brand->id,
                    'unit_id' => $item['unit'] ?? $unit->id,
                    'purchase_price' => $item['price'] * 0.5,
                    'price' => $item['price'],
                    'discount_type' => 'percentage',
                    'discount' => 0,
                    'quantity' => 500,
                    'status' => 1,
                ]);
            }
        }
    }
}
