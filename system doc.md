# System Documentation: หม่าล่าแดนซ์ by ขวัญใจ

## ภาพรวมระบบ

หม่าล่าแดนซ์ by ขวัญใจ เป็นระบบ POS สำหรับร้านหม่าล่าและเครื่องดื่ม รองรับงานขายหน้าร้าน การจัดการสินค้า สต็อก ลูกค้า ซัพพลายเออร์ การซื้อเข้า รายงาน ใบเสร็จ สิทธิ์ผู้ใช้งาน และ audit log

ระบบเป็น Thai Full Version ใช้ข้อมูลเดโมร้านหม่าล่า

## เทคโนโลยีหลัก

- Backend: Laravel 10, PHP 8.1+
- Frontend: Blade + AdminLTE, React 18, Vite 4
- Database: PostgreSQL (Supabase) — production
- Auth/RBAC: Laravel auth + Spatie Laravel Permission
- DataTables: Yajra DataTables (server-side)
- Import/export: Maatwebsite Excel
- PDF: barryvdh/laravel-dompdf
- Hosting: Vercel (vercel-php runtime) + Supabase Postgres

## โครงสร้างสำคัญ

- `app/Http/Controllers`: controller หลังร้าน POS สินค้า ซื้อเข้า รายงาน และสิทธิ์
- `app/Models`: Product, Category, Brand, Unit, Customer, Supplier, Order, OrderProduct, OrderTransaction, Purchase, Currency, User, StockMovement, AuditLog, PosCart
- `app/Services/InventoryService.php`: จัดการสต็อก + stock_movements ledger
- `app/Trait/FileHandler.php`: จัดการ upload รูป (local filesystem)
- `database/seeders`: seed ข้อมูลเริ่มต้น (StartUpSeeder, RolePermissionSeeder, ThaiShopSeeder)
- `database/migrations`: schema รวม composite indexes สำหรับ performance
- `public/build`: Vite build output (ต้อง commit สำหรับ Vercel deploy)
- `resources/views/backend`: หน้าหลังร้านและเมนู
- `resources/js/components`: POS และ purchase React components
- `docs/th`: คู่มือภาษาไทย
- `api/index.php`: entrypoint สำหรับ Vercel PHP runtime
- `vercel.json`: config Vercel (regions, routes, excludeFiles)

## บัญชีผู้ใช้

หลังจาก seed:

- **Admin**: `admin` / `admin` (เปลี่ยนรหัสผ่านทันทีหลังเข้าครั้งแรก)
- **เจ้าของร้าน**: `kwan` / `kwan`

ระบบรองรับการล็อกอินด้วย **ชื่อผู้ใช้** หรือ **อีเมล**

Role: `Admin`, `cashier`, `sales_associate`

## โมดูลหลัก

### Authentication

หน้า public: `/login`, `/sign-up`, `/forget-password`, `/new-password`, `/password-reset`

รองรับ Google OAuth ผ่าน `/auth/google` (ต้องตั้งค่า env)

### Dashboard

หน้า `/admin` แสดงยอดรวมออเดอร์ ยอดขาย ส่วนลด ยอดชำระ ยอดค้าง จำนวนลูกค้า จำนวนสินค้า และจำนวนรายการขาย ใช้ SQL aggregate และ Cache::remember เพื่อ performance

### POS และ Order

POS ใช้ React (`resources/js/components/Pos.jsx`) เรียก API:

- `GET /admin/get/products` — รายการสินค้า (มี cache 10 นาที, รองรับ search/barcode/pagination)
- `GET /admin/get/channels` — รายการช่องทางการขายที่เปิดใช้
- `GET /admin/cart` — ตะกร้าปัจจุบัน
- `POST /admin/cart` — เพิ่มสินค้าเข้าตะกร้า (รองรับ spice_level, toppings)
- `PUT /admin/cart/increment` / `decrement` / `delete` / `empty`
- `PUT /admin/order/create` — บันทึก order
  - รับ `customer_id`, `order_discount`, `paid`, `order_type`, `notes`, `sales_channel_id`, `platform_order_ref`
  - DB transaction: สร้าง Order + OrderProducts → ลดสต็อก → คำนวณส่วนลด + platform_fee → บันทึก OrderTransaction
- `POST /admin/orders/void/{id}` — ยกเลิกบิล (คืนสต็อก, soft-delete Order, บันทึก AuditLog)

### Sales Channels (Food Delivery Platforms)

ระบบรองรับการบันทึกออเดอร์จากแพลตฟอร์ม food delivery เพื่อรวมยอดขายและการตัดสต็อกในระบบเดียว

ตาราง `sales_channels`:

- `name`, `slug` (unique), `icon` (FontAwesome class), `color` (hex)
- `commission_percent` — ใช้คำนวณ platform_fee อัตโนมัติ
- `status`, `sort_order`, `description`

ค่า default seed: `walk_in` (0%), `grab` (32%), `line_man` (30%), `shopee_food` (30%)

คอลัมน์ใหม่ใน `orders`:

- `sales_channel_id` (FK to sales_channels, nullable, indexed)
- `platform_fee` (decimal) — auto-calculated = `total * commission_percent / 100`
- `platform_order_ref` (string) — เลขที่ออเดอร์จากแอปแพลตฟอร์ม

CRUD: `/admin/sales-channels` (เฉพาะ Admin) — ลบ `walk_in` ไม่ได้

Report: `/admin/platform-sales-report` — aggregate ยอดขาย + commission แยกตามช่องทาง

### Product Management

จัดการสินค้า หมวด แบรนด์ หน่วยนับ และนำเข้าสินค้า:

- `/admin/products`
- `/admin/categories`
- `/admin/brands`
- `/admin/units`
- `/admin/import/products`

ทุกการ mutation บน product จะ bust POS cache อัตโนมัติ

### Customer และ Supplier

- `/admin/customers`
- `/admin/suppliers`
- `/admin/get/customers` (search)
- `/admin/create/customers` (สร้างจาก POS)

### Purchase

- `/admin/purchase`
- `/admin/purchase/products/{id}`

เมื่อบันทึกซื้อเข้า ระบบเพิ่มสต็อกสินค้าและบันทึก `stock_movements` type=`purchase`

### Inventory Service

`app/Services/InventoryService.php::adjustStock($product, $quantity, $type, $targetType, $targetId, $note, $userId)`:

- ปรับ `products.quantity`
- บันทึก `stock_movements` (immutable ledger)
- ใช้ทุกครั้งที่สต็อกเปลี่ยน (sale, void, purchase, manual adjustment)

### Reports

- `/admin/sale/summery`
- `/admin/sale/report`
- `/admin/inventory/report`

### Settings

ตั้งค่าร้าน โลโก้ favicon ข้อมูลติดต่อ สถานะระบบ ใบเสร็จ สกุลเงิน ผู้ใช้ บทบาท และสิทธิ์ (`/admin/settings/*`)

### Audit Log

ตาราง `audit_logs` บันทึก action สำคัญ (void_order, etc.) พร้อม user, ip, user_agent

### Manual / คู่มือ

`/admin/manuals` แสดงคู่มือ Markdown ภายใต้ allowlist เท่านั้น:

- `docs/th/quick-start.md`
- `docs/th/owner-manual.md`
- `docs/th/admin-manual.md`
- `docs/th/user-manual.md`
- `system doc.md`
- `CLAUDE.md`

## Performance Guidelines

ดูแนวทางแก้ไขเมื่อ query หรือการแสดงผลช้าได้ที่ [`docs/th/admin-manual.md` ส่วนที่ 11](docs/th/admin-manual.md) มี 11 หัวข้อย่อย ครอบคลุม:

1. ขั้นตอนวินิจฉัย
2. ปัญหา N+1 Query
3. DataTables ช้า
4. Dashboard ช้า
5. POS โหลดสินค้าช้า
6. Cache ไม่หาย
7. Database Connection (Vercel)
8. Region Mismatch
9. Composite Index ขาด
10. Frontend Bundle ใหญ่
11. Checklist ก่อนแจ้งระบบช้า

## Composite Indexes ที่มีในระบบ

- `orders(customer_id, created_at)`
- `products(status, quantity)`
- `stock_movements(product_id, created_at)`
- `order_transactions(order_id, created_at)`

หากเพิ่ม query pattern ใหม่ ให้พิจารณาเพิ่ม index และทดสอบด้วย `EXPLAIN ANALYZE`

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

## Vercel Production

### Architecture

- **Hosting**: Vercel (vercel-php@0.7.4 runtime), region `sin1`
- **Database**: Supabase Postgres (Transaction Pooler)
- **Static assets**: served by Vercel CDN จาก `public/`
- **Build**: `npm run build` (Vite) — output ใน `public/build/` ต้อง commit
- **Session**: cookie driver (เก็บใน client cookie)
- **Cache**: array driver (in-memory per request, ไม่ persist)
- **URL**: `https://mala-dance-by-kwanjai.vercel.app`

### Vercel Environment Variables

```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://mala-dance-by-kwanjai.vercel.app
APP_KEY=<base64 key>
DB_CONNECTION=pgsql
DATABASE_URL=<Supabase Transaction Pooler connection string>
SESSION_DRIVER=cookie
CACHE_DRIVER=array
LOG_CHANNEL=stderr
QUEUE_CONNECTION=sync
```

อย่า commit `DATABASE_URL`, database password, service role key หรือ secret ใด ๆ ลง repo ให้ตั้งผ่าน Vercel Environment Variables เท่านั้น

### ข้อจำกัด Vercel ที่ต้องรู้

1. **Filesystem read-only** ยกเว้น `/tmp` — upload รูปไม่ persist ระหว่าง deploy
2. **Cache driver = array** — cache ไม่ persist ระหว่าง request
3. **Cold start** — request แรกหลัง idle อาจช้า 1-3 วินาที
4. **Function timeout 60s** (ตั้งใน `vercel.json`)
5. **Vite manifest** — ต้อง build แล้ว commit `public/build/` ก่อน push
6. **`excludeFiles`** ใน `vercel.json` ห้ามใส่ `public/build/**` หรือ `docs/**`
7. **PDO PostgreSQL** — `PDO::ATTR_EMULATE_PREPARES` hardcode เป็น `false`

### Supabase Project

- ใช้ **Transaction Pooler** เท่านั้น สำหรับ serverless
- region ใกล้ Vercel function (Southeast Asia - Singapore)

## Known Issues / Future Work

- **File upload**: ปัจจุบันใช้ local filesystem ผ่าน `FileHandler` ซึ่งไม่ persist บน Vercel ควรย้ายไปใช้ Supabase Storage หรือ S3
- **Redis cache**: หากต้องการ persistent cache ระหว่าง serverless invocation ให้ตั้ง Upstash Redis แล้วเปลี่ยน `CACHE_DRIVER=redis`
- **Image optimization**: พิจารณา WebP/AVIF และ Vercel Image Optimization
- **Background jobs**: ปัจจุบัน `QUEUE_CONNECTION=sync` — งานหนัก (เช่น export Excel ขนาดใหญ่) จะ block request
