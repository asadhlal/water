-- =========================================================
-- مشروع نظام إدارة مضخة ماء للوايتات (Bomba)
-- =========================================================

CREATE DATABASE IF NOT EXISTS `bomba_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `bomba_db`;

-- 1. جدول الحسابات/المدير
CREATE TABLE IF NOT EXISTS `admin` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `phone` VARCHAR(20) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `password` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إضافة حساب المدير الافتراضي (إذا لم يكن موجوداً)
INSERT INTO `admin` (`id`, `name`, `phone`, `email`, `password`) 
VALUES (1, 'admin', '777123456', 'admin@gmail.com', '123456')
ON DUPLICATE KEY UPDATE `id`=`id`;

-- 2. جدول السجلات المحدث مع حقل الصورة
CREATE TABLE IF NOT EXISTS `records` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `owner_name` VARCHAR(100) NOT NULL,
    `liters` INT NOT NULL,
    `amount` DECIMAL(10, 2) NOT NULL,
    `owner_phone` VARCHAR(20) NOT NULL,
    `image` VARCHAR(255) NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
