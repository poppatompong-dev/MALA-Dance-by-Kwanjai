# บันทึกตรวจโค้ด

## ไฟล์สำคัญที่เกี่ยวข้อง

- `app/Helper.php`: เพิ่ม `mediaImage()` เพื่อรองรับรูปจาก public asset และ storage
- `app/Http/Resources/ProductResource.php`: ส่ง `image_url` ให้ POS ใช้โดยตรง
- `database/seeders/ProductSeeder.php`: seed สินค้า หม่าล่า/เครื่องดื่ม พร้อมรูปและ slug จาก SKU
- `config/system.php`: ค่าเริ่มต้นร้านภาษาไทยและ path โลโก้/favicon
- `resources/views/backend/layouts/sidebar.blade.php`: เมนูหลังร้านภาษาไทย
- `resources/views/backend/layouts/navbar.blade.php`: navbar ภาษาไทยและ aria-label
- `resources/js/components/Pos.jsx`: UI POS ภาษาไทยและรูปสินค้า

## แนวทางโค้ด

- ไม่ใช้ remote image URL ใน runtime
- รูป demo อยู่ใต้ `public/assets/images/demo`
- สินค้าไทยใน seeder ต้องมี slug จาก SKU เพื่อเลี่ยง slug ว่าง
- ใช้ `mediaImage()` เมื่อแสดง image field ที่อาจเป็น public asset หรือ storage path
- หลีกเลี่ยงการแก้ business logic หากเป็นงาน localization/visual

## คำสั่งตรวจ

```powershell
.\.tools\php\php.exe -l app/Helper.php
git diff --check
.\.tools\php\php.exe artisan migrate:fresh --seed --force
npm.cmd run build
.\.tools\php\php.exe artisan view:cache
```

## หมายเหตุ

งานนี้มี build artifacts ใน `public/build` ซึ่งอาจเปลี่ยนหลัง `npm.cmd run build` ให้ตรวจสถานะ git ก่อน stage/commit ทุกครั้ง และอย่า revert งานของผู้อื่น
