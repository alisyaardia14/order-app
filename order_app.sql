-- Database Order App
-- Kompatibel dengan MySQL 8 dan MariaDB 10.4+

CREATE DATABASE IF NOT EXISTS `order_app`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `order_app`;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `order_items`;
DROP TABLE IF EXISTS `orders`;
DROP TABLE IF EXISTS `menus`;
DROP TABLE IF EXISTS `migrations`;
SET FOREIGN_KEY_CHECKS = 1;


CREATE TABLE `migrations` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` VARCHAR(255) NOT NULL,
  `batch` INT NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `menus` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(150) NOT NULL,
  `price` BIGINT UNSIGNED NOT NULL,
  `description` TEXT NULL,
  `is_available` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `menus_name_unique` (`name`),
  KEY `menus_is_available_name_index` (`is_available`, `name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `orders` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_code` VARCHAR(30) NOT NULL,
  `customer_name` VARCHAR(150) NOT NULL,
  `customer_phone` VARCHAR(30) NOT NULL,
  `customer_address` TEXT NULL,
  `notes` TEXT NULL,
  `total_amount` BIGINT UNSIGNED NOT NULL,
  `status` ENUM('pending','processing','ready','completed','cancelled') NOT NULL DEFAULT 'pending',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_code_unique` (`order_code`),
  KEY `orders_status_created_at_index` (`status`, `created_at`),
  KEY `orders_customer_phone_index` (`customer_phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `order_items` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` BIGINT UNSIGNED NOT NULL,
  `menu_id` BIGINT UNSIGNED NULL,
  `menu_name` VARCHAR(150) NOT NULL,
  `unit_price` BIGINT UNSIGNED NOT NULL,
  `quantity` INT UNSIGNED NOT NULL,
  `subtotal` BIGINT UNSIGNED NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_menu_id_index` (`order_id`, `menu_id`),
  KEY `order_items_menu_id_foreign` (`menu_id`),
  CONSTRAINT `order_items_order_id_foreign`
    FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_menu_id_foreign`
    FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `menus`
  (`name`, `price`, `description`, `is_available`, `created_at`, `updated_at`)
VALUES
  ('Nasi Goreng', 20000, 'Nasi goreng spesial dengan telur dan ayam', 1, NOW(), NOW()),
  ('Mie Ayam', 18000, 'Mie ayam dengan suwiran ayam dan pangsit', 1, NOW(), NOW()),
  ('Ayam Geprek', 22000, 'Ayam geprek pedas disajikan dengan nasi putih', 1, NOW(), NOW());


INSERT INTO `migrations` (`migration`, `batch`) VALUES
  ('2026_07_31_000001_create_menus_table', 1),
  ('2026_07_31_000002_create_orders_table', 1),
  ('2026_07_31_000003_create_order_items_table', 1),
  ('2026_07_31_000004_insert_default_menus', 1);
