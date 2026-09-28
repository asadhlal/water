<?php
session_start();
require_once 'config.php';
require_once 'helpers.php';

if (isset($_COOKIE['admin_login']) || isset($_SESSION['admin'])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";

if (isset($_POST['login'])) {
    $name     = sanitize($_POST['name']);
    $phone    = sanitize($_POST['phone']);
    $email    = sanitize($_POST['email']);
    $password = sanitize($_POST['password']);

    if (empty($name) || empty($phone) || empty($email) || empty($password)) {
        $error = "جميع الحقول مطلوبة!";
    } elseif (!check_email($email)) {
        $error = "البريد الإلكتروني غير صحيح!";
    } elseif (!check_phone($phone)) {
        $error = "رقم الهاتف غير صحيح!";
    } else {
        $stmt = $conn->prepare("SELECT * FROM admin WHERE name=? AND phone=? AND email=? AND password=?");
        $stmt->bind_param("ssss", $name, $phone, $email, $password);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows > 0) {
            $_SESSION['admin'] = $name;
            setcookie("admin_login", "ok", time() + (86400 * 30), "/");
            setcookie("admin_name", $name, time() + (86400 * 30), "/");
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "البيانات المدخلة غير مطابقة لحساب المدير!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تسجيل الدخول - مضخة ماء للوايتات</title>
    <!-- استدعاء ملف CSS المحلي المباشر -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="login-wrapper">
    <div class="login-card">
        <div class="login-sidebar">
            <img src="images/logo.png" style="width: 80px; margin-bottom: 15px;" onerror="this.src='https://cdn-icons-png.flaticon.com/512/3105/3105807.png'">
            <h2>مضخة ماء للوايتات</h2>
            <p style="margin-top: 10px; opacity: 0.8; font-size: 14px;">إدارة متكامله .. لخدمة أفضل</p>
        </div>
        <div class="login-form-box">
            <h3 style="color: #0d6efd; margin-bottom: 20px; text-align: center;">تسجيل الدخول - المدير</h3>
            
            <?php if($error): ?>
                <div class="alert alert-danger"><?=$error?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>اسم المدير</label>
                    <input type="text" name="name" class="form-control" required placeholder="أدخل الاسم">
                </div>
                <div class="form-group">
                    <label>رقم هاتف المدير</label>
                    <input type="text" name="phone" class="form-control" required placeholder="أدخل رقم الهاتف">
                </div>
                <div class="form-group">
                    <label>البريد الاكتروني الخاص ب المدير</label>
                    <input type="email" name="email" class="form-control" required placeholder="example@mail.com">
                </div>
                <div class="form-group">
                    <label>كلمة المرور الاحتياطيه</label>
                    <input type="password" name="password" class="form-control" required placeholder="••••••••">
                </div>
                <button type="submit" name="login" class="btn-primary-custom" style="margin-top: 10px;">دخول</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
