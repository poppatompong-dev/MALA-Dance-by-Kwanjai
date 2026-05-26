# MALA Dance by Kwanjai — POS & Management System

ระบบ Point-of-Sale และ Management สำหรับร้าน **หม่าล่าแดนซ์ by ขวัญใจ** ระดับ production พัฒนาบน Laravel 10 + React 18 + Vite พร้อม deploy ผ่าน Vercel + Supabase Postgres

## Core Features

- **POS หน้าร้านสำหรับร้านหม่าล่า**: รองรับ spice level, toppings, ประเภทออเดอร์ (dine_in / takeaway / delivery), สแกนบาร์โค้ด, สแกนสินค้าด้วยชื่อ
- **Smart Inventory**: real-time stock deduction พร้อม `stock_movements` ledger (immutable) สำหรับ audit ทุกการเปลี่ยนแปลงสต็อก (sale, purchase, void, manual)
- **ระบบสะสมแต้มและรางวัล (Loyalty)**: กฎรางวัลแบบ flexible — earn_points, redeem_points, coupon, tier, birthday พร้อมเงื่อนไข min_purchase, usage_limit, per_customer_limit, stackable
- **Void / Refund**: ยกเลิกบิลแบบ soft-delete พร้อมคืนสต็อกและคืนแต้มลูกค้าอัตโนมัติ
- **Security & RBAC**: 3 roles (Owner / Admin / Cashier) ใช้ Spatie Laravel Permission พร้อม `audit_logs` permanent
- **Performance**: SQL aggregation แทน collection loop, Cache::remember บน dashboard/POS, cache busting อัตโนมัติบน product mutation, composite indexes
- **Manuals หลังร้าน**: หน้า `/admin/manuals` อ่านคู่มือ Markdown ทั้งหมดจากในระบบ
- **Production Deploy**: Vercel + Supabase Postgres (Transaction Pooler) + Vite build artifacts ใน git

## Requirements

- PHP 8.1+
- Composer
- Node.js 18+ & NPM
- SQLite / MySQL (local) หรือ PostgreSQL (production)

## Local Development Setup

1. **Clone**
   ```bash
   git clone https://github.com/poppatompong-dev/MALA-Dance-by-Kwanjai.git
   cd MALA-Dance-by-Kwanjai
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database (SQLite default)**
   ```bash
   touch database/database.sqlite
   php artisan migrate:fresh --seed
   ```
   Seeders ที่รันอัตโนมัติ: `RolesAndPermissionsSeeder` (3 roles + permissions), `ThaiShopSeeder` (เมนูหม่าล่า/เครื่องดื่ม)

5. **Run**
   ```bash
   php artisan serve
   # อีกหน้าต่าง
   npm run dev
   ```

   เปิด http://127.0.0.1:8000/login

## Demo Account

- Email: `demo@qtecsolution.net`
- Password: `87654321`
- Role: Owner

## Roles & Access

- **Owner**: ทุกเมนู + User Management
- **Admin**: ทุกเมนูยกเว้น User Management
- **Cashier**: เฉพาะ POS

## Production Deployment

### Vercel + Supabase Postgres

ระบบ deploy ที่ https://mala-dance-by-kwanjai.vercel.app

**Build process**:
```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm run build  # output ไป public/build/ — ต้อง commit ลง git
```

**Vercel Environment Variables** (ต้องตั้งใน Vercel Dashboard):

```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://mala-dance-by-kwanjai.vercel.app
APP_KEY=<base64 key from php artisan key:generate>
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
| Vite manifest ต้องอ่านได้ | `vercel.json` `excludeFiles` ห้ามใส่ `public/build/**` | commit `public/build/` ลง git |
| PDO::ATTR_EMULATE_PREPARES | env var เป็น string → TypeError | hardcode `false` ใน `config/database.php` |

## Documentation

คู่มือภาษาไทยอยู่ใน `docs/th/`:

- [`docs/th/quick-start.md`](docs/th/quick-start.md) — เริ่มใช้งานเร็ว
- [`docs/th/owner-manual.md`](docs/th/owner-manual.md) — สำหรับเจ้าของร้าน
- [`docs/th/admin-manual.md`](docs/th/admin-manual.md) — สำหรับผู้ดูแลระบบ (มีส่วนที่ 11: **แนวทางแก้ไข Performance**)
- [`docs/th/user-manual.md`](docs/th/user-manual.md) — สำหรับพนักงานหน้าร้าน
- [`system doc.md`](system%20doc.md) — เอกสารระบบ (architecture, modules, schema)
- [`CLAUDE.md`](CLAUDE.md) — คู่มือสำหรับ Claude/AI agent ทำงานบน codebase นี้

เข้าหลังร้านแล้วอ่านได้ที่เมนู `คู่มือการใช้งาน` หรือ `/admin/manuals`

## Tech Stack

- **Backend**: Laravel 10, PHP 8.1+
- **Frontend**: Blade + AdminLTE 3, React 18, Vite 4
- **Database**: SQLite (dev) / PostgreSQL via Supabase (production)
- **Auth**: Laravel + Spatie Permission, Google OAuth optional
- **DataTables**: Yajra (server-side)
- **Excel**: Maatwebsite
- **PDF**: barryvdh/laravel-dompdf
- **Hosting**: Vercel (vercel-php@0.7.4)

## License

Internal use for MALA Dance by Kwanjai
