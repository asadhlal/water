<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "bomba_db";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("خطأ الاتصال: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
?>
