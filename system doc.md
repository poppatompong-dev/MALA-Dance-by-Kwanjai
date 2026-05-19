# System Documentation: MALA Dance by Kwanjai

## ภาพรวมระบบ

MALA Dance by Kwanjai เป็นระบบ POS สำหรับร้านหม่าล่าและเครื่องดื่ม พัฒนาบน Laravel 10 พร้อม React เฉพาะส่วนหน้าจอที่ต้องโต้ตอบแบบรวดเร็ว เช่น POS cart และ purchase form ระบบรองรับการขายหน้าร้าน การจัดการสินค้า สต็อก ลูกค้า ซัพพลายเออร์ คำสั่งซื้อ การรับสินค้า รายงาน และสิทธิ์ผู้ใช้งาน

ระบบเดโมถูกปรับให้เหมาะกับร้านหม่าล่าไทย โดย seed ข้อมูลสินค้าเป็นหมวดหม่าล่าเสียบไม้ ชุดหม่าล่า เครื่องดื่มเย็น เครื่องดื่มปั่น ท็อปปิ้ง ซอส/น้ำจิ้ม และของทานเล่น พร้อมหน่วยนับภาษาไทยและสกุลเงิน Thai Baht

## เทคโนโลยีหลัก

- Backend: PHP 8.1+, Laravel 10
- Frontend: Blade, React 18, Vite
- Database: MySQL/MariaDB สำหรับ production และ SQLite สำหรับ local testing
- Auth/RBAC: Laravel auth flow และ Spatie Laravel Permission
- Data table/report: Yajra DataTables
- Export/import: Maatwebsite Excel
- PDF: barryvdh/laravel-dompdf
- Docker: Dockerfile, Dockerfile.node, docker-compose.yml และ Makefile

## โครงสร้างสำคัญ

- `app/Http/Controllers`: controller หลักของระบบ backend, POS, product, purchase, report, role/permission
- `app/Models`: model สำหรับ Product, Category, Brand, Unit, Customer, Supplier, Order, Purchase, Currency และ user-related models
- `database/migrations`: schema ฐานข้อมูล
- `database/seeders`: seed ข้อมูลเริ่มต้น รวมถึง demo data ร้านหม่าล่า/เครื่องดื่ม
- `resources/views`: Blade templates สำหรับหน้า admin, auth, invoice และ settings
- `resources/js/components`: React components สำหรับ POS cart และ purchase workflow
- `routes/web.php`: web routes หลักทั้งหมด
- `docs/th`: คู่มือภาษาไทยสำหรับผู้ใช้และผู้ดูแล

## โมดูลระบบ

### Authentication

เส้นทาง public อยู่ที่ `/login`, `/sign-up`, `/forget-password`, `/new-password`, `/password-reset` และ Google OAuth callback ระบบหลังบ้านถูกครอบด้วย middleware `admin`

บัญชีเดโมจาก seeder:

- Email: `demo@qtecsolution.net`
- Password: `87654321`
- Name: `ผู้ดูแลร้าน`

### Dashboard

หน้า `/admin` แสดงยอดรวมคำสั่งซื้อ ยอดขาย ส่วนลด ยอดชำระ ยอดค้างชำระ จำนวนลูกค้า จำนวนสินค้า และจำนวนคำสั่งซื้อ

### Product Management

จัดการสินค้า หมวดหมู่ แบรนด์ หน่วยนับ และการ import สินค้า เส้นทางหลัก:

- `/admin/products`
- `/admin/categories`
- `/admin/brands`
- `/admin/units`
- `/admin/import/products`

ข้อมูล demo seed ประกอบด้วยสินค้า deterministic 12 รายการ เช่น หมูสามชั้นหม่าล่า เนื้อวัวหม่าล่า ชุดหม่าล่า และเครื่องดื่มเย็น โดย slug ถูกกำหนดจาก SKU แบบ lowercase เพื่อเลี่ยงปัญหา Thai slug ว่าง

### POS และ Order

หน้าขายใช้ React component เรียก API ภายใต้ `/admin/get/products` และ cart endpoints:

- `GET /admin/cart`
- `POST /admin/cart`
- `PUT /admin/cart/increment`
- `PUT /admin/cart/decrement`
- `PUT /admin/cart/delete`
- `PUT /admin/cart/empty`
- `PUT /admin/order/create`

เมื่อสร้าง order ระบบบันทึกรายการสินค้า ลูกค้า ยอดรวม ส่วนลด การชำระเงิน และธุรกรรมที่เกี่ยวข้อง

### Customer และ Supplier

จัดการข้อมูลลูกค้าและซัพพลายเออร์:

- `/admin/customers`
- `/admin/suppliers`
- `/admin/get/customers`
- `/admin/create/customers`

ข้อมูลเริ่มต้นมีลูกค้าหน้าร้าน ลูกค้าประจำ ออเดอร์เดลิเวอรี และซัพพลายเออร์สำหรับวัตถุดิบสด เครื่องดื่ม/บรรจุภัณฑ์ ซอส/เครื่องปรุง

### Purchase

หน้ารับสินค้าใช้ React component สำหรับเลือกซัพพลายเออร์และสินค้า แล้วบันทึก purchase และ purchase items:

- `/admin/purchase`
- `/admin/purchase/products/{id}`

ระบบเพิ่มจำนวนสต็อกสินค้าเมื่อบันทึกการซื้อเข้า

### Reports

รายงานหลัก:

- `/admin/sale/summery`
- `/admin/sale/report`
- `/admin/inventory/report`

ใช้สำหรับดูยอดขายตามช่วงเวลา รายการขาย และสถานะคลังสินค้า

### Currency

จัดการสกุลเงินผ่าน `/admin/currencies` และกำหนด default ผ่าน `/admin/currencies/default/{id}` ข้อมูล seed กำหนด Thai Baht เป็น:

- Code: `THB`
- Symbol: `฿`
- Active: `true`

### Roles และ Permissions

ระบบสิทธิ์อยู่ภายใต้:

- `/admin/settings/website/roles`
- `/admin/settings/website/permissions`

Seeder สร้าง role `Admin` และ sync ให้กับผู้ใช้เดโม พร้อม permission สำหรับเมนูหลังบ้าน

## Database หลัก

ตารางสำคัญ:

- `users`
- `roles`, `permissions`, `model_has_roles`, `role_has_permissions`
- `categories`, `brands`, `units`, `products`
- `pos_carts`
- `customers`, `suppliers`
- `orders`, `order_products`, `order_transactions`
- `purchases`, `purchase_items`
- `currencies`

ความสัมพันธ์หลัก:

- Product belongs to Category, Brand, Unit
- Customer has many Orders
- Order has many OrderProducts และ OrderTransactions
- Supplier เกี่ยวข้องกับ Purchase
- Purchase has many PurchaseItems

## Demo Data ร้านหม่าล่า

Seeders ที่เกี่ยวข้อง:

- `StartUpSeeder`: admin, first customer, first supplier, units, currencies, permissions
- `UnitSeeder`: ไม้, แก้ว, ขวด, ชุด, ถุง, กรัม
- `ProductSeeder`: หมวดสินค้า แบรนด์ และสินค้า mala/drinks 12 รายการ
- `CustomerSeeder`: ลูกค้าหน้าร้าน ลูกค้าประจำ ออเดอร์เดลิเวอรี
- `SupplierSeeder`: ร้านวัตถุดิบสด ร้านเครื่องดื่มและบรรจุภัณฑ์ ร้านซอสและเครื่องปรุง
- `CurrencySeeder`: สกุลเงิน พร้อม THB active

คำสั่ง reset ฐานข้อมูล local:

```bash
php artisan migrate:fresh --seed --force
```

## การติดตั้งแบบ Local

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
npm run dev
```

เปิดระบบที่:

```text
http://127.0.0.1:8000
```

## การติดตั้งด้วย Docker

```bash
make setup
```

ค่า config หลักอยู่ใน `.env.docker`, `docker-compose.yml`, `Dockerfile` และ `Dockerfile.node`

## คำสั่งพัฒนาและตรวจสอบ

```bash
php artisan migrate:fresh --seed --force
npm run dev
npm run build
vendor/bin/phpunit
```

บน Windows workspace นี้มี portable PHP อยู่ที่:

```powershell
.\.tools\php\php.exe artisan migrate:fresh --seed --force
```

## หมายเหตุการ deploy

- ตั้งค่า `.env` ให้ตรงกับฐานข้อมูลจริง
- รัน `php artisan key:generate` เฉพาะ environment ใหม่
- ตั้ง permission ให้ `storage` และ `bootstrap/cache`
- รัน `php artisan storage:link` หากต้องใช้ไฟล์ upload
- รัน `npm run build` เพื่อสร้าง frontend assets
- ห้าม commit `.env`, `vendor`, `node_modules`, log และ local SQLite
