# MALA Dance by Kwanjai — POS & Management System

ระบบ Point-of-Sale และ Management สำหรับร้าน **หม่าล่าแดนซ์ by ขวัญใจ** ระดับ production พัฒนาบน Laravel 10 + React 18 + Vite พร้อม deploy ผ่าน Vercel + Supabase Postgres

**Production URL**: https://mala-dance-by-kwanjai.vercel.app
**Database**: Neon Postgres (ทั้ง local และ production)

## Core Features

- **POS หน้าร้านสำหรับร้านหม่าล่า**: รองรับ spice level, toppings, ประเภทออเดอร์ (dine_in / takeaway / delivery), สแกนบาร์โค้ด, ค้นหาด้วยชื่อสินค้า
- **Multi-channel Sales**: บันทึกยอดขายจากหน้าร้าน + แพลตฟอร์ม food delivery (Grab, LINE MAN, Shopee Food, เพิ่มได้) — ตัดสต็อกอัตโนมัติ คำนวณค่า commission ต่อแพลตฟอร์ม
- **Smart Inventory**: real-time stock deduction พร้อม `stock_movements` ledger (immutable) สำหรับ audit ทุกการเปลี่ยนแปลงสต็อก (sale, purchase, void, manual)
- **Void / Refund**: ยกเลิกบิลแบบ soft-delete พร้อมคืนสต็อกอัตโนมัติและบันทึก audit log
- **Security & RBAC**: 3 roles (Admin / Cashier / Sales associate) ใช้ Spatie Laravel Permission พร้อม `audit_logs` permanent
- **Performance**: SQL aggregation แทน collection loop, Cache::remember บน dashboard/POS, cache busting อัตโนมัติบน product mutation, composite indexes
- **Manuals หลังร้าน**: หน้า `/admin/manuals` อ่านคู่มือ Markdown ทั้งหมดจากในระบบ
- **Production**: Vercel + Neon Postgres + Vite build artifacts ใน git

## บัญชีผู้ใช้เริ่มต้น

- **ผู้ดูแลระบบ**: `admin` / `admin`
- **เจ้าของร้าน**: `kwan` / `kwan`

ระบบรองรับการล็อกอินด้วยชื่อผู้ใช้หรืออีเมล กรุณาเปลี่ยนรหัสผ่านทันทีหลังเข้าครั้งแรก

## Roles & Access

- **Admin**: ทุกเมนู รวม User Management
- **Cashier**: เฉพาะ POS
- **Sales associate**: ขายและดูรายการขาย

## Production Stack

| Layer | Tech |
|---|---|
| Hosting | Vercel (vercel-php@0.7.4), region `sin1` (Singapore) |
| Backend | Laravel 10, PHP 8.1+ |
| Frontend | Blade + AdminLTE 3, React 18, Vite 4 |
| Database | PostgreSQL via Neon |
| Auth | Laravel + Spatie Permission |
| Tables | Yajra DataTables (server-side) |
| Excel | Maatwebsite |
| PDF | barryvdh/laravel-dompdf |

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

**ห้าม commit** `.env`, `DATABASE_URL`, secrets ลง git

### Known Vercel Constraints

| ข้อจำกัด | ผลกระทบ | แนวทาง |
|---|---|---|
| Filesystem read-only (ยกเว้น `/tmp`) | upload รูปไม่ persist | ใช้ Supabase Storage / S3 (TODO) |
| Cache = array driver | ไม่ persist ระหว่าง request | Upstash Redis ถ้าต้องการ persistent |
| Function timeout 60s | งานหนัก block | export ขนาดใหญ่ต้อง async queue |
| Vite manifest ต้องอ่านได้ | `vercel.json` `excludeFiles` ห้ามใส่ `public/build/**` หรือ `docs/**` | commit `public/build/` ลง git |
| PDO::ATTR_EMULATE_PREPARES | env var เป็น string → TypeError | hardcode `false` ใน `config/database.php` |

## Documentation

คู่มือภาษาไทยอยู่ใน `docs/th/` และเข้าถึงได้จากเมนู `คู่มือการใช้งาน` หลังร้าน:

- [`docs/th/quick-start.md`](docs/th/quick-start.md) — เริ่มใช้งานเร็ว
- [`docs/th/owner-manual.md`](docs/th/owner-manual.md) — สำหรับเจ้าของร้าน
- [`docs/th/admin-manual.md`](docs/th/admin-manual.md) — สำหรับผู้ดูแลระบบ (รวมส่วนที่ 11: **แนวทางแก้ไข Performance**)
- [`docs/th/user-manual.md`](docs/th/user-manual.md) — สำหรับพนักงานหน้าร้าน
- [`system doc.md`](system%20doc.md) — เอกสารระบบ (architecture, modules, schema)
- [`CLAUDE.md`](CLAUDE.md) — คู่มือสำหรับ Claude/AI agent ทำงานบน codebase นี้

## License

Internal use for MALA Dance by Kwanjai
