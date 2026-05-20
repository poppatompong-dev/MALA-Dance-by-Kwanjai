# คู่มือติดตั้งและรันระบบ

## Windows Local ใน Workspace นี้

```powershell
composer install
npm.cmd install
copy .env.example .env
.\.tools\php\php.exe artisan key:generate
.\.tools\php\php.exe artisan migrate:fresh --seed --force
npm.cmd run dev
.\.tools\php\php.exe artisan serve --host=127.0.0.1 --port=8000
```

เปิดระบบ:

```text
http://127.0.0.1:8000/login
```

บัญชีเดโม:

- Email: `demo@qtecsolution.net`
- Password: `87654321`

## SQLite สำหรับทดสอบ

ตัวอย่าง `.env`:

```env
DB_CONNECTION=sqlite
DB_DATABASE=D:\URY\database\database.sqlite
```

หากยังไม่มีไฟล์ฐานข้อมูล ให้สร้างไฟล์ `database/database.sqlite` ก่อนรัน migrate

## MySQL/MariaDB สำหรับใช้งานจริง

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mala_pos
DB_USERNAME=your_user
DB_PASSWORD=your_password
```

จากนั้นรัน:

```powershell
.\.tools\php\php.exe artisan migrate --seed --force
npm.cmd run build
.\.tools\php\php.exe artisan config:cache
.\.tools\php\php.exe artisan route:cache
.\.tools\php\php.exe artisan view:cache
```

## Docker

```powershell
docker compose up -d --build
```

หรือใช้ Makefile หากเครื่องรองรับ:

```bash
make setup
```

## ตรวจหลังติดตั้ง

1. เปิดหน้า login ได้
2. เข้าระบบด้วยบัญชีเดโมได้
3. เมนูเป็นภาษาไทย
4. POS แสดงสินค้าเดโมพร้อมรูป
5. ทดลองขายและพิมพ์ใบเสร็จได้
6. รายงานขายและรายงานสต็อกเปิดได้
