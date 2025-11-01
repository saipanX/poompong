<?php
require_once "Database.php";
require_once "User.php";

class AuthManager {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function login($username, $password) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = ? AND password = MD5(?)");
        $stmt->execute([$username, $password]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            return true;
        }
        return false;
    }

    public function logout() {
        session_destroy();
        header("Location: login.php");
        exit;
    }

    public function checkLogin() {
        if (!isset($_SESSION['username'])) {
            header("Location: login.php");
            exit();
        }
    }
}
?>