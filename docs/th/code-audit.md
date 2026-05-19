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
