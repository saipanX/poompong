<?php
session_start();
require_once "AuthManager.php";
require_once "CoffeeManager.php";
require_once "OrderManager.php";

$auth = new AuthManager();
$auth->checkLogin();

if($_SESSION['role'] !== 'staff'){
    header("Location: index.php");
    exit;
}

$coffeeManager = new CoffeeManager();
$orderManager = new OrderManager();
$coffees = $coffeeManager->getAllCoffees();

if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = [];
}

if(isset($_POST['add_to_cart'])){
    $id = $_POST['coffee_id'];
    $name = $_POST['coffee_name'];
    $price = $_POST['coffee_price'];
    if(isset($_SESSION['cart'][$id])){
        $_SESSION['cart'][$id]['quantity'] += 1;
    } else {
        $_SESSION['cart'][$id] = ['name'=>$name, 'price'=>$price, 'quantity'=>1];
    }
    header("Location: shop.php");
    exit;
}

if(isset($_POST['remove'])){
    $id = $_POST['coffee_id'];
    unset($_SESSION['cart'][$id]);
    header("Location: shop.php");
    exit;
}

$save_success = false;
$save_error = '';
if(isset($_POST['save_order'])){
    $customer_name = trim($_POST['customer_name'] ?? '');
    $cart_items = $_SESSION['cart'] ?? [];

    if(empty($customer_name)){
        $save_error = "กรุณาระบุชื่อลูกค้า!";
    } elseif(empty($cart_items)){
        $save_error = "ตะกร้าว่างเปล่า ไม่สามารถบันทึกได้!";
    } else {
        $staff_user_id = $_SESSION['user_id'];
        
        if($orderManager->saveOrder($staff_user_id, $customer_name, $cart_items)){
            unset($_SESSION['cart']); 
            $save_success = true;
        } else {
            $save_error = "เกิดข้อผิดพลาดในการบันทึกข้อมูล!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="styles.css">
<title>☕ ร้านกาแฟ - Staff</title>
<style>
.container{max-width:900px;margin:20px auto;padding:10px;}
.menu-table, table{width:100%;border-collapse:collapse;margin-bottom:20px;}
.menu-table th, .menu-table td, table th, table td{border:1px solid #ccc;padding:8px;text-align:center;}
.btn{padding:5px 10px;background:#28a745;color:#fff;text-decoration:none;border-radius:5px;}

button[name="save_order"] { background-color: #007bff; }
button[name="save_order"]:hover { background-color: #0056b3; }
.error { color: red; font-weight: bold; }
.success { color: green; font-weight: bold; }
</style>
</head>
<body>
<div class="container">
<h2>☕ ร้านกาแฟ - Staff</h2>
<p>ผู้ใช้: <?= $_SESSION['username'] ?> (<?= $_SESSION['role'] ?>) | <a href="logout.php">ออกจากระบบ</a></p>

<h3>เมนูทั้งหมด</h3>
<table class="menu-table">
<tr>
<th>รูป</th>
<th>ชื่อเมนู</th>
<th>ราคา</th>
<th>ประเภท</th>
<th>เลือกซื้อ</th>
</tr>
<?php foreach($coffees as $c): ?>
<tr>
    <td><?php if($c['image']): ?><img src="uploads/<?= $c['image'] ?>" width="80"><?php endif; ?></td>
    <td><?= htmlspecialchars($c['name']) ?></td>
    <td><?= number_format($c['price'],2) ?></td>
    <td><?= htmlspecialchars($c['category']) ?></td>
    <td>
        <form method="POST">
            <input type="hidden" name="coffee_id" value="<?= $c['id'] ?>">
            <input type="hidden" name="coffee_name" value="<?= htmlspecialchars($c['name']) ?>">
            <input type="hidden" name="coffee_price" value="<?= $c['price'] ?>">
            <button type="submit" name="add_to_cart">➕ ใส่ตะกร้า</button>
        </form>
    </td>
</tr>
<?php endforeach; ?>
</table>

<h3>ตะกร้าสินค้า (สำหรับบันทึก)</h3>

<form method="POST">
    <?php if($save_success): ?>
        <p class="success">✅ บันทึกคำสั่งซื้อเรียบร้อยแล้ว!</p>
    <?php endif; ?>
    <?php if($save_error): ?>
        <p class="error">⚠️ <?= $save_error ?></p>
    <?php endif; ?>

    <?php if(!empty($_SESSION['cart'])): ?>
    <table border="1" cellpadding="10">
    <tr>
    <th>ชื่อ</th>
    <th>ราคา</th>
    <th>จำนวน</th>
    <th>รวม</th>
    <th>ลบ</th>
    </tr>
    <?php $total=0; foreach($_SESSION['cart'] as $id=>$item): ?>
    <tr>
    <td><?= $item['name'] ?></td>
    <td><?= number_format($item['price'],2) ?></td>
    <td><?= $item['quantity'] ?></td>
    <td><?= number_format($item['price']*$item['quantity'],2) ?></td>
    <td>
        <form method="POST">
            <input type="hidden" name="coffee_id" value="<?= $id ?>">
            <button type="submit" name="remove">❌</button>
        </form>
    </td>
    </tr>
    <?php $total += $item['price']*$item['quantity']; endforeach; ?>
    <tr>
    <th colspan="3">รวมทั้งหมด</th>
    <th colspan="2"><?= number_format($total,2) ?> บาท</th>
    </tr>
    </table>

    <div style="margin-top: 15px;">
        <label for="customer_name"><b>ชื่อลูกค้า:</b></label><br>
        <input type="text" id="customer_name" name="customer_name" style="width: 300px;" required>
        <button type="submit" name="save_order" style="width: 150px; margin-left: 10px;">💾 บันทึกการสั่งซื้อ</button>
    </div>

    <?php else: ?>
        <p>ยังไม่มีสินค้าในตะกร้า</p>
    <?php endif; ?>

</form>

</div>
<footer>จัดทำโดย นายภูมิพงษ์ คำเพ็ญ 6704062616176</footer>
</body>
</html>