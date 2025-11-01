<?php
session_start();

// ลบสินค้าจากตะกร้า
if (isset($_GET['remove'])) {
    $id = $_GET['remove'];
    unset($_SESSION['cart'][$id]);
    header("Location: cart.php");
    exit;
}

// ถ้าไม่มีตะกร้า ให้สร้างว่าง ๆ
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="styles.css">
<title>🛒 ตะกร้าของคุณ</title>
</head>
<body>
<h2>🛒 ตะกร้าของคุณ</h2>
<a href="shop.php">⬅️ กลับไปเลือกเมนู</a>

<?php if (empty($_SESSION['cart'])): ?>
    <p>ตะกร้าว่างเปล่า</p>
<?php else: ?>
<table border="1" cellpadding="8" style="margin:20px auto;">
    <tr>
        <th>ชื่อเมนู</th>
        <th>ราคา (บาท)</th>
        <th>จำนวน</th>
        <th>รวม (บาท)</th>
        <th>ลบ</th>
    </tr>
    <?php 
    $total = 0;
    foreach ($_SESSION['cart'] as $id => $item): 
        $subtotal = $item['price'] * $item['qty'];
        $total += $subtotal;
    ?>
    <tr>
        <td><?= $item['name'] ?></td>
        <td><?= number_format($item['price'],2) ?></td>
        <td><?= $item['qty'] ?></td>
        <td><?= number_format($subtotal,2) ?></td>
        <td><a href="cart.php?remove=<?= $id ?>" onclick="return confirm('ลบสินค้านี้?')">🗑️ ลบ</a></td>
    </tr>
    <?php endforeach; ?>
    <tr>
        <td colspan="3" style="text-align:right;"><b>รวมทั้งหมด</b></td>
        <td colspan="2"><b><?= number_format($total,2) ?> บาท</b></td>
    </tr>
</table>
<?php endif; ?>
</body>
</html>
