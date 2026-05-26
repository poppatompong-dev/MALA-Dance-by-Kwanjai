# CLAUDE.md — Guide for AI Agents on MALA Dance by Kwanjai

เอกสารนี้สำหรับ Claude หรือ AI agent ที่จะทำงานบน codebase นี้ อ่านก่อนเริ่ม task ใหม่เพื่อเข้าใจบริบทและกฎสำคัญ

## Quick Context

**ระบบ**: POS + Management สำหรับร้านหม่าล่า/เครื่องดื่ม
**Stack**: Laravel 10 + Blade/AdminLTE + React 18 + Vite 4
**DB**: Supabase Postgres (Transaction Pooler) — production only
**Deploy**: Vercel (`vercel-php@0.7.4`, region `sin1`)
**Production URL**: https://mala-dance-by-kwanjai.vercel.app
**Branch**: local `master` ↔ remote `main` — push ด้วย `git push origin HEAD:main`

## Default Accounts

หลังจาก seed:

- **Admin**: `admin` / `admin`
- **เจ้าของร้าน**: `kwan` / `kwan`

ระบบรองรับ login ด้วย **ชื่อผู้ใช้** หรือ **อีเมล** (ดู `AuthController::login` — ใช้ `filter_var` แยก email vs username)

## Where to Find Things

| ต้องหา | ที่ |
|---|---|
| POS frontend (React) | `resources/js/components/Pos.jsx`, `Cart.jsx`, `CutomerSelect.jsx` |
| Backend controllers | `app/Http/Controllers/Backend/**` |
| Inventory / Stock logic | `app/Services/InventoryService.php` (immutable ledger pattern) |
| Order checkout & void | `app/Http/Controllers/Backend/Pos/OrderController.php` |
| Sales channels (delivery platforms) | `app/Http/Controllers/Backend/SalesChannelController.php`, `app/Models/SalesChannel.php` |
| Platform sales report | `ReportController::platformSalesReport`, `resources/views/backend/reports/platform-sales.blade.php` |
| Routes | `routes/web.php` (single file, all backend under `/admin` prefix) |
| Database schema | `database/migrations/` (รวม composite indexes) |
| Seeders | `database/seeders/` — `StartUpSeeder` (admin+kwan users), `RolePermissionSeeder`, `ThaiShopSeeder` |
| Manuals (Thai) | `docs/th/*.md` — แสดงผ่าน `/admin/manuals` (allowlist) |
| Vercel config | `vercel.json` |
| Vite config | `vite.config.js` (มี manualChunks สำหรับ bundle splitting) |
| DB connection config | `config/database.php` |

## Critical Constraints (อย่าฝ่าฝืน)

### Vercel Production

1. **`config/database.php` `PDO::ATTR_EMULATE_PREPARES`** ต้อง hardcode เป็น `false` ห้ามอ่านจาก `env()` เพราะ Vercel ส่ง env เป็น string → PDO TypeError
2. **`vercel.json` `excludeFiles`** ห้ามใส่ `public/build/**` หรือ `docs/**` — PHP ต้องอ่าน `manifest.json` ตอน `@vite()` และ ManualController ต้องอ่านไฟล์คู่มือ
3. **`public/build/`** ต้อง commit ลง git ทุกครั้งที่ frontend เปลี่ยน (รัน `npm run build` ก่อน push)
4. **Filesystem read-only** ยกเว้น `/tmp` — `FileHandler` upload จะ fail บน Vercel (TODO: ย้ายไป Supabase Storage)
5. **Cache driver = array** บน Vercel — `Cache::remember` ทำงานแต่ไม่ persist ข้าม request
6. **Session driver = cookie** — session อยู่ใน client cookie
7. **Function timeout 60s** — งานหนัก (export Excel ใหญ่) อาจ timeout

### Code Patterns ในระบบนี้

1. **DB transactions ทุกการ mutate หลายตาราง** — ดู `OrderController::store` เป็นตัวอย่าง
2. **InventoryService.adjustStock** ใช้ทุกครั้งที่สต็อกเปลี่ยน — อย่า update `products.quantity` ตรงๆ
3. **Soft delete สำหรับ Order** — ใช้ Void (`OrderController::voidOrder`) ไม่ใช่ `->delete()` ปกติ
4. **AuditLog** บันทึกทุก action สำคัญ (void, etc.) ระบุ user_id, ip, user_agent, target
5. **DataTables server-side** ทุกตารางหลังร้าน — อย่าใช้ client-side สำหรับข้อมูล > 100 row
6. **Cache busting** หลัง product mutation — ดู `ProductController::store/update/destroy`
7. **Yajra DataTables `rawColumns`** ต้อง escape สำหรับคอลัมน์ที่ render HTML
8. **`@vite('resources/js/app.jsx')`** ใน master blade — ต้อง build ก่อน

### Validation Pattern สำหรับ Checkbox

Checkbox HTML ส่ง `"on"` หรือไม่ส่งเลย Laravel `'boolean'` rule **ไม่รับ `"on"`** ใช้ pattern นี้:

```php
$request->merge([
    'is_active' => $request->has('is_active'),
]);
$data = $request->validate([
    'is_active' => 'boolean',
    // ...
]);
```

## Bugs ที่เคยเจอและแก้แล้ว (อย่าทำซ้ำ)

| Bug | สาเหตุ | แก้แล้วที่ |
|---|---|---|
| 419 CSRF page expired บน Vercel | session driver = file default | Vercel env: `SESSION_DRIVER=cookie` |
| PDO TypeError "must be of type bool" | `env('DB_EMULATE_PREPARES')` คืน string | hardcode `false` ใน `config/database.php` |
| Vite manifest not found | `public/build/**` ถูก exclude | ลบออกจาก `vercel.json` excludeFiles |
| ไม่พบคู่มือบน Vercel | `docs/**` ถูก exclude | ลบออกจาก `vercel.json` excludeFiles |
| Checkbox 'boolean' validation fail | Laravel ไม่รับ `"on"` | `$request->merge([...has...])` ก่อน validate |

## Sales Channels / Food Delivery Integration

ระบบบันทึกออเดอร์จากแพลตฟอร์ม delivery (Grab, LINE MAN, Shopee Food + ขยายได้) ผ่าน POS แบบ manual entry

Schema:

- ตาราง `sales_channels`: name, slug (unique), icon (FA class), color, commission_percent, status, sort_order
- คอลัมน์ใหม่ใน `orders`: `sales_channel_id` (FK, indexed), `platform_fee` (auto-calculated), `platform_order_ref`

Default channels (จาก `SalesChannelSeeder`): `walk_in` (0%), `grab` (32%), `line_man` (30%), `shopee_food` (30%)

Key invariants:

- `walk_in` ห้ามลบ (`SalesChannelController::destroy` block ไว้)
- `platform_fee = total * (commission_percent / 100)` คำนวณใน `SalesChannel::calculateFee()` และเรียกใน `OrderController::store`
- ถ้า request ไม่ส่ง `sales_channel_id` → default เป็น `walk_in`
- รายงานใช้ `LEFT JOIN sales_channels` + `GROUP BY` (single query) อย่าใช้ collection loop

API:

- `GET /admin/get/channels` — รายการ active channels สำหรับ POS dropdown
- `/admin/sales-channels` (resource, Admin only)
- `/admin/platform-sales-report` — aggregate ยอดขาย + commission แยกช่องทาง

POS UI (`Pos.jsx`):

- โหลด channels ใน useEffect แรก, default selectedChannelId เป็น walk_in
- แสดง `platform_order_ref` input เฉพาะเมื่อเลือกช่องทางที่ไม่ใช่ walk_in
- คำนวณ + แสดง estimated fee real-time

## Removed Features (อย่าใส่กลับโดยไม่ได้รับการร้องขอ)

- **ระบบสะสมแต้ม/รางวัล (Reward/Loyalty)** — ถูกถอดออกในเดือน 2026-05 เพราะใช้งานไม่ได้
  - ลบ menu, route (`reward-rules`, `/get/rewards`), controller, views, RewardService, RewardRuleSeeder
  - คงเหลือ: model `RewardRule`, `RewardUsage` และ migration ของ `reward_rules`, `reward_usages` (เก็บไว้กรณีต้องทำใหม่)
  - ลบ logic จาก `OrderController::store` (reward processing) และ `voidOrder` (reward reversal)
  - ลบ UI จาก `Pos.jsx` (reward state, available rewards select)
- **Demo user `demo@qtecsolution.net`** — แทนที่ด้วย `admin`/`admin` และ `kwan`/`kwan`

## Commit Style

- Subject สั้น: `Fix: ...` / `Perf: ...` / `Feat: ...` / `Docs: ...` / `Remove: ...`
- Body 1-3 บรรทัด อธิบาย WHY ไม่ใช่ WHAT
- เพิ่ม `Co-Authored-By: Claude Opus 4.7 <noreply@anthropic.com>` ทุก commit

## ก่อน Push

1. ถ้าแตะ frontend → build และ commit `public/build/`
2. ถ้าแตะ migration → ต้องระวังว่าอาจกระทบ schema production
3. ถ้าแตะ config/database → ทดสอบบน Vercel
4. Push ใช้ `git push origin HEAD:main` (local branch ชื่อ master, remote ชื่อ main)

## Performance Reference

ดูแนวทางแก้ไข performance อย่างละเอียดใน [`docs/th/admin-manual.md` ส่วนที่ 11](docs/th/admin-manual.md) — มี 11 หัวข้อย่อยครอบคลุม N+1, DataTables, Dashboard, POS cache, DB pooler, region match, indexes, bundle splitting

Composite indexes ที่มีอยู่:

- `orders(customer_id, created_at)`
- `products(status, quantity)`
- `stock_movements(product_id, created_at)`
- `order_transactions(order_id, created_at)`

## What NOT to Do

- ❌ อย่าใช้ `Order::all()->...` — ใช้ SQL aggregate (`Order::sum('total')`)
- ❌ อย่าลบ Order ตรงๆ — ใช้ Void
- ❌ อย่า update `products.quantity` ตรงๆ — ใช้ `InventoryService::adjustStock`
- ❌ อย่าใส่ secret/connection string ใน `.env` ที่ commit
- ❌ อย่า amend commit ที่ push แล้ว
- ❌ อย่า force push main
- ❌ อย่าใช้ `--no-verify` หรือ skip hooks
- ❌ อย่าใส่ `public/build/**` หรือ `docs/**` ใน `vercel.json` excludeFiles
- ❌ อย่าเปลี่ยน `PDO::ATTR_EMULATE_PREPARES` ให้อ่านจาก env
- ❌ อย่าเพิ่ม Reward/Loyalty system กลับเข้ามาโดยไม่ได้รับการร้องขอ

## TODO / Future Work

- ย้าย `FileHandler` upload ไปใช้ Supabase Storage (รูปไม่ persist บน Vercel)
- เปลี่ยน `CACHE_DRIVER` เป็น Upstash Redis ถ้าต้องการ cache persistent
- Background queue (ปัจจุบัน sync) สำหรับ Excel export ใหญ่
- Image optimization (WebP/AVIF + Vercel Image)
