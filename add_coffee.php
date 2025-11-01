<?php
session_start();
require_once "AuthManager.php";
require_once "CoffeeManager.php";
require_once "Coffee.php";

$auth = new AuthManager();
$auth->checkLogin();
if($_SESSION['role'] !== 'admin') die("คุณไม่มีสิทธิ์เพิ่มเมนู!");

$coffeeManager = new CoffeeManager();
$error = '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST['save'])){
        $name = trim($_POST['name']);
        $price = trim($_POST['price']);
        $category = trim($_POST['category']);

        if(empty($name) || empty($price) || empty($category)){
            $error = "กรุณากรอกข้อมูลให้ครบทุกช่อง!";
        } else {
            // Upload รูป
            if(isset($_FILES['image']) && $_FILES['image']['error']===0){
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $imageName = uniqid().".".$ext;
                if(!is_dir("uploads")) mkdir("uploads",0777,true);
                move_uploaded_file($_FILES['image']['tmp_name'], "uploads/".$imageName);
            } else {
                $imageName = '';
            }

            $coffee = new Coffee($name,$price,$category);
            $coffeeManager->addCoffee($coffee,$imageName);
            header("Location:index.php");
            exit;
        }
    } elseif(isset($_POST['cancel'])){
        header("Location:index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="styles.css">
<title>➕ เพิ่มเมนูกาแฟ</title>
</head>
<body>
<div class="container">
<h2>➕ เพิ่มเมนูกาแฟ</h2>
<?php if($error) echo "<p style='color:red;'>$error</p>"; ?>
<form method="POST" enctype="multipart/form-data">
    <label>ชื่อเมนู:</label><br>
    <input type="text" name="name" required><br><br>
    <label>ราคา (บาท):</label><br>
    <input type="text" name="price" required><br><br>
    <label>ประเภท:</label><br>
    <input type="text" name="category" required><br><br>
    <label>รูปภาพ:</label><br>
    <input type="file" name="image" accept="image/*" required><br><br>
    <button type="submit" name="save">💾 บันทึก</button>
    <button type="submit" name="cancel">❌ ยกเลิก</button>
</form>

</div>
<footer>จัดทำโดย นายภูมิพงษ์ คำเพ็ญ 6704062616176</footer>

</body>
</html>
