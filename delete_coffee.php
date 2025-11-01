<?php
session_start();
require_once "AuthManager.php";
$auth = new AuthManager();
$auth->checkLogin();
if($_SESSION['role'] !== 'admin') {
    die("คุณไม่มีสิทธิ์เข้าถึงหน้านี้!");
}

require_once "CoffeeManager.php";
$manager = new CoffeeManager();
$id = intval($_GET['id'] ?? 0);
if ($id) {
    $manager->deleteCoffee($id);
}
header("Location: index.php");
exit;