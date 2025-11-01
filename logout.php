<?php
session_start();
require_once "AuthManager.php";

$auth = new AuthManager();
$auth->logout();
?>