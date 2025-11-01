<?php
session_start();
require_once "AuthManager.php";
require_once "CoffeeManager.php";

$auth = new AuthManager();
$auth->checkLogin();

$coffeeManager = new CoffeeManager();

$search = '';
if(isset($_GET['search']) && !empty($_GET['search'])){
    $search = trim($_GET['search']);
    $coffees = $coffeeManager->searchCoffees($search);
}else{
    $coffees = $coffeeManager->getAllCoffees();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="styles.css">
<title>☕ ระบบจัดการร้านกาแฟ</title>
</head>
<body>
<div class="container">
<h2>☕ ระบบจัดการร้านกาแฟ</h2>

<p>ผู้ใช้: <?= $_SESSION['username'] ?> (<?= $_SESSION['role'] ?>) | <a href="logout.php">ออกจากระบบ</a></p>

<form method="GET" style="margin-bottom:15px;">
    <input type="text" name="search" placeholder="ค้นหาชื่อกาแฟ..." value="<?= htmlspecialchars($search) ?>">
    <button type="submit">🔍 ค้นหา</button>
</form>

<?php if($_SESSION['role'] === 'admin'): ?>
    <a href="add_coffee.php" class="btn">➕ เพิ่มเมนู</a>
    <a href="order_logs.php" class="btn" style="background-color:#007bff;">📊 ดูประวัติการสั่งซื้อ</a>
<?php endif; ?>

<table class="menu-table">
<tr>
    <th>รูป</th>
    <th>ID</th>
    <th>ชื่อเมนู</th>
    <th>ราคา</th>
    <th>ประเภท</th>
    <?php if($_SESSION['role']==='admin'): ?><th>จัดการ</th><?php endif; ?>
</tr>

<?php foreach($coffees as $coffee): ?>
<tr>
    <td>
        <?php if($coffee['image']): ?>
            <img src="uploads/<?= $coffee['image'] ?>" width="80">
        <?php endif; ?>
    </td>
    <td><?= $coffee['id'] ?></td>
    <td><?= htmlspecialchars($coffee['name']) ?></td>
    <td><?= number_format($coffee['price'],2) ?></td>
    <td><?= htmlspecialchars($coffee['category']) ?></td>
    <?php if($_SESSION['role']==='admin'): ?>
    <td>
        <a href="delete_coffee.php?id=<?= $coffee['id'] ?>" onclick="return confirm('แน่ใจว่าจะลบเมนูนี้?')">🗑️ ลบ</a>
    </td>
    <?php endif; ?>
</tr>
<?php endforeach; ?>
</table>
</div>
<footer>จัดทำโดย นายภูมิพงษ์ คำเพ็ญ 6704062616176</footer>

</body>
</html>