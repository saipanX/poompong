<?php
session_start();
require_once "AuthManager.php";
$auth = new AuthManager();
$auth->checkLogin();
if($_SESSION['role'] !== 'admin') {
    die("คุณไม่มีสิทธิ์เข้าถึงหน้านี้!");
}

require_once "CoffeeManager.php";
require_once "Coffee.php";
$manager = new CoffeeManager();

$id = intval($_GET['id'] ?? 0);
$coffeeData = $manager->getCoffeeById($id); 

if (!$coffeeData) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $category = trim($_POST['category'] ?? '');

    $updatedCoffee = new Coffee($name, $price, $category);
    
    $manager->updateCoffee($id, $updatedCoffee); 
    
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <title>แก้ไขเมนู</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="container form">
    <h2>แก้ไขเมนู</h2>
    <form method="post">
      <label>ชื่อเมนู</label>
      <input type="text" name="name" value="<?= htmlspecialchars($coffeeData['name']) ?>" required>
      
      <label>ราคา (บาท)</label>
      <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($coffeeData['price']) ?>" required>
      
      <label>ประเภท</label>
      <input type="text" name="category" value="<?= htmlspecialchars($coffeeData['category']) ?>">
      
      <div class="actions">
        <button class="btn" type="submit">บันทึก</button>
        <a class="btn" href="index.php">ยกเลิก</a>
      </div>
    </form>
  </div>
</body>
</html>