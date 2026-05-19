<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $categories = [
            'หม่าล่าเสียบไม้',
            'ชุดหม่าล่า',
            'เครื่องดื่มเย็น',
            'เครื่องดื่มปั่น',
            'ท็อปปิ้ง',
            'ซอส/น้ำจิ้ม',
            'ของทานเล่น',
        ];

        $brands = [
            'ครัวกลาง',
            'หน้าร้าน',
            'เครื่องดื่มสด',
            'วัตถุดิบประจำร้าน',
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category],
                [
                    'image' => '',
                    'description' => 'หมวดสินค้าสำหรับร้านหม่าล่าและเครื่องดื่ม',
                    'status' => true,
                ]
            );
        }

        foreach ($brands as $brand) {
            Brand::updateOrCreate(
                ['name' => $brand],
                [
                    'image' => '',
                    'description' => 'แบรนด์สินค้าเดโมสำหรับหน้าร้าน',
                    'status' => true,
                ]
            );
        }

        $products = [
            ['name' => 'หมูสามชั้นหม่าล่า', 'sku' => 'MALA-PORK-BELLY', 'category' => 'หม่าล่าเสียบไม้', 'unit' => 'ไม้', 'price' => 15, 'purchase_price' => 8, 'quantity' => 100, 'brand' => 'ครัวกลาง', 'description' => 'หมูสามชั้นเสียบไม้สำหรับย่าง โรยผงหม่าล่าหอมเผ็ดกำลังดี'],
            ['name' => 'เนื้อวัวหม่าล่า', 'sku' => 'MALA-BEEF', 'category' => 'หม่าล่าเสียบไม้', 'unit' => 'ไม้', 'price' => 20, 'purchase_price' => 12, 'quantity' => 80, 'brand' => 'ครัวกลาง', 'description' => 'เนื้อวัวหมักพร้อมย่าง เหมาะสำหรับลูกค้าที่ชอบรสเข้ม'],
            ['name' => 'ไส้กรอกชีส', 'sku' => 'MALA-CHEESE-SAUSAGE', 'category' => 'หม่าล่าเสียบไม้', 'unit' => 'ไม้', 'price' => 15, 'purchase_price' => 7, 'quantity' => 120, 'brand' => 'ครัวกลาง', 'description' => 'ไส้กรอกชีสเสียบไม้ ย่างร้อนแล้วชีสเยิ้มขายง่าย'],
            ['name' => 'เห็ดเข็มทอง', 'sku' => 'MALA-ENOKI', 'category' => 'หม่าล่าเสียบไม้', 'unit' => 'ไม้', 'price' => 12, 'purchase_price' => 5, 'quantity' => 100, 'brand' => 'วัตถุดิบประจำร้าน', 'description' => 'เห็ดเข็มทองเสียบไม้สำหรับย่าง ทานคู่ซอสหม่าล่าได้ดี'],
            ['name' => 'เต้าหู้ปลา', 'sku' => 'MALA-FISH-TOFU', 'category' => 'หม่าล่าเสียบไม้', 'unit' => 'ไม้', 'price' => 15, 'purchase_price' => 7, 'quantity' => 100, 'brand' => 'วัตถุดิบประจำร้าน', 'description' => 'เต้าหู้ปลาเสียบไม้ เนื้อนุ่ม ย่างไว เหมาะกับทุกวัย'],
            ['name' => 'ชุดหม่าล่าเล็ก', 'sku' => 'SET-MALA-S', 'category' => 'ชุดหม่าล่า', 'unit' => 'ชุด', 'price' => 99, 'purchase_price' => 55, 'quantity' => 30, 'brand' => 'หน้าร้าน', 'description' => 'ชุดรวมไม้หม่าล่าสำหรับทานคนเดียว พร้อมน้ำจิ้มประจำร้าน'],
            ['name' => 'ชุดหม่าล่ากลาง', 'sku' => 'SET-MALA-M', 'category' => 'ชุดหม่าล่า', 'unit' => 'ชุด', 'price' => 159, 'purchase_price' => 90, 'quantity' => 25, 'brand' => 'หน้าร้าน', 'description' => 'ชุดรวมไม้หม่าล่าสำหรับแบ่งกันสองถึงสามคน พร้อมน้ำจิ้มประจำร้าน'],
            ['name' => 'ชุดหม่าล่าใหญ่', 'sku' => 'SET-MALA-L', 'category' => 'ชุดหม่าล่า', 'unit' => 'ชุด', 'price' => 239, 'purchase_price' => 140, 'quantity' => 20, 'brand' => 'หน้าร้าน', 'description' => 'ชุดรวมไม้หม่าล่าขนาดใหญ่สำหรับกลุ่มเพื่อนหรือครอบครัว'],
            ['name' => 'ชานมเย็น', 'sku' => 'DRINK-THAI-MILK-TEA', 'category' => 'เครื่องดื่มเย็น', 'unit' => 'แก้ว', 'price' => 35, 'purchase_price' => 15, 'quantity' => 60, 'brand' => 'เครื่องดื่มสด', 'description' => 'ชานมเย็นชงสด หวานมัน ดับเผ็ดหลังทานหม่าล่า'],
            ['name' => 'ชามะนาว', 'sku' => 'DRINK-LEMON-TEA', 'category' => 'เครื่องดื่มเย็น', 'unit' => 'แก้ว', 'price' => 30, 'purchase_price' => 12, 'quantity' => 60, 'brand' => 'เครื่องดื่มสด', 'description' => 'ชามะนาวเย็นรสเปรี้ยวหวาน สดชื่นสำหรับทานคู่ของย่าง'],
            ['name' => 'น้ำลำไย', 'sku' => 'DRINK-LONGAN', 'category' => 'เครื่องดื่มเย็น', 'unit' => 'แก้ว', 'price' => 30, 'purchase_price' => 12, 'quantity' => 50, 'brand' => 'เครื่องดื่มสด', 'description' => 'น้ำลำไยเย็น หวานหอม เหมาะกับลูกค้าที่ต้องการเครื่องดื่มสมุนไพร'],
            ['name' => 'น้ำเปล่า', 'sku' => 'DRINK-WATER', 'category' => 'เครื่องดื่มเย็น', 'unit' => 'ขวด', 'price' => 10, 'purchase_price' => 5, 'quantity' => 100, 'brand' => 'หน้าร้าน', 'description' => 'น้ำดื่มบรรจุขวดสำหรับขายหน้าร้านและออเดอร์กลับบ้าน'],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['sku' => $product['sku']],
                [
                    'image' => '',
                    'name' => $product['name'],
                    'slug' => strtolower($product['sku']),
                    'description' => $product['description'],
                    'category_id' => Category::where('name', $product['category'])->value('id'),
                    'brand_id' => Brand::where('name', $product['brand'])->value('id'),
                    'unit_id' => Unit::where('title', $product['unit'])->value('id'),
                    'price' => $product['price'],
                    'discount' => 0,
                    'discount_type' => 'fixed',
                    'purchase_price' => $product['purchase_price'],
                    'quantity' => $product['quantity'],
                    'expire_date' => null,
                    'status' => 1,
                ]
            );
        }
    }
}
