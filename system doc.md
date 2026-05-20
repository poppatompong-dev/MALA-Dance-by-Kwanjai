# System Documentation: หม่าล่าแดนซ์ by ขวัญใจ

## ภาพรวมระบบ

หม่าล่าแดนซ์ by ขวัญใจ เป็นระบบ POS สำหรับร้านหม่าล่าและเครื่องดื่ม พัฒนาด้วย Laravel, Blade, React และ Vite รองรับงานขายหน้าร้าน การจัดการสินค้า สต็อก ลูกค้า ซัพพลายเออร์ การซื้อเข้า รายงาน ใบเสร็จ และสิทธิ์ผู้ใช้งาน

ระบบถูกปรับเป็น Thai Full Version โดยใช้ข้อมูลเดโมร้านหม่าล่า เครื่องดื่มเย็น และชุดหม่าล่า พร้อมภาพ placeholder ภายใน repo

## เทคโนโลยีหลัก

- Backend: Laravel 10, PHP 8.1+
- Frontend: Blade, React 18, Vite
- Database: SQLite สำหรับ local testing และ MySQL/MariaDB สำหรับ production
- Auth/RBAC: Laravel auth และ Spatie Laravel Permission
- DataTables: Yajra DataTables
- Import/export: Maatwebsite Excel
- PDF: barryvdh/laravel-dompdf

## โครงสร้างสำคัญ

- `app/Http/Controllers`: controller หลังร้าน POS สินค้า ซื้อเข้า รายงาน และสิทธิ์
- `app/Models`: Product, Category, Brand, Unit, Customer, Supplier, Order, Purchase, Currency, User
- `database/seeders`: seed ข้อมูลร้านเดโม
- `public/assets/images/demo`: logo, favicon, auth/error, product/category/brand placeholders
- `resources/views/backend`: หน้าหลังร้านและเมนู
- `resources/js/components`: POS และ purchase React components
- `docs/th`: คู่มือภาษาไทย

## Demo Data

ข้อมูลเดโมประกอบด้วย:

- หมวดสินค้า 7 หมวด
- แบรนด์/แหล่งสินค้า 4 รายการ
- สินค้า 12 รายการ
- หน่วยนับไทย: ไม้, แก้ว, ขวด, ชุด, ถุง, กรัม
- ลูกค้าเดโม 3 รายการ
- ซัพพลายเออร์เดโม 3 รายการ
- สกุลเงิน THB สัญลักษณ์ `฿`

บัญชีเดโม:

- Email: `demo@qtecsolution.net`
- Password: `87654321`
- Name: `ผู้ดูแลร้าน`

## โมดูลหลัก

### Authentication

หน้า public อยู่ที่ `/login`, `/sign-up`, `/forget-password`, `/new-password`, `/password-reset`

### Dashboard

หน้า `/admin` แสดงยอดรวมออเดอร์ ยอดขาย ส่วนลด ยอดชำระ ยอดค้าง จำนวนลูกค้า จำนวนสินค้า และจำนวนรายการขาย

### POS และ Order

POS ใช้ React เรียก API:

- `GET /admin/get/products`
- `GET /admin/cart`
- `POST /admin/cart`
- `PUT /admin/cart/increment`
- `PUT /admin/cart/decrement`
- `PUT /admin/cart/delete`
- `PUT /admin/cart/empty`
- `PUT /admin/order/create`

### Product Management

จัดการสินค้า หมวด แบรนด์ หน่วยนับ และนำเข้าสินค้า:

- `/admin/products`
- `/admin/categories`
- `/admin/brands`
- `/admin/units`
- `/admin/import/products`

### Customer และ Supplier

- `/admin/customers`
- `/admin/suppliers`
- `/admin/get/customers`
- `/admin/create/customers`

### Purchase

- `/admin/purchase`
- `/admin/purchase/products/{id}`

เมื่อบันทึกซื้อเข้า ระบบเพิ่มสต็อกสินค้า

### Reports

- `/admin/sale/summery`
- `/admin/sale/report`
- `/admin/inventory/report`

### Settings

ตั้งค่าร้าน โลโก้ favicon ข้อมูลติดต่อ สถานะระบบ ใบเสร็จ สกุลเงิน ผู้ใช้ บทบาท และสิทธิ์

## คำสั่ง Local

```powershell
.\.tools\php\php.exe artisan migrate:fresh --seed --force
npm.cmd run dev
.\.tools\php\php.exe artisan serve --host=127.0.0.1 --port=8000
```

## คำสั่งตรวจ

```powershell
.\.tools\php\php.exe artisan migrate:fresh --seed --force
npm.cmd run build
.\.tools\php\php.exe artisan view:cache
```

## หมายเหตุ Deploy

- ตั้งค่า `.env` ให้ตรงฐานข้อมูลจริง
- รัน `php artisan key:generate` เมื่อสร้าง environment ใหม่
- ตั้ง permission ให้ `storage` และ `bootstrap/cache`
- รัน `php artisan storage:link` หากใช้ไฟล์ upload
- รัน `npm run build` ก่อน production
- ห้าม commit `.env`, `vendor`, `node_modules`, log และ local SQLite
## คู่มือบนเว็บหลังร้าน

ระบบมีหน้าอ่านคู่มือสำหรับผู้ดูแลหลังร้านที่:

```text
http://127.0.0.1:8000/admin/manuals
```

route นี้อยู่ภายใต้ middleware `admin` และอ่านได้เฉพาะไฟล์ที่อยู่ใน allowlist เท่านั้น เพื่อป้องกันการอ่านไฟล์อื่นนอกระบบคู่มือ แหล่งข้อมูล Markdown ที่ใช้แสดงผลคือ:

- `docs/th/quick-start.md`
- `docs/th/owner-manual.md`
- `docs/th/admin-manual.md`
- `docs/th/user-manual.md`
- `system doc.md`

## Product Import

หน้าหลังร้าน `สินค้า > นำเข้าสินค้า` ใช้ route `backend.admin.products.import` สำหรับดาวน์โหลดเทมเพลตและอัปโหลดไฟล์สินค้า

- ดาวน์โหลดเทมเพลต: `/admin/import/products?download-template=1`
- ไฟล์ที่รองรับ: `.xlsx`, `.xls`, `.csv`, `.txt`
- ขนาดไฟล์สูงสุด: 5 MB
- importer: `App\Imports\ProductsImport`
- template export: `App\Exports\DemoProductsExport`

หัวคอลัมน์ที่รองรับคือ `name`, `sku`, `description`, `category`, `brand`, `unit`, `price`, `discount`, `discount_type`, `purchase_price`, `quantity`, `expire_date`, `status`, `image`

กฎสำคัญ:

- SKU เป็น key หลักของสินค้า
- slug ถูกสร้างจาก SKU เป็น lowercase/hyphen เพื่อเลี่ยงปัญหา slug ภาษาไทยว่าง
- หาก SKU มีอยู่แล้ว ระบบจะ update สินค้าเดิม
- หาก category, brand หรือ unit ยังไม่มี ระบบจะสร้างให้อัตโนมัติ
- ถ้าเป็นสินค้าใหม่และมี supplier/user ในระบบ จะบันทึก purchase เริ่มต้นสำหรับจำนวนที่นำเข้า

## Supabase Production Database

Production บน Vercel ใช้ Supabase Postgres เป็นฐานข้อมูลถาวร แนะนำให้ใช้ Transaction pooler สำหรับ serverless runtime

Project:

- Supabase URL: `https://rnvgjbhfnnzxmbmlyxke.supabase.co`
- Project ref: `rnvgjbhfnnzxmbmlyxke`
- Database host: `db.rnvgjbhfnnzxmbmlyxke.supabase.co`

Vercel environment variables ที่ต้องมี:

- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL=https://mala-dance-by-kwanjai.vercel.app`
- `APP_KEY=<Laravel app key>`
- `DB_CONNECTION=pgsql`
- `DB_EMULATE_PREPARES=true`
- `DATABASE_URL=<Supabase Transaction pooler connection string>`
- `SESSION_DRIVER=cookie`
- `CACHE_DRIVER=array`
- `LOG_CHANNEL=stderr`
- `QUEUE_CONNECTION=sync`

อย่า commit `DATABASE_URL`, database password, service role key หรือ secret ใด ๆ ลง repo ให้ตั้งผ่าน Vercel Environment Variables เท่านั้น
