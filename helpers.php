<?php
// تنظيف المدخلات من الأكواد الخبيثة
function sanitize($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// الفلاتر المطلوبة
function check_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function check_phone($phone) {
    return preg_match('/^[0-9]{8,15}$/', $phone);
}
?>
