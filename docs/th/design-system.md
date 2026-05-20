# ระบบดีไซน์ภาษาไทย

ระบบนี้ปรับให้เป็น Thai Full Version สำหรับร้านหม่าล่าและเครื่องดื่ม โดยเน้นความเร็วในการขายหน้าร้าน ความชัดเจนของข้อมูล และหน้าจอที่อ่านง่ายสำหรับพนักงาน

## 1. หลักการออกแบบ

- ใช้ภาษาไทยเป็นค่าเริ่มต้นทุกหน้าที่เกี่ยวกับงานร้าน
- ใช้คำสั้น ชัด และตรงกับงานจริง เช่น `ขายหน้าร้าน`, `ซื้อเข้า`, `สรุปยอดขาย`
- ปุ่มต้องบอกการกระทำชัด เช่น `บันทึก`, `อัปเดต`, `ชำระเงิน`, `พิมพ์`
- ช่องกรอกข้อมูลต้องมี label และ placeholder ที่เป็นตัวอย่างจริง
- ภาพต้องมี alt และใช้ fallback เมื่อไม่มีรูป
- เมนูต้องไม่ยาวจนล้น sidebar และต้องจัดกลุ่มตามงาน

## 2. โทนภาพ

ใช้ภาพภายใน repo ทั้งหมด:

- โลโก้: `public/assets/images/demo/logo.svg`
- favicon: `public/assets/images/demo/favicon.svg`
- no image: `public/assets/images/demo/no-image.svg`
- auth/error: `public/assets/images/demo/auth`, `public/assets/images/demo/errors`
- สินค้า หมวด และแบรนด์: `public/assets/images/demo/products`, `categories`, `brands`

ภาพสินค้าเดโมใช้ placeholder แยกไฟล์รายสินค้า เพื่อให้ POS ดูเหมือนร้านจริงและไม่พึ่งพา remote URL

## 3. เมนูหลัก

- แดชบอร์ด
- ขายหน้าร้าน
- ลูกค้าและซัพพลายเออร์
- สินค้า
- การขาย
- ซื้อเข้า
- รายงาน
- ตั้งค่าระบบ

## 4. Accessibility ตาม guideline

- icon-only links ใน navbar มี `aria-label`
- decorative icons ใช้ `aria-hidden="true"`
- รูปโลโก้และรูป error มี alt ภาษาไทย
- layout หลักใช้ `lang="th"`
- ข้อความ loading ควรลงท้ายด้วย `…`
- หลีกเลี่ยงข้อความอังกฤษใน UI หลักของร้าน

## 5. แนวทางข้อความ

ใช้คำเหล่านี้ให้สม่ำเสมอ:

| อังกฤษเดิม | ไทยที่ใช้ |
|---|---|
| Dashboard | แดชบอร์ด |
| POS | ขายหน้าร้าน |
| Products | สินค้า |
| Categories | หมวดสินค้า |
| Brands | แบรนด์/แหล่งสินค้า |
| Units | หน่วยนับ |
| Customers | ลูกค้า |
| Suppliers | ซัพพลายเออร์ |
| Orders | รายการขาย |
| Purchase | ซื้อเข้า |
| Reports | รายงาน |
| Settings | ตั้งค่า |
