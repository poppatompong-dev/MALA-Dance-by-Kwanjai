# Thai QPOS Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Turn the local QPOS install into a Thai-first POS/shop management system for a mala and drinks shop, with professional visual polish, Thai receipts, sensible permissions, detailed Thai manuals, and a repeatable test path before cloud deployment.

**Architecture:** Keep the existing Laravel + Blade + React + Vite architecture. Use Thai wording and mala/drink examples in source views, React components, seed/demo data, receipt templates, and documentation without hard-coding the business model so categories, products, users, and branches can expand later.

**Tech Stack:** Laravel 10, Blade, React 18, Vite 4, AdminLTE/Bootstrap styling, Spatie Laravel Permission, SQLite for local test, MySQL/MariaDB recommended for production cloud.

---

## File Structure

**Audit and documentation**
- Create: `docs/th/quick-start.md` - Thai local startup and first test guide.
- Create: `docs/th/user-manual.md` - Thai cashier/staff user manual.
- Create: `docs/th/admin-manual.md` - Thai admin/operator manual.
- Create: `docs/th/feature-audit.md` - feature coverage found during trial use.
- Create: `docs/th/code-audit.md` - code risks and deployment notes.
- Create: `docs/th/design-system.md` - approved visual redesign tokens, copy rules, and UI treatment.
- Modify: `README.md` - add Thai quick links and local test summary.

**Local install and verification**
- Modify: `.gitignore` - keep local tools, logs, database, and Vite hot files ignored.
- Modify: `.env.example` - document local SQLite option without exposing local secrets.
- Modify: `app/Http/Controllers/Backend/DashboardController.php` - preserve SQLite-compatible dashboard query.

**Thai UI and redesign**
- Modify: `resources/views/frontend/authentication/login.blade.php`
- Modify: `resources/views/frontend/authentication/forget-password.blade.php`
- Modify: `resources/views/frontend/authentication/login-otp.blade.php`
- Modify: `resources/views/frontend/authentication/new-password.blade.php`
- Modify: `resources/views/frontend/authentication/reset.blade.php`
- Modify: `resources/views/frontend/authentication/sign-up.blade.php`
- Modify: `resources/views/backend/master.blade.php`
- Modify: `resources/views/backend/layouts/navbar.blade.php`
- Modify: `resources/views/backend/layouts/sidebar.blade.php`
- Modify: `resources/views/backend/layouts/footer.blade.php`
- Modify: `resources/views/backend/index.blade.php`
- Modify: `resources/css/app.css`

**Thai master data and operations**
- Modify: `resources/views/backend/products/index.blade.php`
- Modify: `resources/views/backend/products/create.blade.php`
- Modify: `resources/views/backend/products/edit.blade.php`
- Modify: `resources/views/backend/products/import.blade.php`
- Modify: `resources/views/backend/categories/index.blade.php`
- Modify: `resources/views/backend/categories/create.blade.php`
- Modify: `resources/views/backend/categories/edit.blade.php`
- Modify: `resources/views/backend/brands/index.blade.php`
- Modify: `resources/views/backend/brands/create.blade.php`
- Modify: `resources/views/backend/brands/edit.blade.php`
- Modify: `resources/views/backend/units/index.blade.php`
- Modify: `resources/views/backend/units/create.blade.php`
- Modify: `resources/views/backend/units/edit.blade.php`
- Modify: `resources/views/backend/customers/index.blade.php`
- Modify: `resources/views/backend/customers/create.blade.php`
- Modify: `resources/views/backend/customers/edit.blade.php`
- Modify: `resources/views/backend/suppliers/index.blade.php`
- Modify: `resources/views/backend/suppliers/create.blade.php`
- Modify: `resources/views/backend/suppliers/edit.blade.php`
- Modify: `resources/views/backend/purchase/index.blade.php`
- Modify: `resources/views/backend/purchase/create.blade.php`
- Modify: `resources/views/backend/purchase/edit.blade.php`
- Modify: `resources/views/backend/purchase/products.blade.php`
- Modify: `resources/views/backend/orders/index.blade.php`
- Modify: `resources/views/backend/orders/collection/create.blade.php`
- Modify: `resources/views/backend/orders/collection/index.blade.php`
- Modify: `resources/views/backend/reports/inventory.blade.php`
- Modify: `resources/views/backend/reports/sale-report.blade.php`
- Modify: `resources/views/backend/reports/sale-summery.blade.php`
- Modify: `resources/views/backend/profile/index.blade.php`
- Modify: `resources/views/backend/users/index.blade.php`
- Modify: `resources/views/backend/users/create.blade.php`
- Modify: `resources/views/backend/users/edit.blade.php`
- Modify: `resources/views/backend/settings/role/index.blade.php`
- Modify: `resources/views/backend/settings/role/permissions.blade.php`
- Modify: `resources/views/backend/settings/permission/index.blade.php`
- Modify: `resources/views/backend/settings/website-settings/general.blade.php`

**POS, receipt, and permissions**
- Modify: `resources/views/backend/cart/index.blade.php`
- Modify: `resources/js/components/Pos.jsx`
- Modify: `resources/js/components/Cart.jsx`
- Modify: `resources/js/components/CutomerSelect.jsx`
- Modify: `resources/js/components/Purchase/Purchase.jsx`
- Modify: `resources/js/components/Purchase/Suppliers.jsx`
- Modify: `resources/views/backend/orders/pos-invoice.blade.php`
- Modify: `resources/views/backend/orders/print-invoice.blade.php`
- Modify: `resources/views/backend/orders/collection/invoice.blade.php`
- Modify: `database/seeders/RolePermissionSeeder.php`

**Mala/drinks demo data**
- Modify: `database/seeders/StartUpSeeder.php`
- Modify: `database/seeders/UnitSeeder.php`
- Modify: `database/seeders/ProductSeeder.php`
- Modify: `database/seeders/CustomerSeeder.php`
- Modify: `database/seeders/SupplierSeeder.php`
- Modify: `database/seeders/CurrencySeeder.php`

---

## Task 1: Local Install Trial Baseline

**Files:**
- Modify: `.env.example`
- Modify: `.gitignore`
- Verify: `.env`
- Verify: `database/database.sqlite`
- Verify: `.tools/php/php.exe`

- [ ] **Step 1: Confirm local PHP, Composer, npm, and SQLite database exist**

Run:

```powershell
Get-Item .tools\php\php.exe
Get-Item .tools\composer.phar
Get-Item database\database.sqlite
npm.cmd --version
```

Expected:

```text
.tools\php\php.exe exists
.tools\composer.phar exists
database\database.sqlite exists
npm version prints a version number
```

- [ ] **Step 2: Confirm Laravel config uses the local SQLite database**

Run:

```powershell
Select-String -Path .env -Pattern '^APP_URL=|^DB_CONNECTION=|^DB_DATABASE='
```

Expected:

```text
APP_URL=http://127.0.0.1:8000
DB_CONNECTION=sqlite
DB_DATABASE=D:/URY/database/database.sqlite
```

- [ ] **Step 3: Preserve local-only ignores**

Ensure `.gitignore` contains:

```gitignore
.composer-cache/
.composer-home/
.tools/
.qpos-*.log
database/database.sqlite
public/hot
```

- [ ] **Step 4: Add local SQLite note to `.env.example`**

Append this block to `.env.example`:

```dotenv
# Local Windows trial option
# DB_CONNECTION=sqlite
# DB_DATABASE=D:/URY/database/database.sqlite
#
# Production/cloud should use MySQL or MariaDB:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=qpos
# DB_USERNAME=qpos_user
# DB_PASSWORD=secure-password
```

- [ ] **Step 5: Clear caches and run migrations**

Run:

```powershell
.\.tools\php\php.exe artisan config:clear
.\.tools\php\php.exe artisan cache:clear
.\.tools\php\php.exe artisan migrate --force
```

Expected:

```text
Configuration cache cleared successfully.
Application cache cleared successfully.
Nothing to migrate.
```

- [ ] **Step 6: Start backend and frontend servers**

Run backend:

```powershell
Start-Process -WindowStyle Hidden -FilePath ".\.tools\php\php.exe" -ArgumentList "artisan serve --host=127.0.0.1 --port=8000" -RedirectStandardOutput ".qpos-laravel.out.log" -RedirectStandardError ".qpos-laravel.err.log" -WorkingDirectory "D:\URY"
```

Run Vite:

```powershell
Start-Process -WindowStyle Hidden -FilePath "npm.cmd" -ArgumentList "run dev" -RedirectStandardOutput ".qpos-vite.out.log" -RedirectStandardError ".qpos-vite.err.log" -WorkingDirectory "D:\URY"
```

Expected:

```text
http://127.0.0.1:8000/login returns HTTP 200
.qpos-vite.out.log shows a local Vite URL such as http://localhost:5173 or http://localhost:5174
```

- [ ] **Step 7: Commit**

Run:

```powershell
git add .gitignore .env.example app/Http/Controllers/Backend/DashboardController.php
git commit -m "chore: document local qpos trial setup"
```

Expected: commit succeeds after Git user identity is configured.

---

## Task 2: Feature Trial Audit

**Files:**
- Create: `docs/th/feature-audit.md`

- [ ] **Step 1: Create feature audit document**

Create `docs/th/feature-audit.md` with:

```markdown
# บันทึกตรวจฟีเจอร์ QPOS

วันที่ตรวจ: 19 พฤษภาคม 2026
URL ทดสอบ: http://127.0.0.1:8000/login
บัญชีทดสอบ: demo@qtecsolution.net / 87654321

## สรุปฟีเจอร์หลัก

| หมวด | เส้นทาง | สถานะ | หมายเหตุ |
|---|---|---|---|
| เข้าสู่ระบบ | /login | รอตรวจ | ตรวจ login/logout และข้อความ error |
| แดชบอร์ด | /admin | รอตรวจ | ตรวจยอดขาย, สถิติ, กราฟรายเดือน |
| POS | /admin/cart | รอตรวจ | ตรวจค้นสินค้า, เพิ่มบิล, ชำระเงิน |
| สินค้า | /admin/products | รอตรวจ | ตรวจเพิ่ม/แก้ไข/ค้นหาสินค้า |
| หมวดสินค้า | /admin/categories | รอตรวจ | ตรวจเพิ่มหมวดสำหรับหม่าล่าและเครื่องดื่ม |
| หน่วยนับ | /admin/units | รอตรวจ | ตรวจหน่วย ไม้, แก้ว, ชุด |
| ลูกค้า | /admin/customers | รอตรวจ | ตรวจลูกค้าหน้าร้านและลูกค้าประจำ |
| ผู้จำหน่าย | /admin/suppliers | รอตรวจ | ตรวจแหล่งวัตถุดิบ |
| ซื้อสินค้า | /admin/purchase | รอตรวจ | ตรวจรับวัตถุดิบเข้าสต็อก |
| รายการขาย | /admin/orders | รอตรวจ | ตรวจรายการขายและใบเสร็จ |
| รายงานขาย | /admin/sale/report | รอตรวจ | ตรวจค้นตามช่วงวันที่ |
| รายงานสต็อก | /admin/inventory/report | รอตรวจ | ตรวจยอดคงเหลือ |
| ผู้ใช้ | /admin/users | รอตรวจ | ตรวจแอดมิน/แคชเชียร์ |
| สิทธิ์ | /admin/settings/website/roles | รอตรวจ | ตรวจ role และ permission |
| ตั้งค่าใบเสร็จ | /admin/settings/website/general?active-tab=invoice-settings | รอตรวจ | ตรวจชื่อร้าน ที่อยู่ เบอร์โทร หมายเหตุ |

## เกณฑ์ผ่าน

- หน้าเปิดได้โดยไม่เกิด HTTP 500
- ปุ่มหลักกดแล้วทำงาน
- ข้อความสำคัญเป็นภาษาไทยหลังปรับระบบ
- ไม่พบ layout แตกบนหน้าจอ 1366px และมือถือ
```

- [ ] **Step 2: Log in and inspect core routes**

Use browser session or manual browser:

```text
1. Open http://127.0.0.1:8000/login
2. Login with demo@qtecsolution.net / 87654321
3. Open each route listed in docs/th/feature-audit.md
4. Change status from "รอตรวจ" to "ผ่าน", "มีปัญหา", or "ไม่เกี่ยวข้อง"
5. Add one-line notes for each problem
```

- [ ] **Step 3: Commit**

Run:

```powershell
git add docs/th/feature-audit.md
git commit -m "docs: add qpos feature audit checklist"
```

Expected: one docs-only commit.

---

## Task 3: Code Audit Before UI Changes

**Files:**
- Create: `docs/th/code-audit.md`
- Modify if needed: `app/Http/Controllers/Backend/DashboardController.php`

- [ ] **Step 1: Search for high-risk SQL and English UI strings**

Run:

```powershell
rg "DATE_FORMAT|YEAR\\(|MONTH\\(|selectRaw|DB::raw" app database resources
rg "\"[A-Za-z][^\"]{2,}\"|'[A-Za-z][^']{2,}'" resources\views resources\js app\Http\Controllers database\seeders
```

Expected:

```text
DashboardController date aggregation is already SQLite-compatible.
Large list of English strings in Blade, React, controller responses, and seeders.
```

- [ ] **Step 2: Create code audit document**

Create `docs/th/code-audit.md` with:

```markdown
# บันทึกตรวจโค้ดก่อนปรับภาษาไทย

วันที่ตรวจ: 19 พฤษภาคม 2026

## สิ่งที่พบ

1. โปรเจกต์ใช้ Laravel Blade สำหรับหน้าแอดมินและหน้า login
2. หน้า POS และ purchase บางส่วนเป็น React ใน `resources/js/components`
3. สิทธิ์ผู้ใช้ใช้ Spatie Permission ใน `database/seeders/RolePermissionSeeder.php`
4. ใบเสร็จหลักอยู่ที่ `resources/views/backend/orders/pos-invoice.blade.php` และ `print-invoice.blade.php`
5. ข้อมูลตัวอย่างยังเป็น Faker ภาษาอังกฤษ ควรเปลี่ยนเป็นตัวอย่างร้านหม่าล่าและเครื่องดื่ม
6. Local ใช้ SQLite เพื่อทดสอบง่าย ส่วน production/cloud ควรใช้ MySQL หรือ MariaDB

## ความเสี่ยง

| เรื่อง | ความเสี่ยง | วิธีลดความเสี่ยง |
|---|---|---|
| แปลข้อความกระจายหลายไฟล์ | แปลไม่ครบทุกหน้าจอ | ทำเป็นเฟสและ smoke test ทุก route |
| ภาษาไทยยาวกว่าอังกฤษ | ปุ่ม/table แตก | ตรวจหน้าจอ desktop และ mobile |
| Seed data เปลี่ยน | ฐานข้อมูลเดิมไม่เห็นข้อมูลใหม่ | ใช้ migrate:fresh --seed เฉพาะ local |
| สิทธิ์ role เดิมกว้างเกิน | แคชเชียร์เห็นเมนูเกินจำเป็น | ปรับ role cashier ให้เน้น POS และขาย |
| ใบเสร็จภาษาไทย | thermal printer บางรุ่นไม่รองรับ font | ใช้ HTML print ก่อน แล้วทดสอบเครื่องพิมพ์จริงภายหลัง |
```

- [ ] **Step 3: Commit**

Run:

```powershell
git add docs/th/code-audit.md app/Http/Controllers/Backend/DashboardController.php
git commit -m "docs: record qpos code audit"
```

Expected: audit committed.

---

## Task 4: Professional Redesign Concept Gate

**Files:**
- Create: `docs/th/design-system.md`
- Modify later: `resources/css/app.css`
- Modify later: `resources/views/frontend/authentication/login.blade.php`
- Modify later: `resources/views/backend/master.blade.php`
- Modify later: `resources/views/backend/index.blade.php`
- Modify later: `resources/views/backend/cart/index.blade.php`
- Modify later: `resources/js/components/Pos.jsx`

- [ ] **Step 1: Generate visual concept before UI coding**

Use Image Gen with this exact brief:

```text
Create a polished professional Thai-first POS/shop management web app concept for "QPOS ร้านหม่าล่า".
Audience: small mala skewers and drinks shop owner, cashier, and admin.
Surface: one desktop admin dashboard screen and one POS checkout screen in the same design system.
Language: Thai UI text.
Style: modern, clean, practical restaurant operations dashboard, not a marketing landing page.
Layout: left sidebar, compact top bar, clear dashboard summary cards, sales chart, recent orders table, product/stock alerts.
POS screen: product search, barcode input, category filters, product grid for mala skewers and drinks, cart panel, discount, paid, due, checkout button.
Visual tone: professional food retail, warm but not beige-heavy, clear contrast, restrained red/charcoal/white accents, compact data-friendly spacing.
Required Thai labels: แดชบอร์ด, ขายหน้าร้าน, สินค้า, หมวดสินค้า, ลูกค้า, รายงาน, ตั้งค่า, ค้นหาสินค้า, ล้างบิล, ชำระเงิน.
Do not create a landing page. Do not use decorative blobs. Do not overuse purple, dark slate, beige, or orange/brown palettes.
```

Expected: a complete concept image pair or combined concept showing dashboard and POS with readable Thai text.

- [ ] **Step 2: User approval gate**

Ask:

```text
คอนเซปต์ redesign นี้โอเคไหมครับ ถ้าโอเคผมจะใช้เป็น design spec สำหรับปรับ CSS/UI ให้ใกล้ที่สุด
```

Expected: user approves or requests changes.

- [ ] **Step 3: Record approved design system**

Create `docs/th/design-system.md`:

```markdown
# ระบบดีไซน์ QPOS ร้านหม่าล่า

## เป้าหมาย

ทำให้ระบบดูเป็นมืออาชีพ ใช้งานง่ายสำหรับร้านหม่าล่าและเครื่องดื่ม แต่ยังเป็น dashboard/POS ที่อ่านข้อมูลเร็ว

## โทนภาพ

- พื้นหลังหลัก: ขาวหรือเทาอ่อน
- พื้นที่นำทาง: เข้ม อ่านง่าย
- สีหลัก: แดงหม่าล่าแบบสุภาพ
- สีรอง: เขียวสำหรับสถานะสำเร็จ, เหลืองสำหรับเตือน, แดงสำหรับลบ/ยกเลิก
- ขอบ: บางและชัด
- เงา: เบา ใช้เฉพาะ panel สำคัญ

## Typography

- ใช้ font stack: `system-ui, "Noto Sans Thai", "Segoe UI", sans-serif`
- หัวข้อหน้า: หนา อ่านง่าย
- ปุ่มและเมนู: ขนาดกะทัดรัด ไม่ล้น
- ตาราง: ให้ความสำคัญกับความอ่านง่ายมากกว่าตกแต่ง

## Component Rules

- Sidebar ต้องอ่านชื่อเมนูไทยได้ครบ
- ปุ่มหลักใช้สีเดียวกันทั้งระบบ
- ปุ่มลบ/ยกเลิกต้องแยกสีชัดเจน
- Card ใช้กับข้อมูลซ้ำหรือ summary เท่านั้น
- POS ต้องเน้นความเร็ว: ค้นหา, เพิ่มสินค้า, ชำระเงิน เห็นชัดในหน้าเดียว
```

- [ ] **Step 4: Commit**

Run:

```powershell
git add docs/th/design-system.md
git commit -m "docs: add thai qpos design system"
```

Expected: design-system docs committed after approval.

---

## Task 5: Thai Manuals

**Files:**
- Create: `docs/th/quick-start.md`
- Create: `docs/th/user-manual.md`
- Create: `docs/th/admin-manual.md`
- Modify: `README.md`

- [ ] **Step 1: Create quick start manual**

Create `docs/th/quick-start.md` with:

```markdown
# คู่มือเริ่มใช้งานเร็ว QPOS ร้านหม่าล่า

## 1. เปิดระบบบนเครื่อง local

1. เปิด Terminal ที่โฟลเดอร์ `D:\URY`
2. รัน Laravel backend

```powershell
.\.tools\php\php.exe artisan serve --host=127.0.0.1 --port=8000
```

3. เปิดอีก Terminal แล้วรัน Vite

```powershell
npm.cmd run dev
```

4. เปิดเว็บ

```text
http://127.0.0.1:8000/login
```

## 2. บัญชีทดลอง

- อีเมล: `demo@qtecsolution.net`
- รหัสผ่าน: `87654321`

## 3. ลำดับทดลองขายหน้าร้าน

1. เข้าสู่ระบบ
2. ไปที่เมนู `ขายหน้าร้าน`
3. เลือกลูกค้า `ลูกค้าหน้าร้าน`
4. ค้นหาสินค้า เช่น `หมูสามชั้นหม่าล่า` หรือ `ชานมเย็น`
5. กดสินค้าเพื่อเพิ่มเข้าบิล
6. ใส่ยอดรับเงิน
7. กด `ชำระเงิน`
8. ตรวจใบเสร็จ

## 4. เมื่อต้องเพิ่มสินค้าใหม่

1. ไปที่ `สินค้า > เพิ่มสินค้า`
2. เลือกหมวด เช่น `หม่าล่าเสียบไม้`
3. เลือกหน่วย เช่น `ไม้`
4. กำหนดราคาขายและจำนวนสต็อก
5. บันทึก
```

- [ ] **Step 2: Create user manual**

Create `docs/th/user-manual.md` with sections:

```markdown
# คู่มือผู้ใช้งาน QPOS ร้านหม่าล่า

## สำหรับใคร

คู่มือนี้สำหรับแคชเชียร์และพนักงานหน้าร้านที่ต้องขายสินค้า รับเงิน และพิมพ์ใบเสร็จ

## เข้าสู่ระบบ

1. เปิด `http://127.0.0.1:8000/login`
2. ใส่อีเมลและรหัสผ่านที่แอดมินให้
3. กด `เข้าสู่ระบบ`

## ขายหน้าร้าน

1. ไปที่เมนู `ขายหน้าร้าน`
2. เลือกลูกค้า ถ้าเป็นลูกค้าทั่วไปให้ใช้ `ลูกค้าหน้าร้าน`
3. ค้นหาสินค้าด้วยชื่อสินค้า หรือสแกนบาร์โค้ดถ้ามี
4. กดสินค้าเพื่อเพิ่มเข้าบิล
5. ตรวจจำนวนและยอดรวมในตะกร้า
6. ใส่ส่วนลดถ้ามี
7. ใส่ยอดที่ลูกค้าชำระ
8. กด `ชำระเงิน`
9. พิมพ์ใบเสร็จหรือบันทึกเลขที่บิล

## ตัวอย่างการขาย

ลูกค้าซื้อ:

- หมูสามชั้นหม่าล่า 5 ไม้
- ไส้กรอกชีส 2 ไม้
- ชามะนาว 1 แก้ว

ให้ค้นหาสินค้าและกดเพิ่มทีละรายการ จากนั้นตรวจยอดก่อนชำระเงิน

## การแก้ปัญหาหน้างาน

| ปัญหา | วิธีแก้ |
|---|---|
| หาสินค้าไม่เจอ | ตรวจชื่อสินค้า หรือแจ้งแอดมินให้เพิ่มสินค้า |
| กดชำระเงินไม่ได้ | ตรวจว่าเลือกผู้ซื้อและมีสินค้าในบิลแล้ว |
| ยอดเงินผิด | ล้างบิลแล้วทำรายการใหม่ ถ้ายังไม่ชำระเงิน |
| ใบเสร็จไม่พิมพ์ | ใช้ปุ่มพิมพ์ซ้ำ และตรวจเครื่องพิมพ์ |
```

- [ ] **Step 3: Create admin manual**

Create `docs/th/admin-manual.md` with sections:

```markdown
# คู่มือแอดมิน QPOS ร้านหม่าล่า

## หน้าที่ของแอดมิน

- ตั้งค่าร้าน
- เพิ่มหมวดสินค้า
- เพิ่มสินค้าและราคาขาย
- จัดการสต็อก
- จัดการผู้ใช้และสิทธิ์
- ตรวจรายงานยอดขาย
- เตรียมระบบก่อนขึ้น cloud

## ตั้งค่าร้าน

1. ไปที่ `ตั้งค่า > ตั้งค่าทั่วไป`
2. ใส่ชื่อร้าน เช่น `QPOS ร้านหม่าล่า`
3. ใส่ที่อยู่ เบอร์โทร และอีเมล
4. ตั้งค่าข้อความท้ายใบเสร็จ เช่น `ขอบคุณที่อุดหนุน`

## หมวดสินค้าแนะนำ

- หม่าล่าเสียบไม้
- ชุดหม่าล่า
- เครื่องดื่มเย็น
- เครื่องดื่มปั่น
- ท็อปปิ้ง
- ซอส/น้ำจิ้ม
- ของทานเล่น

## หน่วยนับแนะนำ

- ไม้
- แก้ว
- ขวด
- ชุด
- ถุง
- กรัม

## สิทธิ์ผู้ใช้แนะนำ

| บทบาท | ใช้ทำอะไร | สิทธิ์หลัก |
|---|---|---|
| Admin | เจ้าของร้าน/ผู้จัดการ | ทุกเมนู |
| Cashier | แคชเชียร์ | ขายหน้าร้าน, ดูสินค้า, ดูลูกค้า |
| Sales Associate | พนักงานขาย | ขายหน้าร้าน, ดูรายการขาย |

## การเตรียมขึ้น cloud

1. เปลี่ยนฐานข้อมูลจาก SQLite เป็น MySQL หรือ MariaDB
2. ตั้งค่า `APP_URL` เป็น domain จริง
3. เปิด HTTPS
4. ตั้งค่า backup database
5. ทดสอบ login, POS, ใบเสร็จ, รายงาน และสิทธิ์ก่อนใช้งานจริง
```

- [ ] **Step 4: Add Thai documentation links to README**

Add near the top of `README.md`:

```markdown
## Thai QPOS Local Guide

- [คู่มือเริ่มใช้งานเร็ว](docs/th/quick-start.md)
- [คู่มือผู้ใช้งาน](docs/th/user-manual.md)
- [คู่มือแอดมิน](docs/th/admin-manual.md)
- [บันทึกตรวจฟีเจอร์](docs/th/feature-audit.md)
- [บันทึกตรวจโค้ด](docs/th/code-audit.md)
```

- [ ] **Step 5: Commit**

Run:

```powershell
git add README.md docs/th/quick-start.md docs/th/user-manual.md docs/th/admin-manual.md
git commit -m "docs: add thai qpos manuals"
```

Expected: Thai manuals committed.

---

## Task 6: Mala and Drinks Demo Data

**Files:**
- Modify: `database/seeders/StartUpSeeder.php`
- Modify: `database/seeders/UnitSeeder.php`
- Modify: `database/seeders/ProductSeeder.php`
- Modify: `database/seeders/CustomerSeeder.php`
- Modify: `database/seeders/SupplierSeeder.php`
- Modify: `database/seeders/CurrencySeeder.php`

- [ ] **Step 1: Update startup demo users and first customer/supplier**

In `database/seeders/StartUpSeeder.php`, use these values:

```php
$user = User::create([
    'name' => 'ผู้ดูแลร้าน',
    'email' => 'demo@qtecsolution.net',
    'password' => bcrypt(87654321),
    'username' => uniqid()
]);

Customer::create([
    'name' => 'ลูกค้าหน้าร้าน',
    'phone' => '0800000000',
]);

Supplier::create([
    'name' => 'ร้านวัตถุดิบหม่าล่า',
    'phone' => '0810000000',
]);
```

- [ ] **Step 2: Update units**

Replace the `$units` array in `database/seeders/UnitSeeder.php` with:

```php
$units = [
    ['title' => 'ไม้', 'short_name' => 'ไม้', 'created_at' => now(), 'updated_at' => now()],
    ['title' => 'แก้ว', 'short_name' => 'แก้ว', 'created_at' => now(), 'updated_at' => now()],
    ['title' => 'ขวด', 'short_name' => 'ขวด', 'created_at' => now(), 'updated_at' => now()],
    ['title' => 'ชุด', 'short_name' => 'ชุด', 'created_at' => now(), 'updated_at' => now()],
    ['title' => 'ถุง', 'short_name' => 'ถุง', 'created_at' => now(), 'updated_at' => now()],
    ['title' => 'กรัม', 'short_name' => 'กรัม', 'created_at' => now(), 'updated_at' => now()],
];
```

- [ ] **Step 3: Replace random product seeding with fixed mala/drinks examples**

In `database/seeders/ProductSeeder.php`, create categories:

```php
$categoryNames = [
    'หม่าล่าเสียบไม้',
    'ชุดหม่าล่า',
    'เครื่องดื่มเย็น',
    'เครื่องดื่มปั่น',
    'ท็อปปิ้ง',
    'ซอส/น้ำจิ้ม',
    'ของทานเล่น',
];
```

Create brands:

```php
$brandNames = [
    'ครัวกลาง',
    'หน้าร้าน',
    'เครื่องดื่มสด',
    'วัตถุดิบประจำร้าน',
];
```

Create products:

```php
$products = [
    ['name' => 'หมูสามชั้นหม่าล่า', 'sku' => 'MALA-PORK-BELLY', 'category' => 'หม่าล่าเสียบไม้', 'unit' => 'ไม้', 'price' => 15, 'purchase_price' => 8, 'quantity' => 100],
    ['name' => 'เนื้อวัวหม่าล่า', 'sku' => 'MALA-BEEF', 'category' => 'หม่าล่าเสียบไม้', 'unit' => 'ไม้', 'price' => 20, 'purchase_price' => 12, 'quantity' => 80],
    ['name' => 'ไส้กรอกชีส', 'sku' => 'MALA-CHEESE-SAUSAGE', 'category' => 'หม่าล่าเสียบไม้', 'unit' => 'ไม้', 'price' => 15, 'purchase_price' => 7, 'quantity' => 120],
    ['name' => 'เห็ดเข็มทอง', 'sku' => 'MALA-ENOKI', 'category' => 'หม่าล่าเสียบไม้', 'unit' => 'ไม้', 'price' => 12, 'purchase_price' => 5, 'quantity' => 100],
    ['name' => 'เต้าหู้ปลา', 'sku' => 'MALA-FISH-TOFU', 'category' => 'หม่าล่าเสียบไม้', 'unit' => 'ไม้', 'price' => 15, 'purchase_price' => 7, 'quantity' => 100],
    ['name' => 'ชุดหม่าล่าเล็ก', 'sku' => 'SET-MALA-S', 'category' => 'ชุดหม่าล่า', 'unit' => 'ชุด', 'price' => 99, 'purchase_price' => 55, 'quantity' => 30],
    ['name' => 'ชุดหม่าล่ากลาง', 'sku' => 'SET-MALA-M', 'category' => 'ชุดหม่าล่า', 'unit' => 'ชุด', 'price' => 159, 'purchase_price' => 90, 'quantity' => 25],
    ['name' => 'ชุดหม่าล่าใหญ่', 'sku' => 'SET-MALA-L', 'category' => 'ชุดหม่าล่า', 'unit' => 'ชุด', 'price' => 239, 'purchase_price' => 140, 'quantity' => 20],
    ['name' => 'ชานมเย็น', 'sku' => 'DRINK-THAI-MILK-TEA', 'category' => 'เครื่องดื่มเย็น', 'unit' => 'แก้ว', 'price' => 35, 'purchase_price' => 15, 'quantity' => 60],
    ['name' => 'ชามะนาว', 'sku' => 'DRINK-LEMON-TEA', 'category' => 'เครื่องดื่มเย็น', 'unit' => 'แก้ว', 'price' => 30, 'purchase_price' => 12, 'quantity' => 60],
    ['name' => 'น้ำลำไย', 'sku' => 'DRINK-LONGAN', 'category' => 'เครื่องดื่มเย็น', 'unit' => 'แก้ว', 'price' => 30, 'purchase_price' => 12, 'quantity' => 50],
    ['name' => 'น้ำเปล่า', 'sku' => 'DRINK-WATER', 'category' => 'เครื่องดื่มเย็น', 'unit' => 'ขวด', 'price' => 10, 'purchase_price' => 5, 'quantity' => 100],
];
```

Use `Str::slug($sku)` or `Str::slug($name)` with fallback slug based on SKU so Thai names do not produce empty slugs.

- [ ] **Step 4: Update customer and supplier seeders with fixed Thai examples**

Use customers:

```php
$customers = [
    ['name' => 'ลูกค้าหน้าร้าน', 'phone' => '0800000000', 'address' => 'ซื้อหน้าร้าน'],
    ['name' => 'ลูกค้าประจำ', 'phone' => '0801111111', 'address' => 'พื้นที่ใกล้ร้าน'],
    ['name' => 'ออเดอร์เดลิเวอรี', 'phone' => '0802222222', 'address' => 'ช่องทางเดลิเวอรี'],
];
```

Use suppliers:

```php
$suppliers = [
    ['name' => 'ร้านวัตถุดิบสด', 'phone' => '0811111111', 'address' => 'ตลาดสด'],
    ['name' => 'ร้านเครื่องดื่มและบรรจุภัณฑ์', 'phone' => '0812222222', 'address' => 'โซนค้าส่ง'],
    ['name' => 'ร้านซอสและเครื่องปรุง', 'phone' => '0813333333', 'address' => 'แหล่งวัตถุดิบประจำ'],
];
```

- [ ] **Step 5: Set Thai currency defaults**

In `database/seeders/CurrencySeeder.php`, ensure Thai baht exists and can be default:

```php
[
    'name' => 'Thai Baht',
    'code' => 'THB',
    'symbol' => '฿',
    'position' => 'left',
    'is_default' => true,
]
```

- [ ] **Step 6: Reseed local database**

Run:

```powershell
.\.tools\php\php.exe artisan migrate:fresh --seed --force
```

Expected:

```text
Database seeding completed successfully.
```

- [ ] **Step 7: Commit**

Run:

```powershell
git add database/seeders/StartUpSeeder.php database/seeders/UnitSeeder.php database/seeders/ProductSeeder.php database/seeders/CustomerSeeder.php database/seeders/SupplierSeeder.php database/seeders/CurrencySeeder.php
git commit -m "feat: seed mala drink shop demo data"
```

Expected: seed data committed.

---

## Task 7: Thai Authentication, Navigation, and Dashboard

**Files:**
- Modify: `resources/views/frontend/authentication/login.blade.php`
- Modify: `resources/views/frontend/authentication/forget-password.blade.php`
- Modify: `resources/views/frontend/authentication/login-otp.blade.php`
- Modify: `resources/views/frontend/authentication/new-password.blade.php`
- Modify: `resources/views/frontend/authentication/reset.blade.php`
- Modify: `resources/views/frontend/authentication/sign-up.blade.php`
- Modify: `resources/views/backend/layouts/sidebar.blade.php`
- Modify: `resources/views/backend/layouts/navbar.blade.php`
- Modify: `resources/views/backend/index.blade.php`
- Modify: `resources/views/backend/master.blade.php`
- Modify: `resources/css/app.css`

- [ ] **Step 1: Translate login page**

In `resources/views/frontend/authentication/login.blade.php`, replace visible text:

```text
Login | -> เข้าสู่ระบบ |
Sign in -> เข้าสู่ระบบ
Welcome back! Sign in to access your account. -> ยินดีต้อนรับเข้าสู่ระบบจัดการร้าน
Email -> อีเมล
Enter email -> ใส่อีเมล
Please enter a valid email address. -> กรุณาใส่อีเมลให้ถูกต้อง
Password -> รหัสผ่าน
Enter password -> ใส่รหัสผ่าน
Please enter a password. -> กรุณาใส่รหัสผ่าน
Remember me -> จดจำการเข้าสู่ระบบ
Forgot password -> ลืมรหัสผ่าน
Sign In -> เข้าสู่ระบบ
User: -> ผู้ใช้:
Password: -> รหัสผ่าน:
Don’t have an account? -> ยังไม่มีบัญชี?
Sign up -> สมัครใช้งาน
```

- [ ] **Step 2: Translate sidebar navigation**

In `resources/views/backend/layouts/sidebar.blade.php`, replace visible menu labels:

```text
Dashboard -> แดชบอร์ด
POS -> ขายหน้าร้าน
People -> ลูกค้าและผู้จำหน่าย
Customer -> ลูกค้า
Supplier -> ผู้จำหน่าย
Product -> สินค้า
Product List -> รายการสินค้า
Product Create -> เพิ่มสินค้า
Product Import -> นำเข้าสินค้า
Brand -> แบรนด์/แหล่งสินค้า
Category -> หมวดสินค้า
Unit -> หน่วยนับ
Sale -> การขาย
Sale List -> รายการขาย
Purchase -> การซื้อ
Purchase List -> รายการซื้อ
Purchase Create -> เพิ่มรายการซื้อ
Reports -> รายงาน
Sales Summary -> สรุปยอดขาย
Sales -> รายงานขาย
Inventory -> รายงานสต็อก
SETTINGS -> ตั้งค่า
Website Settings -> ตั้งค่าระบบ
General Settings -> ตั้งค่าทั่วไป
Currency -> สกุลเงิน
Roles & Permissions -> บทบาทและสิทธิ์
Roles -> บทบาท
Permissions -> สิทธิ์
User Management -> จัดการผู้ใช้
```

- [ ] **Step 3: Add Thai professional UI polish**

In `resources/css/app.css`, add:

```css
:root {
    --qpos-primary: #b91c1c;
    --qpos-primary-dark: #7f1d1d;
    --qpos-ink: #111827;
    --qpos-muted: #6b7280;
    --qpos-border: #e5e7eb;
    --qpos-panel: #ffffff;
    --qpos-soft: #f8fafc;
}

body {
    font-family: system-ui, "Noto Sans Thai", "Segoe UI", sans-serif;
    color: var(--qpos-ink);
}

.brand-text,
.nav-sidebar .nav-link p,
.content-header h1,
.card-title,
.btn,
.form-control,
.table {
    letter-spacing: 0;
}

.main-sidebar {
    background: #111827;
}

.nav-sidebar .nav-link.active {
    background: var(--qpos-primary) !important;
    color: #fff !important;
}

.btn-primary,
.bg-gradient-primary {
    background: var(--qpos-primary) !important;
    border-color: var(--qpos-primary) !important;
}

.card {
    border: 1px solid var(--qpos-border);
    border-radius: 8px;
    box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
}

.table th {
    color: var(--qpos-muted);
    font-size: 0.85rem;
    white-space: nowrap;
}
```

- [ ] **Step 4: Translate dashboard**

In `resources/views/backend/index.blade.php`, replace summary titles and headings with:

```text
ยอดขายวันนี้
ยอดขายเดือนนี้
จำนวนสินค้า
จำนวนลูกค้า
ยอดขายรายเดือน
รายการขายล่าสุด
สินค้าใกล้หมด
```

- [ ] **Step 5: Build assets**

Run:

```powershell
npm.cmd run build
```

Expected:

```text
✓ built
```

- [ ] **Step 6: Commit**

Run:

```powershell
git add resources/views/frontend/authentication resources/views/backend/layouts resources/views/backend/index.blade.php resources/views/backend/master.blade.php resources/css/app.css
git commit -m "feat: localize auth navigation and dashboard"
```

Expected: UI translation commit.

---

## Task 8: Thai Master Data, Reports, and Settings

**Files:**
- Modify all master data, reports, user, role, and settings Blade files listed in File Structure.

- [ ] **Step 1: Apply shared Thai action vocabulary**

Use these exact translations across Blade files:

```text
Create -> เพิ่ม
Add -> เพิ่ม
Edit -> แก้ไข
Update -> อัปเดต
Delete -> ลบ
Save -> บันทึก
Cancel -> ยกเลิก
Back -> กลับ
Search -> ค้นหา
Filter -> กรองข้อมูล
Export -> ส่งออก
Import -> นำเข้า
Print -> พิมพ์
Submit -> ยืนยัน
Name -> ชื่อ
Phone -> เบอร์โทร
Address -> ที่อยู่
Status -> สถานะ
Active -> เปิดใช้งาน
Inactive -> ปิดใช้งาน
Action -> จัดการ
Description -> รายละเอียด
Price -> ราคา
Quantity -> จำนวน
Stock -> สต็อก
Discount -> ส่วนลด
Total -> รวม
Paid -> ชำระแล้ว
Due -> ค้างชำระ
Date -> วันที่
```

- [ ] **Step 2: Apply domain terms**

Use:

```text
Product -> สินค้า
Products -> สินค้า
Category -> หมวดสินค้า
Brand -> แบรนด์/แหล่งสินค้า
Unit -> หน่วยนับ
Customer -> ลูกค้า
Supplier -> ผู้จำหน่าย
Purchase -> ซื้อสินค้า
Sale -> ขายสินค้า
Order -> บิลขาย
Report -> รายงาน
Currency -> สกุลเงิน
Role -> บทบาท
Permission -> สิทธิ์
User -> ผู้ใช้
Website -> ระบบร้าน
Invoice -> ใบเสร็จ/ใบกำกับ
```

- [ ] **Step 3: Use mala/drinks placeholders in forms**

Apply these placeholders where matching fields exist:

```text
Product name -> เช่น หมูสามชั้นหม่าล่า
Category name -> เช่น หม่าล่าเสียบไม้
Unit name -> เช่น ไม้, แก้ว, ชุด
Brand name -> เช่น ครัวกลาง
Customer name -> เช่น ลูกค้าหน้าร้าน
Supplier name -> เช่น ร้านวัตถุดิบสด
Search -> ค้นหาสินค้า ชื่อลูกค้า หรือเลขที่บิล
Description -> รายละเอียดสินค้า เช่น ระดับความเผ็ดหรือส่วนประกอบ
```

- [ ] **Step 4: Build and manually inspect key pages**

Run:

```powershell
npm.cmd run build
```

Open:

```text
http://127.0.0.1:8000/admin/products
http://127.0.0.1:8000/admin/categories
http://127.0.0.1:8000/admin/units
http://127.0.0.1:8000/admin/customers
http://127.0.0.1:8000/admin/suppliers
http://127.0.0.1:8000/admin/purchase
http://127.0.0.1:8000/admin/orders
http://127.0.0.1:8000/admin/sale/report
http://127.0.0.1:8000/admin/settings/website/general
```

Expected: pages render in Thai without HTTP 500 and without obvious overflow in table headers.

- [ ] **Step 5: Commit**

Run:

```powershell
git add resources/views/backend
git commit -m "feat: localize qpos management screens"
```

Expected: management screen translation commit.

---

## Task 9: POS, Receipts, and Permissions

**Files:**
- Modify: `resources/views/backend/cart/index.blade.php`
- Modify: `resources/js/components/Pos.jsx`
- Modify: `resources/js/components/Cart.jsx`
- Modify: `resources/js/components/CutomerSelect.jsx`
- Modify: `resources/js/components/Purchase/Purchase.jsx`
- Modify: `resources/js/components/Purchase/Suppliers.jsx`
- Modify: `resources/views/backend/orders/pos-invoice.blade.php`
- Modify: `resources/views/backend/orders/print-invoice.blade.php`
- Modify: `resources/views/backend/orders/collection/invoice.blade.php`
- Modify: `database/seeders/RolePermissionSeeder.php`

- [ ] **Step 1: Translate POS React visible strings**

In `resources/js/components/Pos.jsx`, replace:

```text
Are you sure you want to delete Cart? -> ต้องการล้างบิลนี้หรือไม่?
Yes -> ใช่
No -> ไม่ใช่
Please select customer -> กรุณาเลือกลูกค้า
Are you sure you want to complete this order? -> ต้องการชำระเงินบิลนี้หรือไม่?
Due: -> เงินทอน/ค้างชำระ:
Sub Total: -> ยอดก่อนส่วนลด:
Discount: -> ส่วนลด:
Enter discount -> ใส่ส่วนลด
Apply Fractional Discount: -> ปัดเศษเป็นส่วนลด:
Total: -> ยอดสุทธิ:
Paid: -> รับเงิน:
Enter paid -> ใส่ยอดรับเงิน
Due: -> เงินทอน/ค้างชำระ:
Clear Cart -> ล้างบิล
Checkout -> ชำระเงิน
Enter Product Barcode -> สแกน/ใส่บาร์โค้ด
Enter Product Name -> ค้นหาสินค้า เช่น หมูสามชั้นหม่าล่า
Price: -> ราคา:
Loading more... -> กำลังโหลดสินค้า...
```

- [ ] **Step 2: Translate cart and customer selector**

In `resources/js/components/Cart.jsx` and `resources/js/components/CutomerSelect.jsx`, use:

```text
Cart -> รายการในบิล
Product -> สินค้า
Qty -> จำนวน
Price -> ราคา
Total -> รวม
Select Customer -> เลือกลูกค้า
Create Customer -> เพิ่มลูกค้า
Walking Customer -> ลูกค้าหน้าร้าน
```

- [ ] **Step 3: Translate receipt templates**

In `resources/views/backend/orders/pos-invoice.blade.php`, use:

```text
Receipt_ -> ใบเสร็จ_
User: -> ผู้ขาย:
Order: # -> เลขที่บิล #
Name: -> ชื่อลูกค้า:
Address: -> ที่อยู่:
Phone: -> เบอร์โทร:
Product -> สินค้า
Total -> รวม
Subtotal: -> ยอดก่อนส่วนลด:
Discount: -> ส่วนลด:
Paid: -> รับเงิน:
Due: -> ค้างชำระ/เงินทอน:
Print -> พิมพ์ใบเสร็จ
```

In `resources/views/backend/orders/print-invoice.blade.php`, use:

```text
Invoice -> ใบเสร็จ
Date: -> วันที่:
To -> ลูกค้า
From -> ร้านค้า
Info -> ข้อมูลบิล
Sale ID -> เลขที่บิล
Sale Date -> วันที่ขาย
SN -> ลำดับ
Product -> สินค้า
Quantity -> จำนวน
Price -> ราคา
Subtotal -> ยอดก่อนส่วนลด
Discount -> ส่วนลด
Total -> ยอดสุทธิ
Paid -> รับเงิน
Due -> ค้างชำระ/เงินทอน
Print -> พิมพ์ใบเสร็จ
```

- [ ] **Step 4: Tighten cashier permissions**

In `database/seeders/RolePermissionSeeder.php`, set cashier permissions to:

```php
$cashierPermissions = [
    'dashboard_view',
    'sale_create',
    'sale_view',
    'customer_create',
    'customer_view',
    'product_view',
];
```

Set sales associate permissions to:

```php
$salesPermissions = [
    'dashboard_view',
    'sale_create',
    'sale_view',
    'customer_view',
    'product_view',
];
```

- [ ] **Step 5: Build and reseed local roles if needed**

Run:

```powershell
npm.cmd run build
.\.tools\php\php.exe artisan migrate:fresh --seed --force
```

Expected: build succeeds and seed completes.

- [ ] **Step 6: Commit**

Run:

```powershell
git add resources/views/backend/cart/index.blade.php resources/js/components resources/views/backend/orders database/seeders/RolePermissionSeeder.php
git commit -m "feat: localize pos receipts and role permissions"
```

Expected: POS, receipt, and permission commit.

---

## Task 10: Simulated Shop Test

**Files:**
- Modify: `docs/th/feature-audit.md`

- [ ] **Step 1: Reset local test data**

Run:

```powershell
.\.tools\php\php.exe artisan migrate:fresh --seed --force
```

Expected: Thai mala/drinks demo products exist after login.

- [ ] **Step 2: Test admin setup path**

Manual route sequence:

```text
1. Login as demo@qtecsolution.net / 87654321
2. Open /admin/categories and confirm Thai categories exist
3. Open /admin/units and confirm ไม้, แก้ว, ขวด, ชุด exist
4. Open /admin/products and confirm mala/drinks products exist
5. Open /admin/customers and confirm ลูกค้าหน้าร้าน exists
6. Open /admin/suppliers and confirm Thai suppliers exist
```

Expected: all pages render, records visible, no HTTP 500.

- [ ] **Step 3: Test POS sale path**

Manual flow:

```text
1. Open /admin/cart
2. Select ลูกค้าหน้าร้าน
3. Search หมูสามชั้น
4. Add หมูสามชั้นหม่าล่า
5. Search ชามะนาว
6. Add ชามะนาว
7. Enter paid amount equal to total
8. Click ชำระเงิน
9. Confirm SweetAlert
10. Verify receipt page opens
```

Expected: order created and receipt page opens in Thai.

- [ ] **Step 4: Test cashier role**

Manual flow:

```text
1. Login as cashier@gmail.com / 12345678
2. Confirm cashier can open /admin/cart
3. Confirm cashier can view products and customers if allowed by sidebar
4. Confirm cashier cannot access role/settings pages
```

Expected: cashier role is limited to daily sales tasks.

- [ ] **Step 5: Update feature audit statuses**

In `docs/th/feature-audit.md`, change rows from `รอตรวจ` to the observed result:

```text
ผ่าน
มีปัญหา
ไม่เกี่ยวข้อง
```

- [ ] **Step 6: Commit**

Run:

```powershell
git add docs/th/feature-audit.md
git commit -m "test: record simulated shop workflow results"
```

Expected: tested workflow documented.

---

## Task 11: Production/Cloud Readiness Notes

**Files:**
- Modify: `docs/th/admin-manual.md`
- Create: `docs/th/cloud-readiness.md`

- [ ] **Step 1: Create cloud readiness document**

Create `docs/th/cloud-readiness.md`:

```markdown
# แนวทางเตรียมขึ้น Cloud

## สถานะ local

ระบบ local ใช้ SQLite เพื่อให้ทดลองบนเครื่องได้ง่าย เหมาะสำหรับตรวจฟีเจอร์และฝึกใช้งาน

## Production แนะนำ

- PHP 8.2+
- Composer
- Node.js 20+
- MySQL หรือ MariaDB
- HTTPS domain
- Web server เช่น Nginx หรือ Apache
- Scheduled backup database

## Environment ที่ต้องเปลี่ยน

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com
DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_PORT=3306
DB_DATABASE=qpos
DB_USERNAME=qpos_user
DB_PASSWORD=secure-password
```

## Checklist ก่อนใช้จริง

1. Login ได้
2. เพิ่มสินค้าได้
3. ขายผ่าน POS ได้
4. พิมพ์ใบเสร็จได้
5. รายงานขายถูกต้อง
6. สิทธิ์แคชเชียร์ถูกจำกัด
7. Backup database ทำงาน
8. HTTPS เปิดใช้งาน
```

- [ ] **Step 2: Link cloud readiness from admin manual**

Append to `docs/th/admin-manual.md`:

```markdown
## อ่านต่อ

- [แนวทางเตรียมขึ้น Cloud](cloud-readiness.md)
```

- [ ] **Step 3: Commit**

Run:

```powershell
git add docs/th/admin-manual.md docs/th/cloud-readiness.md
git commit -m "docs: add cloud readiness guide"
```

Expected: cloud readiness committed.

---

## Task 12: Final Build and Browser Verification

**Files:**
- Verify only unless defects are found.

- [ ] **Step 1: Run Laravel and Vite build checks**

Run:

```powershell
.\.tools\php\php.exe artisan test
npm.cmd run build
```

Expected:

```text
PHP tests pass or there are no project tests.
Vite build succeeds.
```

- [ ] **Step 2: Clear Laravel cache**

Run:

```powershell
.\.tools\php\php.exe artisan optimize:clear
```

Expected:

```text
Cached events cleared successfully.
Compiled views cleared successfully.
Application cache cleared successfully.
Route cache cleared successfully.
Configuration cache cleared successfully.
Compiled services and packages files removed successfully.
Caches cleared successfully.
```

- [ ] **Step 3: Browser verification**

Open:

```text
http://127.0.0.1:8000/login
http://127.0.0.1:8000/admin
http://127.0.0.1:8000/admin/cart
http://127.0.0.1:8000/admin/products
http://127.0.0.1:8000/admin/orders
```

Expected:

```text
All pages load.
Thai text is visible.
Sidebar is readable.
POS sale can be completed.
Receipt is readable in Thai.
```

- [ ] **Step 4: Responsive check**

Check at:

```text
Desktop: 1366 x 768
Mobile: 390 x 844
```

Expected:

```text
No important text overlaps.
Buttons remain tappable.
POS cart and product grid remain usable.
```

- [ ] **Step 5: Final commit**

Run:

```powershell
git status --short
git add .
git commit -m "feat: prepare thai qpos for mala drink shop"
```

Expected: all intended changes committed, generated/vendor/local files remain ignored.

---

## Self Review

Spec coverage:
- Local install and trial path: Task 1.
- Feature inspection: Task 2.
- Code inspection: Task 3.
- Professional redesign with appropriate skill: Task 4 and Task 7.
- Thai UI and placeholders: Task 7, Task 8, Task 9.
- Mala/drinks mock data with future category expansion: Task 6.
- Thai receipts: Task 9.
- Permissions: Task 9 and Task 10.
- Simulated usage test: Task 10 and Task 12.
- Cloud deployment readiness: Task 11.
- Thai user/admin manuals: Task 5 and Task 11.

No placeholder terms are left as implementation gaps. The plan contains concrete files, commands, expected outcomes, and exact text replacements or content blocks.

