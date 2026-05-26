# CLAUDE.md — Guide for AI Agents on MALA Dance by Kwanjai

เอกสารนี้สำหรับ Claude หรือ AI agent ที่จะทำงานบน codebase นี้ อ่านก่อนเริ่ม task ใหม่เพื่อเข้าใจบริบทและกฎสำคัญ

## Quick Context

**ระบบ**: POS + Management สำหรับร้านหม่าล่า/เครื่องดื่ม
**Stack**: Laravel 10 + Blade/AdminLTE + React 18 + Vite 4
**DB**: SQLite (local), Supabase Postgres (production via Vercel)
**Deploy**: Vercel (`vercel-php@0.7.4`, region `sin1`)
**Production URL**: https://mala-dance-by-kwanjai.vercel.app
**Main branch**: `main` (remote) / `master` (local) — push ใช้ `git push origin HEAD:main`

## Where to Find Things

| ต้องหา | ที่ |
|---|---|
| POS frontend (React) | `resources/js/components/Pos.jsx`, `Cart.jsx`, `CutomerSelect.jsx` |
| Backend controllers | `app/Http/Controllers/Backend/**` |
| Inventory / Stock logic | `app/Services/InventoryService.php` (immutable ledger pattern) |
| Reward / Loyalty logic | `app/Services/RewardService.php`, `RewardRuleController.php`, `OrderController::store/voidOrder` |
| Routes | `routes/web.php` (single file, all backend under `/admin` prefix) |
| Database schema | `database/migrations/` (รวม composite indexes) |
| Seeders | `database/seeders/` — `RolesAndPermissionsSeeder`, `ThaiShopSeeder` |
| Manuals (Thai) | `docs/th/*.md` — แสดงผ่าน `/admin/manuals` (allowlist) |
| Vercel config | `vercel.json` |
| Vite config | `vite.config.js` (มี manualChunks สำหรับ bundle splitting) |
| DB connection config | `config/database.php` |

## Critical Constraints (อย่าฝ่าฝืน)

### Vercel Production

1. **`config/database.php` `PDO::ATTR_EMULATE_PREPARES`** ต้อง hardcode เป็น `false` ห้ามอ่านจาก `env()` เพราะ Vercel ส่ง env เป็น string → PDO TypeError
2. **`vercel.json` `excludeFiles`** ห้ามใส่ `public/build/**` — PHP ต้องอ่าน `manifest.json` ตอน `@vite()`
3. **`public/build/`** ต้อง commit ลง git ทุกครั้งที่ frontend เปลี่ยน (รัน `npm run build` ก่อน push)
4. **Filesystem read-only** ยกเว้น `/tmp` — `FileHandler` upload จะ fail บน Vercel (ยังไม่ได้แก้ — TODO ย้ายไป Supabase Storage)
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

## Bugs ที่เคยเจอและแก้แล้ว (อย่าทำซ้ำ)

| Bug | สาเหตุ | แก้แล้วที่ |
|---|---|---|
| 419 CSRF page expired บน Vercel | session driver = file default | `.env`/Vercel: `SESSION_DRIVER=cookie` |
| PDO TypeError "must be of type bool" | `env('DB_EMULATE_PREPARES')` คืน string | hardcode `false` ใน `config/database.php` |
| Vite manifest not found | `public/build/**` ถูก exclude | ลบออกจาก `vercel.json` excludeFiles |
| RewardRule บันทึกไม่ได้ | validation `'boolean'` ไม่รับ `"on"` จาก checkbox | `$request->merge([...has...])` ก่อน validate |

## Validation Pattern สำหรับ Checkbox

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

## Commit Style

- Subject สั้น: `Fix: ...` / `Perf: ...` / `Feat: ...` / `Docs: ...`
- Body 1-3 บรรทัด อธิบาย WHY ไม่ใช่ WHAT
- เพิ่ม `Co-Authored-By: Claude Opus 4.7 <noreply@anthropic.com>` ทุก commit

## ก่อน Push

1. ถ้าแตะ frontend → รัน `npm run build` และ commit `public/build/`
2. ถ้าแตะ migration → ทดสอบ `php artisan migrate:fresh --seed` local ก่อน
3. ถ้าแตะ config/database → ทดสอบทั้ง local (SQLite) และ pgsql ถ้าทำได้
4. Push ใช้ `git push origin HEAD:main` (local branch ชื่อ master, remote ชื่อ main)

## Performance Reference

ดูแนวทางแก้ไข performance อย่างละเอียดใน [`docs/th/admin-manual.md` ส่วนที่ 11](docs/th/admin-manual.md) — มี 11 หัวข้อย่อยครอบคลุม N+1, DataTables, Dashboard, POS cache, DB pooler, region match, indexes, bundle splitting

Composite indexes ที่มีอยู่:

- `orders(customer_id, created_at)`
- `products(status, quantity)`
- `stock_movements(product_id, created_at)`
- `reward_usages(reward_rule_id, customer_id)`
- `order_transactions(order_id, created_at)`

## Useful Commands

```powershell
# Local dev
.\.tools\php\php.exe artisan serve --host=127.0.0.1 --port=8000
npm.cmd run dev

# Fresh DB
.\.tools\php\php.exe artisan migrate:fresh --seed --force

# Production build
npm.cmd run build
.\.tools\php\php.exe artisan view:cache

# Clear all caches
.\.tools\php\php.exe artisan optimize:clear
```

## Demo Login

- Email: `demo@qtecsolution.net`
- Password: `87654321`
- Role: Owner

## What NOT to Do

- ❌ อย่าใช้ `Order::all()->...` — ใช้ SQL aggregate (`Order::sum('total')`)
- ❌ อย่าลบ Order ตรงๆ — ใช้ Void
- ❌ อย่า update `products.quantity` ตรงๆ — ใช้ `InventoryService::adjustStock`
- ❌ อย่าใส่ secret/connection string ใน `.env` ที่ commit
- ❌ อย่า amend commit ที่ push แล้ว
- ❌ อย่า force push main
- ❌ อย่าใช้ `--no-verify` หรือ skip hooks
- ❌ อย่าใส่ `public/build/**` ใน `vercel.json` excludeFiles
- ❌ อย่าเปลี่ยน `PDO::ATTR_EMULATE_PREPARES` ให้อ่านจาก env

## TODO / Future Work

- ย้าย `FileHandler` upload ไปใช้ Supabase Storage (รูปไม่ persist บน Vercel)
- เปลี่ยน `CACHE_DRIVER` เป็น Upstash Redis ถ้าต้องการ cache persistent
- Background queue (ปัจจุบัน sync) สำหรับ Excel export ใหญ่
- Image optimization (WebP/AVIF + Vercel Image)
