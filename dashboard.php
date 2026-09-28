<?php
session_start();
if (!isset($_COOKIE['admin_login']) && !isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit;
}
$admin_name = $_COOKIE['admin_name'] ?? $_SESSION['admin'] ?? 'المدير';
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>الرئيسية - مضخة ماء للوايتات</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="navbar-custom">
    <div class="navbar-brand">
        <img src="images/logo.png" style="height: 28px;" onerror="this.src='https://cdn-icons-png.flaticon.com/512/3105/3105807.png'">
        مضخة ماء للوايتات
    </div>
    <div class="navbar-user">
        <a href="logout.php">خروج</a>
    </div>
</div>

<div class="container">
    <div class="top-nav">
        <a href="dashboard.php" class="active">الرئيسية</a>
        <a href="add.php">إضافة سجل</a>
        <a href="add.php#records">عرض السجلات</a>
    </div>

    <div class="banner-box">
        <img src="images/banner.jpg" onerror="this.src='https://images.unsplash.com/photo-1541888946425-d0fbb186a5b3?w=1000'">
    </div>

    <div class="grid-2">
        <a href="add.php" style="text-decoration: none;">
            <div class="card-custom" style="background-color: #0d6efd; color: #fff; text-align: center; padding: 35px 20px;">
                <h3 style="margin-bottom: 10px;">إضافة سجل</h3>
                <p style="opacity: 0.8; font-size: 14px;">    اضافه سجلات جديده</p>
            </div>
        </a>
        <a href="add.php#records" style="text-decoration: none; color: inherit;">
            <div class="card-custom" style="text-align: center; padding: 35px 20px;">
                <h3 style="color: #0d6efd; margin-bottom: 10px;">عرض السجلات</h3>
                <p style="color: #6c757d; font-size: 14px;">عرض جميع السجلات السابقة</p>
            </div>
        </a>
    </div>
</div>

</body>
</html>
