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
            'อาหารจานเดียว' => '/assets/images/demo/categories/mala-sets.svg',
            'แกง/ต้ม' => '/assets/images/demo/categories/sauces.svg',
            'ย่าง/เสียบไม้' => '/assets/images/demo/categories/mala-skewers.svg',
            'ของทานเล่น' => '/assets/images/demo/categories/snacks.svg',
            'ของหวาน' => '/assets/images/demo/categories/toppings.svg',
            'กาแฟ' => '/assets/images/demo/categories/cold-drinks.svg',
            'ชา/นม' => '/assets/images/demo/categories/cold-drinks.svg',
            'น้ำผลไม้/โซดา' => '/assets/images/demo/categories/cold-drinks.svg',
            'สมูทตี้/ปั่น' => '/assets/images/demo/categories/blended-drinks.svg',
            'สมุนไพร/สุขภาพ' => '/assets/images/demo/categories/cold-drinks.svg',
            'เครื่องดื่มบรรจุขวด' => '/assets/images/demo/categories/cold-drinks.svg',
        ];

        $brands = [
            'ครัวไทย' => '/assets/images/demo/brands/kitchen.svg',
            'ครัวปิ้งย่าง' => '/assets/images/demo/brands/kitchen.svg',
            'บาร์เครื่องดื่ม' => '/assets/images/demo/brands/fresh-drinks.svg',
            'สินค้าพร้อมขาย' => '/assets/images/demo/brands/storefront.svg',
        ];

        foreach ($categories as $category => $image) {
            Category::updateOrCreate(
                ['name' => $category],
                [
                    'image' => $image,
                    'description' => 'หมวดข้อมูลตั้งต้นสำหรับเมนูอาหารและเครื่องดื่ม',
                    'status' => true,
                ]
            );
        }

        foreach ($brands as $brand => $image) {
            Brand::updateOrCreate(
                ['name' => $brand],
                [
                    'image' => $image,
                    'description' => 'แหล่งจัดเตรียมสินค้าเริ่มต้นของร้าน',
                    'status' => true,
                ]
            );
        }

        $products = [
            ['name' => 'ผัดไทยกุ้งสด', 'sku' => 'FOOD-PAD-THAI-SHRIMP', 'category' => 'อาหารจานเดียว', 'unit' => 'จาน', 'price' => 79, 'purchase_price' => 38, 'quantity' => 50, 'brand' => 'ครัวไทย', 'image' => $this->commonsFile('Pad Thai.JPG'), 'description' => 'เส้นผัดไทยพร้อมกุ้ง ถั่วงอก ใบกุยช่าย และถั่วคั่ว'],
            ['name' => 'ข้าวผัดไข่', 'sku' => 'FOOD-FRIED-RICE-EGG', 'category' => 'อาหารจานเดียว', 'unit' => 'จาน', 'price' => 59, 'purchase_price' => 25, 'quantity' => 60, 'brand' => 'ครัวไทย', 'image' => $this->commonsFile('FriedRice.JPG'), 'description' => 'ข้าวผัดหอมกระทะสำหรับเมนูจานด่วนประจำร้าน'],
            ['name' => 'ข้าวซอยไก่', 'sku' => 'FOOD-KHAO-SOI-CHICKEN', 'category' => 'อาหารจานเดียว', 'unit' => 'ชาม', 'price' => 89, 'purchase_price' => 42, 'quantity' => 40, 'brand' => 'ครัวไทย', 'image' => $this->commonsFile('Khao soi Chiang Mai.jpg'), 'description' => 'บะหมี่แกงกะทิสไตล์เหนือ เสิร์ฟพร้อมไก่และเครื่องเคียง'],
            ['name' => 'ส้มตำไทย', 'sku' => 'FOOD-SOM-TAM-THAI', 'category' => 'อาหารจานเดียว', 'unit' => 'จาน', 'price' => 55, 'purchase_price' => 22, 'quantity' => 50, 'brand' => 'ครัวไทย', 'image' => $this->commonsFile('Som tam thai.JPG'), 'description' => 'ส้มตำรสเปรี้ยวหวาน เผ็ดกำลังดี พร้อมถั่วลิสงและกุ้งแห้ง'],
            ['name' => 'ต้มยำน้ำใส', 'sku' => 'SOUP-TOM-YUM-CLEAR', 'category' => 'แกง/ต้ม', 'unit' => 'ชาม', 'price' => 95, 'purchase_price' => 45, 'quantity' => 35, 'brand' => 'ครัวไทย', 'image' => $this->commonsFile('Tom Yum with Clear Soup.jpg'), 'description' => 'ต้มยำรสจัดจ้าน หอมสมุนไพรไทย'],
            ['name' => 'แกงเขียวหวานไก่', 'sku' => 'CURRY-GREEN-CHICKEN', 'category' => 'แกง/ต้ม', 'unit' => 'ชาม', 'price' => 85, 'purchase_price' => 38, 'quantity' => 35, 'brand' => 'ครัวไทย', 'image' => $this->commonsFile('Green Curry.jpg'), 'description' => 'แกงเขียวหวานกะทิสดพร้อมไก่และมะเขือ'],
            ['name' => 'มัสมั่นไก่', 'sku' => 'CURRY-MASSAMAN-CHICKEN', 'category' => 'แกง/ต้ม', 'unit' => 'ชาม', 'price' => 95, 'purchase_price' => 45, 'quantity' => 30, 'brand' => 'ครัวไทย', 'image' => $this->commonsFile('Massaman curry with chicken and potatoes.jpg'), 'description' => 'แกงมัสมั่นรสกลมกล่อมพร้อมมันฝรั่ง'],
            ['name' => 'สะเต๊ะไก่', 'sku' => 'GRILL-CHICKEN-SATAY', 'category' => 'ย่าง/เสียบไม้', 'unit' => 'ชุด', 'price' => 75, 'purchase_price' => 32, 'quantity' => 45, 'brand' => 'ครัวปิ้งย่าง', 'image' => $this->commonsFile('Satay 2.jpg'), 'description' => 'ไก่หมักเครื่องเทศย่าง เสิร์ฟพร้อมน้ำจิ้มถั่ว'],
            ['name' => 'หมูสามชั้นหม่าล่า', 'sku' => 'GRILL-MALA-PORK-BELLY', 'category' => 'ย่าง/เสียบไม้', 'unit' => 'ไม้', 'price' => 18, 'purchase_price' => 9, 'quantity' => 120, 'brand' => 'ครัวปิ้งย่าง', 'image' => $this->commonsFile('Barbecue skewers.jpg'), 'description' => 'หมูสามชั้นเสียบไม้ โรยผงหม่าล่าเผ็ดชา'],
            ['name' => 'ไส้กรอกชีสย่าง', 'sku' => 'GRILL-CHEESE-SAUSAGE', 'category' => 'ย่าง/เสียบไม้', 'unit' => 'ไม้', 'price' => 15, 'purchase_price' => 7, 'quantity' => 120, 'brand' => 'ครัวปิ้งย่าง', 'image' => $this->commonsFile('Sausages on the grill.jpg'), 'description' => 'ไส้กรอกชีสย่างร้อน เหมาะกับเมนูเสียบไม้'],
            ['name' => 'เฟรนช์ฟรายส์', 'sku' => 'SNACK-FRENCH-FRIES', 'category' => 'ของทานเล่น', 'unit' => 'กล่อง', 'price' => 49, 'purchase_price' => 20, 'quantity' => 70, 'brand' => 'สินค้าพร้อมขาย', 'image' => $this->commonsFile('French fries.jpg'), 'description' => 'มันฝรั่งทอดกรอบสำหรับขายคู่เครื่องดื่ม'],
            ['name' => 'ไก่ทอด', 'sku' => 'SNACK-FRIED-CHICKEN', 'category' => 'ของทานเล่น', 'unit' => 'ชุด', 'price' => 69, 'purchase_price' => 32, 'quantity' => 50, 'brand' => 'ครัวไทย', 'image' => $this->commonsFile('Fried Chicken.jpg'), 'description' => 'ไก่ทอดกรอบพร้อมซอสจิ้ม'],
            ['name' => 'ข้าวเหนียวมะม่วง', 'sku' => 'DESSERT-MANGO-STICKY-RICE', 'category' => 'ของหวาน', 'unit' => 'จาน', 'price' => 89, 'purchase_price' => 42, 'quantity' => 30, 'brand' => 'ครัวไทย', 'image' => $this->commonsFile('Mango over sticky rice with coconut sauce.jpg'), 'description' => 'ข้าวเหนียวมูนเสิร์ฟพร้อมมะม่วงและน้ำกะทิ'],
            ['name' => 'ไอศกรีมวานิลลา', 'sku' => 'DESSERT-VANILLA-ICE-CREAM', 'category' => 'ของหวาน', 'unit' => 'ถ้วย', 'price' => 45, 'purchase_price' => 18, 'quantity' => 40, 'brand' => 'สินค้าพร้อมขาย', 'image' => $this->commonsFile('Vanilla ice cream cone.jpg'), 'description' => 'ไอศกรีมวานิลลาสำหรับเมนูของหวาน'],

            ['name' => 'เอสเปรสโซร้อน', 'sku' => 'DRINK-HOT-ESPRESSO', 'category' => 'กาแฟ', 'unit' => 'แก้ว', 'price' => 45, 'purchase_price' => 14, 'quantity' => 80, 'brand' => 'บาร์เครื่องดื่ม', 'image' => $this->commonsFile('Espresso BW 1.jpg'), 'description' => 'กาแฟเอสเปรสโซเข้มข้นแบบร้อน'],
            ['name' => 'อเมริกาโนร้อน', 'sku' => 'DRINK-HOT-AMERICANO', 'category' => 'กาแฟ', 'unit' => 'แก้ว', 'price' => 45, 'purchase_price' => 14, 'quantity' => 80, 'brand' => 'บาร์เครื่องดื่ม', 'image' => $this->commonsFile('(Zazu, Quito) Cafè Americano.JPG'), 'description' => 'กาแฟดำร้อนรสสะอาด'],
            ['name' => 'คาปูชิโนร้อน', 'sku' => 'DRINK-HOT-CAPPUCCINO', 'category' => 'กาแฟ', 'unit' => 'แก้ว', 'price' => 55, 'purchase_price' => 18, 'quantity' => 80, 'brand' => 'บาร์เครื่องดื่ม', 'image' => $this->commonsFile('Classic Cappuccino.jpg'), 'description' => 'กาแฟนมร้อนพร้อมฟองนม'],
            ['name' => 'ลาเต้ร้อน', 'sku' => 'DRINK-HOT-LATTE', 'category' => 'กาแฟ', 'unit' => 'แก้ว', 'price' => 55, 'purchase_price' => 18, 'quantity' => 80, 'brand' => 'บาร์เครื่องดื่ม', 'image' => $this->commonsFile('Latte art (3064899205).jpg'), 'description' => 'กาแฟนมร้อนรสนุ่ม'],
            ['name' => 'อเมริกาโนเย็น', 'sku' => 'DRINK-ICED-AMERICANO', 'category' => 'กาแฟ', 'unit' => 'แก้ว', 'price' => 55, 'purchase_price' => 16, 'quantity' => 90, 'brand' => 'บาร์เครื่องดื่ม', 'image' => $this->commonsFile('Iced coffee .jpg'), 'description' => 'กาแฟดำเย็น ไม่ใส่นม'],
            ['name' => 'กาแฟเย็น', 'sku' => 'DRINK-ICED-COFFEE', 'category' => 'กาแฟ', 'unit' => 'แก้ว', 'price' => 60, 'purchase_price' => 18, 'quantity' => 90, 'brand' => 'บาร์เครื่องดื่ม', 'image' => $this->commonsFile('Freddo cappuccino.jpg'), 'description' => 'กาแฟเย็นใส่นม เหมาะกับเมนูหน้าร้าน'],
            ['name' => 'ชาไทยเย็น', 'sku' => 'DRINK-THAI-ICED-TEA', 'category' => 'ชา/นม', 'unit' => 'แก้ว', 'price' => 45, 'purchase_price' => 14, 'quantity' => 100, 'brand' => 'บาร์เครื่องดื่ม', 'image' => $this->commonsFile('Thai iced tea.jpg'), 'description' => 'ชาไทยใส่นม หวานมัน สีส้มเข้ม'],
            ['name' => 'ชาเขียวลาเต้เย็น', 'sku' => 'DRINK-ICED-GREEN-TEA-LATTE', 'category' => 'ชา/นม', 'unit' => 'แก้ว', 'price' => 55, 'purchase_price' => 18, 'quantity' => 90, 'brand' => 'บาร์เครื่องดื่ม', 'image' => $this->commonsFile('Iced green tea latte.jpg'), 'description' => 'ชาเขียวผสมนมเสิร์ฟเย็น'],
            ['name' => 'ชานมไข่มุก', 'sku' => 'DRINK-BUBBLE-MILK-TEA', 'category' => 'ชา/นม', 'unit' => 'แก้ว', 'price' => 59, 'purchase_price' => 22, 'quantity' => 85, 'brand' => 'บาร์เครื่องดื่ม', 'image' => $this->commonsFile('Classic bubble tea.jpg'), 'description' => 'ชานมพร้อมไข่มุกเคี้ยวหนึบ'],
            ['name' => 'โกโก้เย็น', 'sku' => 'DRINK-ICED-COCOA', 'category' => 'ชา/นม', 'unit' => 'แก้ว', 'price' => 50, 'purchase_price' => 17, 'quantity' => 90, 'brand' => 'บาร์เครื่องดื่ม', 'image' => $this->commonsFile('Iced chocolate.jpg'), 'description' => 'โกโก้เย็นเข้มข้น'],
            ['name' => 'นมสดเย็น', 'sku' => 'DRINK-ICED-MILK', 'category' => 'ชา/นม', 'unit' => 'แก้ว', 'price' => 40, 'purchase_price' => 13, 'quantity' => 90, 'brand' => 'บาร์เครื่องดื่ม', 'image' => $this->commonsFile('Glass of milk on tablecloth.jpg'), 'description' => 'นมสดเย็นสำหรับลูกค้าทุกวัย'],
            ['name' => 'น้ำส้มคั้น', 'sku' => 'DRINK-ORANGE-JUICE', 'category' => 'น้ำผลไม้/โซดา', 'unit' => 'แก้ว', 'price' => 45, 'purchase_price' => 18, 'quantity' => 80, 'brand' => 'บาร์เครื่องดื่ม', 'image' => $this->commonsFile('Fresh orange juice.jpg'), 'description' => 'น้ำส้มสดรสเปรี้ยวหวาน'],
            ['name' => 'น้ำมะนาว', 'sku' => 'DRINK-LIMEADE', 'category' => 'น้ำผลไม้/โซดา', 'unit' => 'แก้ว', 'price' => 40, 'purchase_price' => 14, 'quantity' => 80, 'brand' => 'บาร์เครื่องดื่ม', 'image' => $this->commonsFile('Limeade.jpg'), 'description' => 'น้ำมะนาวเย็น สดชื่น'],
            ['name' => 'น้ำมะพร้าว', 'sku' => 'DRINK-COCONUT-WATER', 'category' => 'น้ำผลไม้/โซดา', 'unit' => 'แก้ว', 'price' => 50, 'purchase_price' => 22, 'quantity' => 70, 'brand' => 'บาร์เครื่องดื่ม', 'image' => $this->commonsFile('Fresh coconut water.jpg'), 'description' => 'น้ำมะพร้าวสดจากผลมะพร้าว'],
            ['name' => 'แดงโซดา', 'sku' => 'DRINK-RED-SODA', 'category' => 'น้ำผลไม้/โซดา', 'unit' => 'แก้ว', 'price' => 35, 'purchase_price' => 10, 'quantity' => 90, 'brand' => 'บาร์เครื่องดื่ม', 'image' => $this->commonsFile('Italian soda.jpg'), 'description' => 'น้ำหวานแดงผสมโซดา'],
            ['name' => 'มะนาวโซดา', 'sku' => 'DRINK-LIME-SODA', 'category' => 'น้ำผลไม้/โซดา', 'unit' => 'แก้ว', 'price' => 40, 'purchase_price' => 12, 'quantity' => 90, 'brand' => 'บาร์เครื่องดื่ม', 'image' => $this->commonsFile('Lemon soda.jpg'), 'description' => 'โซดามะนาวรสซ่า'],
            ['name' => 'สตรอว์เบอร์รีสมูทตี้', 'sku' => 'DRINK-STRAWBERRY-SMOOTHIE', 'category' => 'สมูทตี้/ปั่น', 'unit' => 'แก้ว', 'price' => 65, 'purchase_price' => 26, 'quantity' => 65, 'brand' => 'บาร์เครื่องดื่ม', 'image' => $this->commonsFile('Strawberry smoothie.jpg'), 'description' => 'สมูทตี้สตรอว์เบอร์รีเนื้อเนียน'],
            ['name' => 'มะม่วงสมูทตี้', 'sku' => 'DRINK-MANGO-SMOOTHIE', 'category' => 'สมูทตี้/ปั่น', 'unit' => 'แก้ว', 'price' => 65, 'purchase_price' => 26, 'quantity' => 65, 'brand' => 'บาร์เครื่องดื่ม', 'image' => $this->commonsFile('Mango smoothie.jpg'), 'description' => 'สมูทตี้มะม่วงรสหวานหอม'],
            ['name' => 'กล้วยปั่น', 'sku' => 'DRINK-BANANA-SMOOTHIE', 'category' => 'สมูทตี้/ปั่น', 'unit' => 'แก้ว', 'price' => 60, 'purchase_price' => 22, 'quantity' => 65, 'brand' => 'บาร์เครื่องดื่ม', 'image' => $this->commonsFile('Banana smoothie.jpg'), 'description' => 'เครื่องดื่มกล้วยปั่นใส่นม'],
            ['name' => 'น้ำแตงโมปั่น', 'sku' => 'DRINK-WATERMELON-SHAKE', 'category' => 'สมูทตี้/ปั่น', 'unit' => 'แก้ว', 'price' => 55, 'purchase_price' => 20, 'quantity' => 70, 'brand' => 'บาร์เครื่องดื่ม', 'image' => $this->commonsFile('Watermelon juice.jpg'), 'description' => 'แตงโมปั่นเย็นสดชื่น'],
            ['name' => 'น้ำเก๊กฮวย', 'sku' => 'DRINK-CHRYSANTHEMUM-TEA', 'category' => 'สมุนไพร/สุขภาพ', 'unit' => 'แก้ว', 'price' => 30, 'purchase_price' => 9, 'quantity' => 90, 'brand' => 'บาร์เครื่องดื่ม', 'image' => $this->commonsFile('Chrysanthemum tea.jpg'), 'description' => 'ชาเก๊กฮวยหอมหวาน ดื่มเย็นหรือร้อนได้'],
            ['name' => 'น้ำอัญชันมะนาว', 'sku' => 'DRINK-BUTTERFLY-PEA-LIME', 'category' => 'สมุนไพร/สุขภาพ', 'unit' => 'แก้ว', 'price' => 35, 'purchase_price' => 10, 'quantity' => 90, 'brand' => 'บาร์เครื่องดื่ม', 'image' => $this->commonsFile('Butterfly pea flower tea.jpg'), 'description' => 'น้ำอัญชันผสมมะนาว สีสวยสดชื่น'],
            ['name' => 'น้ำลำไย', 'sku' => 'DRINK-LONGAN-JUICE', 'category' => 'สมุนไพร/สุขภาพ', 'unit' => 'แก้ว', 'price' => 35, 'purchase_price' => 12, 'quantity' => 80, 'brand' => 'บาร์เครื่องดื่ม', 'image' => $this->commonsFile('Longan drink.jpg'), 'description' => 'น้ำลำไยเย็น หอมหวาน'],
            ['name' => 'น้ำดื่มบรรจุขวด', 'sku' => 'DRINK-BOTTLED-WATER', 'category' => 'เครื่องดื่มบรรจุขวด', 'unit' => 'ขวด', 'price' => 12, 'purchase_price' => 5, 'quantity' => 200, 'brand' => 'สินค้าพร้อมขาย', 'image' => $this->commonsFile('Bottled water.jpg'), 'description' => 'น้ำดื่มบรรจุขวดพร้อมขาย'],
            ['name' => 'น้ำอัดลมกระป๋อง', 'sku' => 'DRINK-CANNED-SOFT-DRINK', 'category' => 'เครื่องดื่มบรรจุขวด', 'unit' => 'ขวด', 'price' => 20, 'purchase_price' => 11, 'quantity' => 150, 'brand' => 'สินค้าพร้อมขาย', 'image' => $this->commonsFile('Soft drink cans.jpg'), 'description' => 'น้ำอัดลมพร้อมขายแบบกระป๋องหรือขวด'],
            ['name' => 'น้ำแร่', 'sku' => 'DRINK-MINERAL-WATER', 'category' => 'เครื่องดื่มบรรจุขวด', 'unit' => 'ขวด', 'price' => 20, 'purchase_price' => 9, 'quantity' => 120, 'brand' => 'สินค้าพร้อมขาย', 'image' => $this->commonsFile('Mineral water bottle.jpg'), 'description' => 'น้ำแร่บรรจุขวด'],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['sku' => $product['sku']],
                [
                    'image' => $product['image'],
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

    private function commonsFile(string $file): string
    {
        return 'https://commons.wikimedia.org/wiki/Special:FilePath/' . rawurlencode($file);
    }
}
