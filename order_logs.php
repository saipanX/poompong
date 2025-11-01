<?php
session_start();
require_once "AuthManager.php";
require_once "OrderManager.php"; // ✅ ใช้ Manager ใหม่

$auth = new AuthManager();
$auth->checkLogin();

// ✅ ตรวจสอบสิทธิ์ Admin เท่านั้น
if ($_SESSION['role'] !== 'admin') {
    die("คุณไม่มีสิทธิ์เข้าถึงหน้านี้!");
}

$orderManager = new OrderManager();
$logs = $orderManager->getOrderLogs();

// จัดกลุ่มข้อมูลใหม่เพื่อให้แสดงผลในตารางได้ง่ายขึ้น
$grouped_logs = [];
foreach ($logs as $log) {
    $order_id = $log['order_id'];
    if (!isset($grouped_logs[$order_id])) {
        $grouped_logs[$order_id] = [
            'customer_name' => $log['customer_name'],
            'staff_name'    => $log['staff_name'],
            'order_date'    => $log['order_date'],
            'total_price'   => $log['total_price'],
            'items'         => []
        ];
    }
    $grouped_logs[$order_id]['items'][] = $log;
}

?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="styles.css">
<title>📊 ประวัติการสั่งซื้อ</title>
<style>
.container { max-width: 1000px; }
.log-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
.log-table th, .log-table td { border: 1px solid #ccc; padding: 8px; text-align: left; }
.log-table th { background-color: #f4f4f4; }
.order-header td { background-color: #f9f9f9; font-weight: bold; }
.item-row td { padding-left: 30px; }
</style>
</head>
<body>
<div class="container">
<h2>📊 ประวัติการสั่งซื้อ (Log)</h2>
<p>ผู้ใช้: <?= $_SESSION['username'] ?> (<?= $_SESSION['role'] ?>) | <a href="index.php">กลับหน้าหลัก</a> | <a href="logout.php">ออกจากระบบ</a></p>

<table class="log-table">
    <tr>
        <th>เลขที่ออเดอร์</th>
        <th>วันที่</th>
        <th>ชื่อลูกค้า</th>
        <th>พนักงานที่บันทึก</th>
        <th>รายการ</th>
        <th>จำนวน</th>
        <th>ราคา (รวม)</th>
    </tr>

    <?php if (empty($grouped_logs)): ?>
        <tr><td colspan="7" style="text-align:center;">ยังไม่มีประวัติการสั่งซื้อ</td></tr>
    <?php endif; ?>

    <?php foreach ($grouped_logs as $order_id => $order): ?>
        <tr class="order-header">
            <td>#<?= $order_id ?></td>
            <td><?= date('d/m/Y H:i', strtotime($order['order_date'])) ?></td>
            <td><?= htmlspecialchars($order['customer_name']) ?></td>
            <td><?= htmlspecialchars($order['staff_name']) ?></td>
            <td colspan="2"></td>
            <td><b><?= number_format($order['total_price'], 2) ?></b></td>
        </tr>
        
        <?php foreach ($order['items'] as $item): ?>
        <tr class="item-row">
            <td colspan="4"></td>
            <td><?= htmlspecialchars($item['coffee_name']) ?></td>
            <td><?= $item['quantity'] ?></td>
            <td>(<?= number_format($item['price_per_item'] * $item['quantity'], 2) ?>)</td>
        </tr>
        <?php endforeach; ?>

    <?php endforeach; ?>
</table>
</div>
<footer>จัดทำโดย นายภูมิพงษ์ คำเพ็ญ 6704062616176</footer>
</body>
</html>