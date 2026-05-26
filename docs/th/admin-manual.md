# คู่มือผู้ดูแลระบบ

คู่มือนี้สำหรับผู้ดูแลระบบที่ต้องตั้งค่าร้าน จัดการข้อมูลหลัก ดูแลผู้ใช้งาน สิทธิ์ สินค้า สต็อก รายงาน ระบบสะสมแต้ม และความพร้อมของระบบ

## 1. หน้าที่ของผู้ดูแลระบบ

- ตั้งค่าชื่อร้าน โลโก้ favicon ข้อมูลติดต่อ และข้อความท้ายใบเสร็จ
- เพิ่มและแก้ไขสินค้า หมวดสินค้า แบรนด์/แหล่งสินค้า และหน่วยนับ
- ดูแลลูกค้า ซัพพลายเออร์ และรายการซื้อเข้า
- จัดการกฎสะสมแต้ม/รางวัล/คูปอง
- ตรวจรายงานขายและรายงานสต็อก
- จัดการผู้ใช้งาน บทบาท และสิทธิ์
- ตรวจระบบหลัง migrate, seed, build หรือ deploy
- ดู audit log เมื่อเกิดเหตุการณ์ผิดปกติ (void, การแก้ข้อมูลสำคัญ)

## 2. ตั้งค่าร้าน

ไปที่ `ตั้งค่า > ตั้งค่าระบบ > ตั้งค่าทั่วไป`

ตรวจแท็บสำคัญ:

- `ข้อมูลร้าน`: ชื่อร้าน URL คำอธิบาย และคำค้นหา
- `ช่องทางติดต่อ`: ที่อยู่ เบอร์โทร อีเมล และเวลาทำการ
- `โลโก้และภาพ`: โลโก้ favicon และไอคอน Apple
- `สถานะระบบ`: เปิดหรือปิดระบบชั่วคราว
- `ตั้งค่าใบเสร็จ`: ข้อความท้ายใบเสร็จและรายการข้อมูลที่ต้องแสดง

ค่าแนะนำสำหรับร้านนี้:

- ชื่อร้าน: `หม่าล่าแดนซ์ by ขวัญใจ`
- เบอร์โทร: `080-000-0000`
- เวลาทำการ: `เปิดทุกวัน 11:00 - 22:00 น.`
- ข้อความท้ายใบเสร็จ: `ขอบคุณที่อุดหนุนหม่าล่าแดนซ์ by ขวัญใจ แซ่บแล้วกลับมาอีกนะคะ`

## 3. จัดการสินค้า

เมนูหลักอยู่ใต้ `สินค้า`

ลำดับการตั้งค่าที่แนะนำ:

1. สร้างหน่วยนับ เช่น `ไม้`, `แก้ว`, `ขวด`, `ชุด`, `ถุง`, `กรัม`
2. สร้างหมวดสินค้า เช่น `หม่าล่าเสียบไม้`, `ชุดหม่าล่า`, `เครื่องดื่มเย็น`
3. สร้างแบรนด์/แหล่งสินค้า เช่น `ครัวกลาง`, `หน้าร้าน`, `เครื่องดื่มสด`
4. เพิ่มสินค้า พร้อม SKU ราคา ต้นทุน สต็อก และรูปสินค้า
5. เปิดสถานะสินค้าเฉพาะรายการที่พร้อมขาย

ข้อสำคัญ: สินค้าภาษาไทยควรมี slug ที่ไม่ว่าง ระบบ seeder ใช้ slug จาก SKU เพื่อเลี่ยงปัญหา Thai slug ว่าง

หลังแก้สินค้า ระบบจะ bust cache POS อัตโนมัติ

## 4. จัดการระบบสะสมแต้ม/รางวัล

เมนู `จัดการสะสมแต้ม/รางวัล` (เฉพาะ Owner/Admin)

ตารางที่เกี่ยวข้อง:

- `reward_rules`: เก็บกฎรางวัล/โปรโมชั่น
- `reward_usages`: บันทึกการใช้แต้ม/รางวัลของลูกค้า (เชื่อมกับ Order)
- `customers.points` / `customers.total_spent` / `customers.visit_count` / `customers.last_visit_at`: ข้อมูลสะสมของลูกค้า

ระบบจะคำนวณแต้มอัตโนมัติเมื่อบันทึก Order (1 แต้ม / 10 บาท) และคืนแต้มเมื่อ void

ขั้นตอนสร้างกฎใหม่:

1. กด `เพิ่มกฎของรางวัล`
2. ใส่ชื่อรางวัล (เช่น "ส่วนลด 10% สำหรับลูกค้าวันเกิด")
3. เลือกประเภท (`type`):
   - `earn_points` / `redeem_points` / `coupon` / `tier` / `birthday`
4. เลือกประเภทสิทธิประโยชน์ (`benefit_type`):
   - `percent_discount` / `fixed_discount` / `bonus_points`
5. ใส่ค่า `benefit_value`
6. ตั้งเงื่อนไขอื่น (ยอดซื้อขั้นต่ำ, แต้มที่ต้องใช้, จำกัดการใช้, วันที่)
7. เปิด `Active` และ `Stackable` ตามต้องการ
8. กด `บันทึกกฎ`

ตัวอย่างกฎที่ใช้บ่อย:

| กฎ | type | benefit_type | benefit_value | เงื่อนไข |
|---|---|---|---|---|
| ใช้ 100 แต้ม ส่วนลด 50 บาท | redeem_points | fixed_discount | 50 | required_points=100 |
| ลูกค้าวันเกิดลด 10% | birthday | percent_discount | 10 | - |
| ซื้อครบ 500 ลด 50 | earn_points | fixed_discount | 50 | min_purchase=500 |
| คูปอง MALA10 ลด 10% | coupon | percent_discount | 10 | coupon_code=MALA10 |

## 5. จัดการซื้อเข้าและสต็อก

ใช้เมนู `ซื้อเข้า` เมื่อรับวัตถุดิบหรือสินค้าเข้าร้าน

ขั้นตอน:

1. เลือกซัพพลายเออร์
2. เลือกสินค้าที่รับเข้า
3. ใส่จำนวนและต้นทุน
4. ตรวจยอดรวม
5. บันทึก
6. เปิดรายงานสต็อกเพื่อตรวจว่าสต็อกเพิ่มถูกต้อง (และมี record ใน `stock_movements` type=`purchase`)

## 6. ลูกค้าและซัพพลายเออร์

ลูกค้าหลักที่ควรมี:

- `ลูกค้าหน้าร้าน`
- `ลูกค้าประจำ`
- `ออเดอร์เดลิเวอรี`

ลูกค้าประจำควรกรอก `birth_date` เพื่อใช้สิทธิ์โปรโมชั่นวันเกิดได้

ซัพพลายเออร์หลักที่ควรมี:

- `ร้านวัตถุดิบสด`
- `ร้านเครื่องดื่มและบรรจุภัณฑ์`
- `ร้านซอสและเครื่องปรุง`

ควรใส่เบอร์โทรและหมายเหตุพื้นที่ให้ครบ เพื่อให้ติดต่อได้เร็วเมื่อของใกล้หมด

## 7. ผู้ใช้งาน บทบาท และสิทธิ์

ไปที่ `ตั้งค่า > ตั้งค่าระบบ > บทบาทและสิทธิ์`

ระบบใช้ Spatie Laravel Permission

Roles ที่มีในระบบ:

- **Owner**: สิทธิ์ครบทุกเมนู รวม User Management
- **Admin**: สิทธิ์ครบยกเว้น User Management
- **Cashier**: เฉพาะ POS ขายหน้าร้าน

หลังแก้สิทธิ์ ให้ทดสอบ login ด้วยบัญชีของบทบาทนั้นจริงเสมอ

## 8. Audit Log

ระบบเก็บ `audit_logs` สำหรับการกระทำที่สำคัญ เช่น void order

ตรวจดูได้ผ่าน database query หรือใช้ tinker:

```powershell
.\.tools\php\php.exe artisan tinker
>>> App\Models\AuditLog::latest()->take(20)->get()
```

## 9. รายงานที่ต้องตรวจ

- `รายงาน > สรุปยอดขาย`: ใช้ปิดยอดรายวัน
- `รายงาน > รายงานขาย`: ใช้ตรวจบิลรายใบ
- `รายงาน > รายงานสต็อก`: ใช้ดูสินค้าคงเหลือและวางแผนซื้อเข้า

## 10. คำสั่งดูแลระบบ

บน Windows workspace นี้:

```powershell
.\.tools\php\php.exe artisan migrate:fresh --seed --force
npm.cmd run build
.\.tools\php\php.exe artisan view:cache
```

รัน dev server:

```powershell
.\.tools\php\php.exe artisan serve --host=127.0.0.1 --port=8000
npm.cmd run dev
```

ล้าง cache ระบบ:

```powershell
.\.tools\php\php.exe artisan optimize:clear
```

หรือผ่าน URL หลังร้าน: `/admin/clear-all`

## 11. แนวทางแก้ไขเมื่อ Query หรือการแสดงผลช้า

หัวข้อนี้รวบรวมขั้นตอนตรวจสอบและแก้ไขปัญหา performance ที่พบได้บ่อยในระบบนี้ ใช้เมื่อพบว่า dashboard, POS, รายงาน หรือ DataTables โหลดช้า

### 11.1 ขั้นตอนวินิจฉัยเบื้องต้น (Diagnose)

1. เปิด **Browser DevTools > Network** ดูว่า request ใดใช้เวลาเกิน 1 วินาที
2. เปิด **Console** ดู error JS หรือ warning การ render ซ้ำ
3. หากเป็น query backend ให้เปิด Laravel Debugbar (เฉพาะ local) เพื่อดูจำนวน query และเวลาที่ใช้
4. บน Vercel ดู **Function Logs** ใน Dashboard เพื่อจับ slow request

ตัวชี้วัดที่ยอมรับได้:

- Dashboard load: < 800ms
- POS product list: < 500ms
- DataTables ajax: < 1s ต่อ page
- Save Order: < 1.5s

### 11.2 ปัญหา N+1 Query

**อาการ**: หน้า list มี query หลายร้อยครั้ง ทำให้โหลดช้า

**สาเหตุ**: Loop ผ่าน collection แล้วเรียก relation ในแต่ละ iteration เช่น
```php
$orders = Order::all();
foreach ($orders as $order) {
    echo $order->customer->name; // เกิด query ใหม่ทุกรอบ
}
```

**วิธีแก้**: ใช้ Eager Loading
```php
$orders = Order::with('customer')->get();
// หรือ with('customer', 'products.product')
```

ตรวจสอบใน controller ของระบบ: `OrderController::index` ใช้ `Order::with('customer')->withSum('products', 'quantity')` ถูกต้องแล้ว หากเพิ่ม relation ใหม่ในตาราง ให้เช็คว่ายังเข้า eager load อยู่หรือไม่

### 11.3 ปัญหา DataTables ช้า

**อาการ**: ตาราง `รายการขาย`, `รายการสินค้า`, `กฎรางวัล` โหลด page ละหลายวินาที

**วิธีแก้**:

1. **ตรวจ serverSide: true** ทุก DataTable ที่มีข้อมูลเกิน 100 รายการต้องเปิด server-side processing
2. **เพิ่ม index** ในคอลัมน์ที่ใช้ ORDER BY และ WHERE บ่อย เช่น `orders.created_at`, `products.sku`, `customers.phone`
3. **จำกัด columns** ที่ rawColumns ลดเฉพาะที่จำเป็น
4. **ตรวจ relation** ที่ดึงมาเฉพาะ field ที่ใช้ เช่น `Order::with('customer:id,name')`

### 11.4 ปัญหา Dashboard โหลดช้า

**อาการ**: แดชบอร์ดใช้เวลา > 2 วินาที

**วิธีแก้**:

1. ใช้ aggregate SQL แทน PHP collection loop:
   - ❌ `Order::all()->sum('total')` (โหลดทั้งตารางมา RAM)
   - ✅ `Order::sum('total')` (SQL aggregate)
2. ใช้ `Cache::remember` ครอบ aggregate ที่หนัก (ดูตัวอย่างใน `DashboardController`)
3. ตั้งค่า cache TTL สั้น (เช่น 60-300 วินาที) เพื่อให้ข้อมูลใหม่พอ

### 11.5 ปัญหา POS โหลดสินค้าช้า

**อาการ**: เปิดหน้า POS ครั้งแรกใช้เวลานาน

**วิธีแก้**:

1. `CartController::getProducts` ใช้ `Cache::remember` cache 10 นาทีสำหรับ page ที่ไม่มี filter — ตรวจว่า cache driver รองรับ (บน Vercel เป็น `array` ใช้ได้แต่ไม่ persist)
2. เพิ่ม **pagination** (`->paginate(96)`) อย่าโหลดสินค้าทั้งหมดมาทีเดียว
3. ใช้ **image lazy loading** (`loading="lazy"` ใน `<img>` แล้ว)
4. Bundle JS ใช้ `manualChunks` ใน [vite.config.js](../../vite.config.js) แยก vendor

### 11.6 ปัญหา Cache ไม่หาย (Stale Data)

**อาการ**: แก้ราคาสินค้าแล้ว POS ยังแสดงราคาเดิม

**วิธีแก้**:

1. Bust cache หลังบันทึก/แก้ไข/ลบ: `Cache::flush()` หรือ `Cache::forget('pos_products_page_*')`
2. ระบบนี้ bust cache อัตโนมัติใน `ProductController::store/update/destroy` แล้ว — ถ้าเพิ่ม field/mutation ใหม่ต้องเพิ่ม cache bust ด้วย
3. กดล้าง cache ผ่าน `/admin/clear-all` หรือ `php artisan optimize:clear`

### 11.7 ปัญหา Database Connection (Vercel)

**อาการ**: 500 Internal Server Error เป็นช่วงๆ บน Vercel

**สาเหตุ**: serverless function เปิด connection ใหม่ทุก request หาก connection pool เต็มจะ fail

**วิธีแก้**:

1. ใช้ **Supabase Transaction Pooler** (`...pooler.supabase.com:6543`) ไม่ใช่ Direct Connection
2. ตั้ง `DATABASE_URL` ให้ใช้ pooler URL เท่านั้น
3. ตรวจ `config/database.php` ว่า `PDO::ATTR_EMULATE_PREPARES => false` (ค่าตายตัว ไม่อ่านจาก env)
4. หลีกเลี่ยง long-running transaction (Vercel function timeout 60 วินาที)

### 11.8 ปัญหา Region Mismatch (Latency สูงระหว่าง Vercel ↔ DB)

**อาการ**: ทุก request ช้า 200-500ms แม้ query เบา

**สาเหตุ**: Vercel function อยู่คนละ region กับ Supabase

**วิธีแก้**:

1. ตั้ง `regions: ["sin1"]` ใน `vercel.json` (Singapore) ให้ใกล้ Supabase region
2. ตรวจว่า Supabase project สร้างใน region ใกล้กัน (เช่น Southeast Asia - Singapore)

### 11.9 ปัญหา Composite Index ขาด

**อาการ**: query ที่กรองด้วยหลายคอลัมน์ช้า

**วิธีแก้**: เพิ่ม composite index ใน migration ใหม่ ตัวอย่างที่ระบบนี้มีอยู่แล้ว:

- `orders(customer_id, created_at)` — สำหรับรายงานขายตามลูกค้าและเวลา
- `products(status, quantity)` — สำหรับ POS query สินค้าที่พร้อมขาย
- `stock_movements(product_id, created_at)` — สำหรับ history สต็อก
- `reward_usages(reward_rule_id, customer_id)` — สำหรับนับการใช้รางวัล

### 11.10 ปัญหา Frontend Bundle ใหญ่

**อาการ**: หน้าเว็บโหลดช้าครั้งแรก network tab เห็น JS > 1MB

**วิธีแก้**:

1. ใช้ Vite `manualChunks` แยก vendor (มีแล้วใน `vite.config.js`): react-vendor, ui-libs, http
2. ใช้ React lazy + Suspense สำหรับ component หนัก
3. รัน `npm run build` แล้วดู bundle ขนาด ถ้า > 600KB ต่อ chunk ให้แยกเพิ่ม
4. ใช้ image format ใหม่ (.webp) แทน .png/.jpg ถ้าเป็นไปได้

### 11.11 Checklist ก่อนแจ้งว่าระบบช้า

1. ตรวจ Network tab — request ไหนช้า?
2. ตรวจว่ารัน `php artisan config:cache` และ `php artisan route:cache` หรือยัง (production)
3. ตรวจว่า build asset ใหม่หรือยัง (`npm run build`)
4. ลองล้าง cache (`/admin/clear-all`)
5. ตรวจว่า DB region ตรงกับ Vercel region
6. ตรวจ Vercel Function Logs ดู cold start vs warm
7. ตรวจขนาด table หลัก (`orders`, `products`, `stock_movements`) — ถ้าเกิน 100k row อาจต้อง partition

## 12. Production Deployment (Vercel + Supabase)

ระบบถูก deploy ที่ Vercel โดยใช้:

- **Runtime**: `vercel-php@0.7.4`
- **Database**: Supabase Postgres (Transaction Pooler)
- **Region**: `sin1` (Singapore)

Environment variables ที่ต้องตั้งใน Vercel Dashboard:

```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://mala-dance-by-kwanjai.vercel.app
APP_KEY=<base64 key>
DB_CONNECTION=pgsql
DATABASE_URL=<Supabase Transaction Pooler URL>
SESSION_DRIVER=cookie
CACHE_DRIVER=array
LOG_CHANNEL=stderr
QUEUE_CONNECTION=sync
```

ข้อจำกัดของ Vercel ที่ต้องรู้:

1. **Filesystem read-only** ยกเว้น `/tmp` — การ upload รูปสินค้าหรือโลโก้ต้องใช้ external storage (เช่น S3 หรือ Supabase Storage) ในอนาคต
2. **Cache driver = array** — ไม่ persist ระหว่าง request หาก require persistent cache ต้องใช้ Redis (เช่น Upstash)
3. **Session driver = cookie** — session อยู่ใน cookie ของ client ไม่ใช่ฝั่ง server
4. **Vite manifest** — `public/build/` ต้องถูก build และ commit ลง git (รัน `npm run build` ก่อน push)

## 13. Checklist หลังอัปเดตระบบ

1. เข้าหน้า login ได้
2. เมนูหลังร้านเป็นภาษาไทย
3. POS แสดงรูปสินค้าและค้นหาสินค้าได้
4. ทดลองขาย 1 บิลและพิมพ์ใบเสร็จได้
5. ทดลอง void 1 บิล สต็อกคืนถูกต้อง
6. ทดลองซื้อเข้าแล้วสต็อกเพิ่ม
7. รายงานขายและรายงานสต็อกเปิดได้
8. หน้า `จัดการสะสมแต้ม/รางวัล` เปิดและสร้างกฎใหม่ได้
9. ตั้งค่าโลโก้และข้อความท้ายใบเสร็จแสดงถูกต้อง
10. สิทธิ์ผู้ใช้งานไม่เปิดเมนูเกินจำเป็น

## หน้าอ่านคู่มือบนเว็บ

ผู้ดูแลระบบสามารถอ่านคู่มือทั้งหมดจากหลังร้านได้ที่:

```text
/admin/manuals
```

เมนู `คู่มือการใช้งาน` อยู่ใน sidebar หลังร้าน และใช้สำหรับเปิดอ่านไฟล์คู่มือ Markdown แบบ read-only ได้แก่ quick start, owner manual, admin manual, user manual และ system doc โดยระบบจำกัดเฉพาะรายการที่อนุญาตไว้เท่านั้น

## นำเข้ารายการสินค้า

ไปที่ `สินค้า > นำเข้าสินค้า` เพื่ออัปโหลดรายการสินค้าแบบ Excel หรือ CSV

ขั้นตอนที่แนะนำ:

1. กด `ดาวน์โหลดเทมเพลต`
2. กรอกข้อมูลตามหัวคอลัมน์ในไฟล์ตัวอย่าง
3. ตรวจว่า SKU ไม่ว่างและไม่ซ้ำกันในไฟล์เดียวกัน
4. อัปโหลดไฟล์กลับเข้าระบบ
5. ตรวจรายการที่เมนู `สินค้า > รายการสินค้า`

คอลัมน์ที่รองรับ:

- `name`: ชื่อสินค้า
- `sku`: รหัสสินค้า ใช้เป็นตัวอ้างอิงหลักและใช้สร้าง slug
- `description`: รายละเอียดสินค้า
- `category`: หมวดสินค้า ถ้ายังไม่มีระบบจะสร้างให้อัตโนมัติ
- `brand`: แบรนด์หรือแหล่งสินค้า ถ้ายังไม่มีระบบจะสร้างให้อัตโนมัติ
- `unit`: หน่วยนับ เช่น ไม้, แก้ว, ขวด
- `price`: ราคาขาย
- `discount`: ส่วนลด ใส่ 0 ได้
- `discount_type`: `fixed` หรือ `percentage`
- `purchase_price`: ต้นทุน
- `quantity`: จำนวนสต็อกเริ่มต้น
- `expire_date`: วันหมดอายุ ถ้าไม่มีให้เว้นว่าง
- `status`: 1 หรือ `ใช้งาน` สำหรับเปิดขาย
- `image`: path รูปสินค้าในระบบ ถ้าไม่มีให้เว้นว่าง

หาก SKU มีอยู่แล้ว ระบบจะอัปเดตสินค้านั้นแทนการสร้างสินค้าซ้ำ เพื่อให้ใช้ไฟล์เดิมปรับราคา สต็อก หรือรายละเอียดสินค้าได้
