<?php
session_start();
require_once "AuthManager.php";

$auth = new AuthManager();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($auth->login($username, $password)) {
        
        if ($_SESSION['role'] === 'admin') {
            header("Location: index.php");
        } elseif ($_SESSION['role'] === 'staff') {
            header("Location: shop.php");
        } else {
            header("Location: index.php"); 
        }
        exit;

    } else {
        $error = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง!";
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="styles.css">
<title>เข้าสู่ระบบ</title>
</head>
<body>
<div class="container">
    <h2>☕ เข้าสู่ระบบร้านกาแฟ</h2>
    <?php if ($error): ?><p style="color:red;"><?= $error ?></p><?php endif; ?>
    <form method="POST">
        <label>ชื่อผู้ใช้:</label><br>
        <input type="text" name="username" required><br>
        <label>รหัสผ่าน:</label><br>
        <input type="password" name="password" required><br><br>
        <button type="submit">เข้าสู่ระบบ</button>
    </form>
    
</div>
<footer>จัดทำโดย นายภูมิพงษ์ คำเพ็ญ 6704062616176</footer>

</body>
</html>