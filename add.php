<?php
session_start();
require_once 'config.php';
require_once 'helpers.php';

if (!isset($_COOKIE['admin_login']) && !isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit;
}
$admin_name = $_COOKIE['admin_name'] ?? $_SESSION['admin'] ?? 'المدير';

$msg = "";
if (isset($_POST['save'])) {
    $owner_name  = sanitize($_POST['owner_name']);
    $liters      = filter_var($_POST['liters'], FILTER_VALIDATE_INT);
    $amount      = filter_var($_POST['amount'], FILTER_VALIDATE_FLOAT);
    $owner_phone = sanitize($_POST['owner_phone']);

    // معالجة رفع الصورة
    $image_name = "";
    if (isset($_FILES['owner_image']) && $_FILES['owner_image']['error'] == 0) {
        $ext = pathinfo($_FILES['owner_image']['name'], PATHINFO_EXTENSION);
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (in_array(strtolower($ext), $allowed)) {
            $image_name = time() . '_' . rand(1000, 9999) . '.' . $ext;
            if (!is_dir('uploads')) {
                mkdir('uploads', 0777, true);
            }
            move_uploaded_file($_FILES['owner_image']['tmp_name'], 'uploads/' . $image_name);
        }
    }

    if (!empty($owner_name) && $liters && $amount && check_phone($owner_phone)) {
        $stmt = $conn->prepare("INSERT INTO records (owner_name, liters, amount, owner_phone, image) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sidss", $owner_name, $liters, $amount, $owner_phone, $image_name);
        if ($stmt->execute()) {
            $msg = "<div class='alert alert-success'>تم حفظ البيانات والصورة بنجاح!</div>";
        }
    } else {
        $msg = "<div class='alert alert-danger'>يرجى التأكد من ملء الحقول بصيغة صحيحة!</div>";
    }
}

if (isset($_GET['del'])) {
    $del_id = filter_var($_GET['del'], FILTER_VALIDATE_INT);
    if ($del_id) {
        // حذف الصورة القديمة من مجلد uploads
        $get_img = $conn->prepare("SELECT image FROM records WHERE id = ?");
        $get_img->bind_param("i", $del_id);
        $get_img->execute();
        $res_img = $get_img->get_result()->fetch_assoc();
        if(!empty($res_img['image']) && file_exists('uploads/' . $res_img['image'])) {
            unlink('uploads/' . $res_img['image']);
        }

        $stmt = $conn->prepare("DELETE FROM records WHERE id = ?");
        $stmt->bind_param("i", $del_id);
        $stmt->execute();
    }
    header("Location: add.php");
    exit;
}

$records = $conn->query("SELECT * FROM records ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إضافة سجل - مضخة ماء للوايتات</title>
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
        <a href="dashboard.php">الرئيسية</a>
        <a href="add.php" class="active">إضافة سجل</a>
        <a href="#records">عرض السجلات</a>
    </div>

    <?=$msg?>

    <div class="card-custom">
        <h3 style="color: #0d6efd; margin-bottom: 20px; text-align: center;">   اضافه سجل جديد</h3>
        <form method="POST" enctype="multipart/form-data">
            <div class="grid-form">
                <div class="form-group">
                    <label>اسم صاحب الوايت </label>
                    <input type="text" name="owner_name" class="form-control" required placeholder="أدخل اسم صاحب الوايت">
                </div>
                <div class="form-group">
                    <label>   عدد اللترات </label>
                    <input type="number" name="liters" class="form-control" required placeholder="أدخل عدد اللترات">
                </div>
                <div class="form-group">
                    <label>المبلغ (ريال) </label>
                    <input type="number" step="0.01" name="amount" class="form-control" required placeholder="أدخل المبلغ">
                </div>
                <div class="form-group">
                    <label>رقم صاحب الوايت </label>
                    <input type="text" name="owner_phone" class="form-control" required placeholder="أدخل رقم الهاتف">
                </div>
                <div class="form-group" style="grid-column: span 2;">
                    <label>صورة السند / الإيصال (اختياري)</label>
                    <input type="file" name="owner_image" class="form-control" accept="image/*">
                </div>
            </div>
            <button type="submit" name="save" class="btn-primary-custom" style="margin-top: 15px;">حفظ </button>
        </form>
    </div>

    <div class="card-custom" id="records">
        <h3 style="color: #0d6efd; margin-bottom: 15px;"> السجلات </h3>
        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>الصورة</th>
                        <th>اسم صاحب الوايت</th>
                        <th>   عدد اللترات</th>
                        <th>المبلغ</th>
                        <th>رقم صاحب الوايت</th>
                        <th>التاريخ والوقت</th>
                        <th>حذف</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($records && $records->num_rows > 0): ?>
                        <?php while($row = $records->fetch_assoc()): ?>
                            <tr>
                                <td><?=$row['id']?></td>
                                <td>
                                    <?php if(!empty($row['image']) && file_exists('uploads/' . $row['image'])): ?>
                                        <a href="uploads/<?=$row['image']?>" target="_blank">
                                            <img src="uploads/<?=$row['image']?>" style="width: 45px; height: 45px; object-fit: cover; border-radius: 4px;">
                                        </a>
                                    <?php else: ?>
                                        <span style="color: #aaa; font-size: 12px;">بدون صورة</span>
                                    <?php endif; ?>
                                </td>
                                <td><?=$row['owner_name']?></td>
                                <td><?=$row['liters']?></td>
                                <td><?=number_format($row['amount'], 2)?></td>
                                <td><?=$row['owner_phone']?></td>
                                <td><?=date('h:i A | Y/m/d', strtotime($row['created_at']))?></td>
                                <td>
                                    <a href="add.php?del=<?=$row['id']?>" class="btn-danger btn-del" onclick="return confirm('هل أنت متأكد من الحذف؟')">حذف</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="8">لا توجد سجلات مضافة حتى الآن.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="main.js"></script>
</body>
</html>
