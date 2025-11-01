<?php
// ตรวจสอบว่า Database.php ถูก require หรือยัง (กันพลาด)
if (!class_exists('Database')) {
    require_once "Database.php";
}

class OrderManager {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    /**
     * บันทึกคำสั่งซื้อลง Database (orders และ order_details)
     * ใช้ Transaction เพื่อให้แน่ใจว่าข้อมูลเข้าทั้ง 2 ตาราง
     */
    public function saveOrder($staff_user_id, $customer_name, $cart_items) {
        if (empty($cart_items)) {
            return false;
        }

        // 1. คำนวณราคารวม
        $total_price = 0;
        foreach ($cart_items as $item) {
            $total_price += $item['price'] * $item['quantity'];
        }

        try {
            // 2. เริ่ม Transaction
            $this->conn->beginTransaction();

            // 3. บันทึกลงตารางแม่ (orders)
            $stmt = $this->conn->prepare(
                "INSERT INTO orders (staff_user_id, customer_name, total_price) VALUES (?, ?, ?)"
            );
            $stmt->execute([$staff_user_id, $customer_name, $total_price]);

            // 4. ดึง ID ของออเดอร์ที่เพิ่งสร้าง
            $order_id = $this->conn->lastInsertId();

            // 5. วนลูป บันทึกรายการสินค้าลงตารางลูก (order_details)
            $stmt_detail = $this->conn->prepare(
                "INSERT INTO order_details (order_id, coffee_id, coffee_name, quantity, price_per_item) 
                 VALUES (?, ?, ?, ?, ?)"
            );
            
            foreach ($cart_items as $coffee_id => $item) {
                $stmt_detail->execute([
                    $order_id,
                    $coffee_id,
                    $item['name'],
                    $item['quantity'],
                    $item['price']
                ]);
            }

            // 6. ถ้าสำเร็จทั้งหมด ให้ Commit
            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            // 7. ถ้ามีข้อผิดพลาด ให้ Rollback
            $this->conn->rollBack();
            // สามารถบันทึก $e->getMessage() ไว้ดู error log ได้
            return false;
        }
    }

    /**
     * ดึงประวัติการสั่งซื้อทั้งหมดสำหรับหน้า Admin
     */
    public function getOrderLogs() {
        // ใช้ JOIN เพื่อดึงชื่อ Staff (จาก users) และข้อมูลออเดอร์
        $sql = "SELECT 
                    o.order_id, o.customer_name, o.order_date, o.total_price,
                    u.username AS staff_name,
                    d.coffee_name, d.quantity, d.price_per_item
                FROM orders o
                JOIN users u ON o.staff_user_id = u.id
                JOIN order_details d ON o.order_id = d.order_id
                ORDER BY o.order_id DESC, d.detail_id ASC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>