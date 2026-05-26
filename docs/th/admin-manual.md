# คู่มือผู้ดูแลระบบ

คู่มือนี้สำหรับผู้ดูแลระบบที่ต้องตั้งค่าร้าน จัดการข้อมูลหลัก ดูแลผู้ใช้งาน สิทธิ์ สินค้า สต็อก รายงาน และความพร้อมของระบบ

## 1. เข้าสู่ระบบ

URL: `https://mala-dance-by-kwanjai.vercel.app/login`

บัญชีผู้ดูแลระบบ: `admin` / `admin` (เปลี่ยนรหัสผ่านในเมนู `โปรไฟล์` ทันทีหลังเข้าครั้งแรก)

## 2. หน้าที่ของผู้ดูแลระบบ

- ตั้งค่าชื่อร้าน โลโก้ favicon ข้อมูลติดต่อ และข้อความท้ายใบเสร็จ
- เพิ่มและแก้ไขสินค้า หมวดสินค้า แบรนด์/แหล่งสินค้า และหน่วยนับ
- ดูแลลูกค้า ซัพพลายเออร์ และรายการซื้อเข้า
- ตรวจรายงานขายและรายงานสต็อก
- จัดการผู้ใช้งาน บทบาท และสิทธิ์
- ตรวจระบบหลัง deploy
- ดู audit log เมื่อเกิดเหตุการณ์ผิดปกติ (void, การแก้ข้อมูลสำคัญ)

## 3. ตั้งค่าร้าน

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

## 4. จัดการสินค้า

เมนูหลักอยู่ใต้ `สินค้า`

ลำดับการตั้งค่าที่แนะนำ:

1. สร้างหน่วยนับ เช่น `ไม้`, `แก้ว`, `ขวด`, `ชุด`, `ถุง`, `กรัม`
2. สร้างหมวดสินค้า เช่น `เนื้อสัตว์`, `ซีฟู้ด`, `ผักและเห็ด`, `เมนูพิเศษ`
3. สร้างแบรนด์/แหล่งสินค้า เช่น `MALA Dance`, `ครัวกลาง`, `หน้าร้าน`
4. เพิ่มสินค้า พร้อม SKU ราคา ต้นทุน สต็อก และรูปสินค้า
5. เปิดสถานะสินค้าเฉพาะรายการที่พร้อมขาย

ข้อสำคัญ: สินค้าภาษาไทยควรมี slug ที่ไม่ว่าง ระบบ seeder ใช้ slug จาก SKU เพื่อเลี่ยงปัญหา Thai slug ว่าง

หลังแก้สินค้า ระบบจะ bust cache POS อัตโนมัติ

## 5. จัดการช่องทางการขาย (Sales Channels)

ระบบรองรับการบันทึกออเดอร์จากหลายช่องทาง — หน้าร้าน, Grab Food, LINE MAN, Shopee Food หรือเพิ่มแพลตฟอร์มใหม่ได้

ไปที่ `การขาย > ช่องทางการขาย` (เฉพาะ Admin)

หน้าจัดการแสดง:

- **ชื่อช่องทาง** + สี + ไอคอน FontAwesome
- **Slug** — รหัสภายใน (เช่น `grab`, `line_man`)
- **ค่า Commission (%)** — ใช้คำนวณค่าธรรมเนียมแพลตฟอร์มอัตโนมัติ
- **ลำดับการแสดง** ใน dropdown POS
- **สถานะ** เปิด/ปิด

ค่า commission ใน database ปัจจุบัน (อิงตามอัตรามาตรฐาน — Admin ปรับให้ตรงสัญญาจริงของร้าน):

| ช่องทาง | Commission |
|---|---|
| หน้าร้าน | 0% |
| Grab Food | 32% |
| LINE MAN | 30% |
| Shopee Food | 30% |

ขั้นตอนเพิ่มช่องทางใหม่:

1. กด `เพิ่มช่องทางการขาย`
2. ใส่ชื่อ เช่น `Robinhood`
3. ปล่อย slug ให้ระบบสร้าง หรือใส่เอง
4. ใส่ % commission ที่ตกลงกับแพลตฟอร์ม
5. เลือกสี (สำหรับ badge ในรายการขาย)
6. เลือก icon FontAwesome (เช่น `fas fa-motorcycle`)
7. บันทึก

**สำคัญ**: ช่องทาง `หน้าร้าน` (slug=walk_in) ลบไม่ได้ เพราะเป็นค่าเริ่มต้นของระบบ

### การใช้ใน POS

แคชเชียร์เลือก `ช่องทางการขาย` ใน POS ก่อนชำระเงิน:

- ถ้าเลือกหน้าร้าน → commission = 0
- ถ้าเลือกแพลตฟอร์ม delivery → แสดงค่า commission ประมาณการให้ดู และมีช่องกรอก `เลขที่ออเดอร์` จากแอป

ระบบบันทึก `sales_channel_id`, `platform_fee` (อัตโนมัติ), และ `platform_order_ref` ในตาราง `orders`

### รายงานยอดขายแยกตามแพลตฟอร์ม

เมนู `รายงาน > ยอดขายแยกตามแพลตฟอร์ม` แสดง:

- จำนวนออเดอร์ต่อช่องทาง
- ยอดขายก่อนหัก commission (gross)
- ค่า commission รวม
- รายได้จริง (net) = ยอดขาย - commission

## 6. จัดการซื้อเข้าและสต็อก

ใช้เมนู `ซื้อเข้า` เมื่อรับวัตถุดิบหรือสินค้าเข้าร้าน

ขั้นตอน:

1. เลือกซัพพลายเออร์
2. เลือกสินค้าที่รับเข้า
3. ใส่จำนวนและต้นทุน
4. ตรวจยอดรวม
5. บันทึก
6. เปิดรายงานสต็อกเพื่อตรวจว่าสต็อกเพิ่มถูกต้อง (และมี record ใน `stock_movements` type=`purchase`)

## 7. ลูกค้าและซัพพลายเออร์

ลูกค้าหลักที่ควรมี:

- `ลูกค้าหน้าร้าน`
- `ลูกค้าประจำ`
- `ออเดอร์เดลิเวอรี`

ซัพพลายเออร์หลักที่ควรมี:

- `ร้านวัตถุดิบสด`
- `ร้านเครื่องดื่มและบรรจุภัณฑ์`
- `ร้านซอสและเครื่องปรุง`

ควรใส่เบอร์โทรและหมายเหตุพื้นที่ให้ครบ เพื่อให้ติดต่อได้เร็วเมื่อของใกล้หมด

## 8. ผู้ใช้งาน บทบาท และสิทธิ์

ไปที่ `ตั้งค่า > ตั้งค่าระบบ > บทบาทและสิทธิ์`

ระบบใช้ Spatie Laravel Permission

Roles ที่มีในระบบ:

- **Admin**: สิทธิ์ครบทุกเมนู รวม User Management
- **Cashier**: เฉพาะ POS ขายหน้าร้าน
- **Sales associate**: ขายและดูรายการขาย

หลังแก้สิทธิ์ ให้ทดสอบ login ด้วยบัญชีของบทบาทนั้นจริงเสมอ

## 9. Audit Log

ระบบเก็บ `audit_logs` สำหรับการกระทำที่สำคัญ เช่น void order รวมข้อมูล user_id, ip, user_agent, target

## 10. รายงานที่ต้องตรวจ

- `รายงาน > สรุปยอดขาย`: ใช้ปิดยอดรายวัน
- `รายงาน > รายงานขาย`: ใช้ตรวจบิลรายใบ
- `รายงาน > รายงานสต็อก`: ใช้ดูสินค้าคงเหลือและวางแผนซื้อเข้า
- `รายงาน > ยอดขายแยกตามแพลตฟอร์ม`: ดูยอดขาย/ค่า commission แยกตาม Grab/LINE MAN/Shopee/หน้าร้าน

## 11. การล้าง Cache บนระบบ

หากแสดงข้อมูลค้างไม่อัปเดต ให้กดล้างผ่าน URL หลังร้าน:

```text
/admin/clear-all
```

## 12. แนวทางแก้ไขเมื่อ Query หรือการแสดงผลช้า

หัวข้อนี้รวบรวมขั้นตอนตรวจสอบและแก้ไขปัญหา performance ที่พบได้บ่อยในระบบนี้ ใช้เมื่อพบว่า dashboard, POS, รายงาน หรือ DataTables โหลดช้า

### 11.1 ขั้นตอนวินิจฉัยเบื้องต้น (Diagnose)

1. เปิด **Browser DevTools > Network** ดูว่า request ใดใช้เวลาเกิน 1 วินาที
2. เปิด **Console** ดู error JS หรือ warning การ render ซ้ำ
3. ดู **Function Logs** ใน Vercel Dashboard เพื่อจับ slow request

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

**อาการ**: ตาราง `รายการขาย`, `รายการสินค้า` โหลด page ละหลายวินาที

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

1. `CartController::getProducts` ใช้ `Cache::remember` cache 10 นาทีสำหรับ page ที่ไม่มี filter — ตรวจว่า cache driver รองรับ (บน Vercel เป็น `array` ใช้ได้แต่ไม่ persist ข้าม request)
2. เพิ่ม **pagination** (`->paginate(96)`) อย่าโหลดสินค้าทั้งหมดมาทีเดียว
3. ใช้ **image lazy loading** (`loading="lazy"` ใน `<img>` แล้ว)
4. Bundle JS ใช้ `manualChunks` ใน `vite.config.js` แยก vendor

### 11.6 ปัญหา Cache ไม่หาย (Stale Data)

**อาการ**: แก้ราคาสินค้าแล้ว POS ยังแสดงราคาเดิม

**วิธีแก้**:

1. Bust cache หลังบันทึก/แก้ไข/ลบ: `Cache::flush()` หรือ `Cache::forget('pos_products_page_*')`
2. ระบบนี้ bust cache อัตโนมัติใน `ProductController::store/update/destroy` แล้ว — ถ้าเพิ่ม field/mutation ใหม่ต้องเพิ่ม cache bust ด้วย
3. กดล้าง cache ผ่าน `/admin/clear-all`

### 11.7 ปัญหา Database Connection (Vercel)

**อาการ**: 500 Internal Server Error เป็นช่วงๆ

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
2. ตรวจว่า Supabase project สร้างใน region ใกล้กัน (Southeast Asia - Singapore)

### 11.9 ปัญหา Composite Index ขาด

**อาการ**: query ที่กรองด้วยหลายคอลัมน์ช้า

**วิธีแก้**: เพิ่ม composite index ใน migration ใหม่ ตัวอย่างที่ระบบนี้มีอยู่แล้ว:

- `orders(customer_id, created_at)` — สำหรับรายงานขายตามลูกค้าและเวลา
- `products(status, quantity)` — สำหรับ POS query สินค้าที่พร้อมขาย
- `stock_movements(product_id, created_at)` — สำหรับ history สต็อก
- `order_transactions(order_id, created_at)` — สำหรับ history การชำระ

### 11.10 ปัญหา Frontend Bundle ใหญ่

**อาการ**: หน้าเว็บโหลดช้าครั้งแรก network tab เห็น JS > 1MB

**วิธีแก้**:

1. ใช้ Vite `manualChunks` แยก vendor (มีแล้วใน `vite.config.js`): react-vendor, ui-libs, http
2. ใช้ React lazy + Suspense สำหรับ component หนัก
3. ตรวจ bundle ขนาด ถ้า > 600KB ต่อ chunk ให้แยกเพิ่ม
4. ใช้ image format ใหม่ (.webp) แทน .png/.jpg ถ้าเป็นไปได้

### 11.11 Checklist ก่อนแจ้งว่าระบบช้า

1. ตรวจ Network tab — request ไหนช้า?
2. ตรวจว่าเคยเปิดหน้าก่อนนานแล้วหรือไม่ (Vercel cold start)
3. ลองล้าง cache (`/admin/clear-all`)
4. ตรวจว่า DB region ตรงกับ Vercel region
5. ตรวจ Vercel Function Logs ดู cold start vs warm
6. ตรวจขนาด table หลัก (`orders`, `products`, `stock_movements`) — ถ้าเกิน 100k row อาจต้อง partition

## 13. Production Architecture (Vercel + Supabase)

ระบบถูก deploy ที่ Vercel โดยใช้:

- **Runtime**: `vercel-php@0.7.4`
- **Database**: Supabase Postgres (Transaction Pooler)
- **Region**: `sin1` (Singapore)

ข้อจำกัดของ Vercel ที่ต้องรู้:

1. **Filesystem read-only** ยกเว้น `/tmp` — การ upload รูปสินค้าหรือโลโก้ต้องใช้ external storage (เช่น S3 หรือ Supabase Storage) ในอนาคต
2. **Cache driver = array** — ไม่ persist ระหว่าง request หาก require persistent cache ต้องใช้ Redis (เช่น Upstash)
3. **Session driver = cookie** — session อยู่ใน cookie ของ client ไม่ใช่ฝั่ง server
4. **Function timeout 60s** — งานหนัก (export Excel ใหญ่) อาจ timeout

## 14. Checklist หลัง Deploy

1. เข้าหน้า login ได้
2. เมนูหลังร้านเป็นภาษาไทย
3. POS แสดงรูปสินค้าและค้นหาสินค้าได้
4. ทดลองขาย 1 บิลและพิมพ์ใบเสร็จได้
5. ทดลอง void 1 บิล สต็อกคืนถูกต้อง
6. ทดลองซื้อเข้าแล้วสต็อกเพิ่ม
7. รายงานขายและรายงานสต็อกเปิดได้
8. ตั้งค่าโลโก้และข้อความท้ายใบเสร็จแสดงถูกต้อง
9. สิทธิ์ผู้ใช้งานไม่เปิดเมนูเกินจำเป็น

## 15. นำเข้ารายการสินค้า

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
