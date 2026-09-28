<?php
session_start();
session_destroy();

// حذف الكوكيز بتصفير وقتها
setcookie("admin_login", "", time() - 3600, "/");
setcookie("admin_name", "", time() - 3600, "/");

header("Location: index.php");
exit;
?>
