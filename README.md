# Coffee Shop - ระบบจัดการร้านกาแฟ (ตัวอย่าง)

## คำอธิบาย
โปรเจ็กต์ตัวอย่างเป็นเว็บ PHP + MySQL ใช้หลัก OOP (classes) ในฝั่ง backend สำหรับจัดการเมนูกาแฟ (เพิ่ม แก้ไข ลบ แสดงผล)

## วิธีตั้งค่า (บนเครื่อง Windows ด้วย XAMPP)
1. ติดตั้ง XAMPP และเปิด Apache + MySQL
2. คัดลอกโฟลเดอร์นี้ไปไว้ที่ `C:\xampp\htdocs\coffee_shop`
3. เปิด phpMyAdmin -> สร้างฐานข้อมูลชื่อ `coffee_shop`
4. นำเข้าไฟล์ `db.sql` (ไฟล์ SQL ที่ให้มาด้วย) เพื่อสร้างตาราง `coffees`
5. เปิดเว็บเบราว์เซอร์: `http://localhost/coffee_shop/`

## ชื่อไฟล์สำคัญ
- index.php — หน้ารายการเมนู
- add_coffee.php — หน้าเพิ่มเมนู
- edit_coffee.php — หน้าแก้ไขเมนู
- delete_coffee.php — ลบเมนู
- Coffee.php, CoffeeManager.php, Database.php — class ต่าง ๆ (OOP)
- styles.css — สไตล์หน้าเว็บ

## หมายเหตุ
- ค่าเชื่อมต่อฐานข้อมูลติดตั้งสำหรับ `root` ไม่มีรหัสผ่าน หากคุณใช้ข้อมูลอื่น ให้แก้ไข `Database.php`
