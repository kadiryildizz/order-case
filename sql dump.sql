-- -------------------------------------------------------------
-- TablePlus 6.4.2(600)
--
-- https://tableplus.com/
--
-- Database: laravel
-- Generation Time: 2025-04-05 13:25:22.1960
-- -------------------------------------------------------------


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


CREATE TABLE `campaigns` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount` double(8,2) NOT NULL DEFAULT '0.00',
  `discount_type` enum('AMOUNT','PERCENT','GIFT') COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_count` int NOT NULL DEFAULT '0',
  `count_type` enum('DIFF','SAME') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'DIFF',
  `count_apply` enum('CHEAPEST','PREVIOUS') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'CHEAPEST',
  `minimum_cart_total` int NOT NULL DEFAULT '0',
  `main_type` enum('CATEGORY','PRODUCT','BRAND') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'CATEGORY,PRODUCT,BRAND',
  `main_id` int DEFAULT NULL,
  `status` enum('PASSIVE','ACTIVE') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PASSIVE',
  `criterions` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active_since` datetime DEFAULT NULL,
  `active_till` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '0,1,2',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`),
  KEY `categories_status_index` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `customers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '0,1,2',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `customers_status_index` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `order_products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `order_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL,
  `unit_price` double(8,2) NOT NULL,
  `discount` double(8,2) NOT NULL DEFAULT '0.00',
  `total_price` double(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_products_product_id_foreign` (`product_id`),
  KEY `order_products_order_id_foreign` (`order_id`),
  CONSTRAINT `order_products_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` bigint unsigned NOT NULL,
  `campaign_id` bigint unsigned DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0' COMMENT '0,1,2',
  `total` double(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `orders_status_index` (`status`),
  KEY `orders_customer_id_foreign` (`customer_id`),
  KEY `orders_campaign_id_foreign` (`campaign_id`),
  CONSTRAINT `orders_campaign_id_foreign` FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`id`) ON DELETE CASCADE,
  CONSTRAINT `orders_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` bigint unsigned NOT NULL,
  `price` double(8,2) NOT NULL,
  `stock` int NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '0,1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_slug_unique` (`slug`),
  KEY `products_status_index` (`status`),
  KEY `products_category_id_foreign` (`category_id`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `campaigns` (`id`, `title`, `discount`, `discount_type`, `product_count`, `count_type`, `count_apply`, `minimum_cart_total`, `main_type`, `main_id`, `status`, `criterions`, `active_since`, `active_till`, `created_at`, `updated_at`) VALUES
(1, 'Sepette %10 indirim', 10.00, 'PERCENT', 1, 'DIFF', 'CHEAPEST', 1000, 'PRODUCT', NULL, 'ACTIVE', '[\"minimum_cart_total\"]', '2025-04-01 12:00:00', '2026-12-31 12:00:00', '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(2, '2 ID\'li kategoriden Aynı üründen 6 adet satın alana birtanesi ücretsiz.', 1.00, 'GIFT', 6, 'SAME', 'CHEAPEST', 0, 'CATEGORY', 2, 'ACTIVE', '[\"combine\"]', '2025-04-01 12:00:00', '2026-12-31 12:00:00', '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(3, '4 ID\'li kategoriden iki veya daha fazla ürün var ise, en ucuz üründe %20 indirim.', 20.00, 'PERCENT', 2, 'DIFF', 'CHEAPEST', 0, 'CATEGORY', 4, 'ACTIVE', '[\"combine\"]', '2025-04-01 12:00:00', '2026-12-31 12:00:00', '2025-04-03 18:13:38', '2025-04-03 18:13:38');

INSERT INTO `categories` (`id`, `name`, `slug`, `status`, `created_at`, `updated_at`) VALUES
(1, 'eligendi et', 'eligendi-et', 1, '2025-04-03 18:13:37', '2025-04-03 18:13:37'),
(2, 'sit eos', 'sit-eos', 1, '2025-04-03 18:13:37', '2025-04-03 18:13:37'),
(3, 'nemo provident', 'nemo-provident', 1, '2025-04-03 18:13:37', '2025-04-03 18:13:37'),
(4, 'occaecati soluta', 'occaecati-soluta', 1, '2025-04-03 18:13:37', '2025-04-03 18:13:37'),
(5, 'est harum', 'est-harum', 1, '2025-04-03 18:13:37', '2025-04-03 18:13:37'),
(6, 'perferendis magni', 'perferendis-magni', 1, '2025-04-03 18:13:37', '2025-04-03 18:13:37'),
(7, 'ut recusandae', 'ut-recusandae', 1, '2025-04-03 18:13:37', '2025-04-03 18:13:37'),
(8, 'et dolor', 'et-dolor', 1, '2025-04-03 18:13:37', '2025-04-03 18:13:37'),
(9, 'qui totam', 'qui-totam', 1, '2025-04-03 18:13:37', '2025-04-03 18:13:37'),
(10, 'minus quam', 'minus-quam', 1, '2025-04-03 18:13:37', '2025-04-03 18:13:37');

INSERT INTO `customers` (`id`, `name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Ms. Willie Lebsack I', 1, '2025-04-03 18:13:37', '2025-04-03 18:13:37'),
(2, 'Miss Dejah Ritchie', 1, '2025-04-03 18:13:37', '2025-04-03 18:13:37'),
(3, 'Ms. Mariam Jones Jr.', 1, '2025-04-03 18:13:37', '2025-04-03 18:13:37'),
(4, 'Wilford Murazik', 1, '2025-04-03 18:13:37', '2025-04-03 18:13:37'),
(5, 'Carroll Cruickshank', 1, '2025-04-03 18:13:37', '2025-04-03 18:13:37'),
(6, 'Chance Yost Sr.', 1, '2025-04-03 18:13:37', '2025-04-03 18:13:37'),
(7, 'Santa Grady V', 1, '2025-04-03 18:13:37', '2025-04-03 18:13:37'),
(8, 'Mr. Lane McDermott Jr.', 1, '2025-04-03 18:13:37', '2025-04-03 18:13:37'),
(9, 'Cordia Hermiston', 1, '2025-04-03 18:13:37', '2025-04-03 18:13:37'),
(10, 'Alaina Harvey', 1, '2025-04-03 18:13:37', '2025-04-03 18:13:37'),
(11, 'Bernardo Bailey', 1, '2025-04-03 18:13:37', '2025-04-03 18:13:37'),
(12, 'Kenya Hegmann', 1, '2025-04-03 18:13:37', '2025-04-03 18:13:37'),
(13, 'Dr. Cole Willms', 1, '2025-04-03 18:13:37', '2025-04-03 18:13:37'),
(14, 'Dr. Jaclyn Harris II', 1, '2025-04-03 18:13:37', '2025-04-03 18:13:37'),
(15, 'Genevieve Herman', 1, '2025-04-03 18:13:37', '2025-04-03 18:13:37');

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(11, '2014_10_12_000000_create_users_table', 1),
(12, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(13, '2019_08_19_000000_create_failed_jobs_table', 1),
(14, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(15, '2025_04_03_164756_create_all_make_table', 1);

INSERT INTO `order_products` (`id`, `product_id`, `order_id`, `quantity`, `unit_price`, `discount`, `total_price`, `created_at`, `updated_at`) VALUES
(5, 39, 13, 3, 55.53, 0.00, 166.59, '2025-04-04 00:56:55', '2025-04-04 08:57:32'),
(6, 18, 13, 7, 74.82, 0.00, 523.74, '2025-04-04 13:18:23', '2025-04-04 13:18:29'),
(7, 82, 14, 3, 72.89, 0.00, 218.67, '2025-04-04 15:35:55', '2025-04-04 15:35:55'),
(8, 13, 14, 2, 28.61, 0.00, 57.22, '2025-04-04 15:52:23', '2025-04-04 16:07:43'),
(9, 1, 15, 1, 741.71, 0.00, 741.71, '2025-04-05 09:34:59', '2025-04-05 09:34:59'),
(10, 2, 15, 1, 595.85, 0.00, 595.85, '2025-04-05 09:59:14', '2025-04-05 09:59:14'),
(11, 18, 16, 7, 74.82, 0.00, 523.74, '2025-04-05 10:09:50', '2025-04-05 10:10:47'),
(12, 80, 14, 1, 54.06, 0.00, 54.06, '2025-04-05 10:16:47', '2025-04-05 10:16:47');

INSERT INTO `orders` (`id`, `customer_id`, `campaign_id`, `status`, `total`, `created_at`, `updated_at`) VALUES
(13, 1, 3, 2, 0.00, '2025-04-04 00:56:55', '2025-04-04 16:13:21'),
(14, 2, 3, 0, 0.00, '2025-04-04 15:35:55', '2025-04-04 15:43:01'),
(15, 1, 1, 2, 0.00, '2025-04-04 16:13:28', '2025-04-05 10:05:54'),
(16, 1, 2, 0, 0.00, '2025-04-05 10:06:59', '2025-04-05 10:06:59');

INSERT INTO `products` (`id`, `name`, `slug`, `category_id`, `price`, `stock`, `status`, `created_at`, `updated_at`) VALUES
(1, 'quos quaerat et', 'quos-quaerat-et', 10, 741.71, 1, 1, '2025-04-03 18:13:37', '2025-04-03 18:13:37'),
(2, 'officiis est non', 'officiis-est-non', 5, 595.85, 5, 1, '2025-04-03 18:13:37', '2025-04-03 18:13:37'),
(3, 'quas voluptas vitae', 'quas-voluptas-vitae', 1, 51.08, 4, 0, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(4, 'blanditiis sunt quas', 'blanditiis-sunt-quas', 7, 45.18, 1, 1, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(5, 'laudantium dignissimos ducimus', 'laudantium-dignissimos-ducimus', 3, 79.86, 1, 0, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(6, 'ut ut ipsa', 'ut-ut-ipsa', 7, 53.34, 9, 1, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(7, 'expedita minima tempora', 'expedita-minima-tempora', 3, 11.21, 0, 0, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(8, 'voluptatem animi natus', 'voluptatem-animi-natus', 5, 73.77, 9, 1, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(9, 'dolor ipsum distinctio', 'dolor-ipsum-distinctio', 9, 49.99, 7, 0, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(10, 'et nisi repudiandae', 'et-nisi-repudiandae', 7, 39.73, 2, 0, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(11, 'quam consequuntur accusantium', 'quam-consequuntur-accusantium', 4, 76.22, 8, 0, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(12, 'sed odit nihil', 'sed-odit-nihil', 9, 84.39, 7, 1, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(13, 'quis quod eos', 'quis-quod-eos', 4, 28.61, 7, 1, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(14, 'nisi consequatur mollitia', 'nisi-consequatur-mollitia', 10, 5.45, 4, 1, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(15, 'ex deleniti atque', 'ex-deleniti-atque', 6, 53.14, 7, 1, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(16, 'eum atque perspiciatis', 'eum-atque-perspiciatis', 10, 57.75, 7, 1, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(17, 'fugit nam libero', 'fugit-nam-libero', 1, 37.17, 2, 0, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(18, 'iure quia qui', 'iure-quia-qui', 2, 74.82, 8, 1, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(19, 'expedita maxime quo', 'expedita-maxime-quo', 2, 68.63, 2, 1, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(20, 'culpa voluptates corporis', 'culpa-voluptates-corporis', 8, 79.38, 2, 1, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(21, 'illo sint ex', 'illo-sint-ex', 7, 51.11, 2, 1, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(22, 'laboriosam sed consequatur', 'laboriosam-sed-consequatur', 3, 91.94, 3, 1, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(23, 'veniam aut temporibus', 'veniam-aut-temporibus', 10, 54.93, 5, 0, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(24, 'a nulla eaque', 'a-nulla-eaque', 9, 35.79, 8, 0, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(25, 'odit qui officiis', 'odit-qui-officiis', 9, 37.46, 9, 1, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(26, 'architecto fugiat itaque', 'architecto-fugiat-itaque', 3, 66.86, 6, 1, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(27, 'non velit quasi', 'non-velit-quasi', 5, 69.22, 1, 1, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(28, 'provident accusantium nulla', 'provident-accusantium-nulla', 10, 21.56, 3, 1, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(29, 'est ea excepturi', 'est-ea-excepturi', 5, 23.10, 4, 1, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(30, 'porro et at', 'porro-et-at', 8, 99.12, 4, 1, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(31, 'porro excepturi laboriosam', 'porro-excepturi-laboriosam', 1, 94.14, 4, 1, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(32, 'exercitationem eos tempore', 'exercitationem-eos-tempore', 10, 25.65, 1, 0, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(33, 'totam ea cupiditate', 'totam-ea-cupiditate', 8, 84.97, 2, 0, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(34, 'et saepe veritatis', 'et-saepe-veritatis', 8, 37.66, 1, 1, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(35, 'repudiandae ipsa dolores', 'repudiandae-ipsa-dolores', 4, 29.55, 4, 0, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(36, 'corrupti dolorem et', 'corrupti-dolorem-et', 1, 8.44, 5, 1, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(37, 'quisquam pariatur neque', 'quisquam-pariatur-neque', 6, 34.84, 4, 0, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(38, 'molestiae inventore nesciunt', 'molestiae-inventore-nesciunt', 9, 45.21, 1, 1, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(39, 'et alias quos', 'et-alias-quos', 9, 55.53, 3, 1, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(40, 'totam vel blanditiis', 'totam-vel-blanditiis', 5, 26.49, 9, 0, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(41, 'repellat aspernatur rerum', 'repellat-aspernatur-rerum', 9, 70.50, 5, 1, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(42, 'rerum pariatur aspernatur', 'rerum-pariatur-aspernatur', 6, 49.14, 7, 0, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(43, 'quis omnis dicta', 'quis-omnis-dicta', 3, 17.80, 7, 0, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(44, 'cupiditate veritatis enim', 'cupiditate-veritatis-enim', 5, 25.28, 5, 0, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(45, 'ut sit nam', 'ut-sit-nam', 1, 81.89, 0, 0, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(46, 'nulla quia quae', 'nulla-quia-quae', 9, 74.57, 3, 0, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(47, 'amet odio qui', 'amet-odio-qui', 1, 57.47, 7, 0, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(48, 'ut enim culpa', 'ut-enim-culpa', 3, 44.28, 6, 0, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(49, 'iure nihil quaerat', 'iure-nihil-quaerat', 10, 56.32, 9, 0, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(50, 'iure sint ipsa', 'iure-sint-ipsa', 4, 25.92, 2, 0, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(51, 'laboriosam unde officiis', 'laboriosam-unde-officiis', 2, 21.82, 3, 0, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(52, 'quis quasi id', 'quis-quasi-id', 4, 61.78, 5, 0, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(53, 'laudantium qui voluptas', 'laudantium-qui-voluptas', 7, 22.76, 0, 0, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(54, 'aliquid id nam', 'aliquid-id-nam', 6, 61.14, 2, 0, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(55, 'rem eius aliquid', 'rem-eius-aliquid', 3, 92.52, 8, 1, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(56, 'nemo debitis nemo', 'nemo-debitis-nemo', 5, 34.33, 6, 1, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(57, 'aut cumque error', 'aut-cumque-error', 6, 77.06, 5, 1, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(58, 'ipsum quia iusto', 'ipsum-quia-iusto', 7, 38.03, 1, 1, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(59, 'magnam laudantium eius', 'magnam-laudantium-eius', 6, 94.28, 6, 1, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(60, 'voluptas perspiciatis eos', 'voluptas-perspiciatis-eos', 1, 37.26, 3, 1, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(61, 'officia dolor expedita', 'officia-dolor-expedita', 6, 7.61, 8, 1, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(62, 'mollitia sunt eligendi', 'mollitia-sunt-eligendi', 8, 44.36, 4, 0, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(63, 'labore neque ea', 'labore-neque-ea', 6, 22.74, 8, 0, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(64, 'et illum quia', 'et-illum-quia', 9, 47.43, 0, 1, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(65, 'saepe consequuntur iure', 'saepe-consequuntur-iure', 3, 13.02, 5, 0, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(66, 'maxime minus suscipit', 'maxime-minus-suscipit', 5, 94.72, 8, 0, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(67, 'qui sequi numquam', 'qui-sequi-numquam', 3, 92.39, 6, 1, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(68, 'eius quam quos', 'eius-quam-quos', 2, 39.43, 1, 1, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(69, 'consequuntur a aut', 'consequuntur-a-aut', 2, 98.14, 4, 1, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(70, 'doloremque maiores magnam', 'doloremque-maiores-magnam', 6, 33.87, 9, 0, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(71, 'quia qui aut', 'quia-qui-aut', 10, 69.22, 5, 0, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(72, 'eum asperiores unde', 'eum-asperiores-unde', 10, 16.50, 0, 0, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(73, 'et nulla at', 'et-nulla-at', 2, 79.34, 0, 0, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(74, 'eos nostrum qui', 'eos-nostrum-qui', 8, 35.61, 5, 1, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(75, 'eum asperiores consectetur', 'eum-asperiores-consectetur', 6, 74.54, 8, 0, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(76, 'dicta ut omnis', 'dicta-ut-omnis', 6, 89.14, 7, 1, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(77, 'voluptatem expedita architecto', 'voluptatem-expedita-architecto', 9, 73.08, 5, 0, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(78, 'incidunt eos consequatur', 'incidunt-eos-consequatur', 6, 98.38, 2, 1, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(79, 'vel velit quo', 'vel-velit-quo', 4, 54.38, 8, 0, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(80, 'odio et similique', 'odio-et-similique', 4, 54.06, 4, 1, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(81, 'ab harum aliquid', 'ab-harum-aliquid', 10, 83.37, 9, 1, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(82, 'dolorum quaerat molestiae', 'dolorum-quaerat-molestiae', 4, 72.89, 6, 1, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(83, 'similique tenetur fuga', 'similique-tenetur-fuga', 10, 50.76, 1, 1, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(84, 'omnis assumenda vel', 'omnis-assumenda-vel', 2, 61.65, 5, 1, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(85, 'nihil eum voluptas', 'nihil-eum-voluptas', 7, 32.88, 2, 0, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(86, 'perferendis et similique', 'perferendis-et-similique', 2, 50.89, 1, 1, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(87, 'cumque aspernatur perspiciatis', 'cumque-aspernatur-perspiciatis', 2, 91.23, 7, 0, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(88, 'sed rerum asperiores', 'sed-rerum-asperiores', 2, 52.63, 7, 1, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(89, 'doloribus consequatur recusandae', 'doloribus-consequatur-recusandae', 1, 17.81, 7, 1, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(90, 'maiores in soluta', 'maiores-in-soluta', 8, 18.31, 8, 1, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(91, 'quis dolor laboriosam', 'quis-dolor-laboriosam', 7, 71.99, 4, 0, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(92, 'voluptatem sint dolor', 'voluptatem-sint-dolor', 8, 51.13, 7, 0, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(93, 'autem voluptatem quisquam', 'autem-voluptatem-quisquam', 9, 29.71, 4, 1, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(94, 'ducimus molestiae dolor', 'ducimus-molestiae-dolor', 2, 8.58, 4, 0, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(95, 'officiis porro laudantium', 'officiis-porro-laudantium', 1, 76.69, 3, 1, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(96, 'praesentium blanditiis fugiat', 'praesentium-blanditiis-fugiat', 6, 44.83, 6, 0, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(97, 'necessitatibus dolorem voluptas', 'necessitatibus-dolorem-voluptas', 9, 70.23, 3, 0, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(98, 'voluptas voluptatibus debitis', 'voluptas-voluptatibus-debitis', 4, 78.04, 0, 0, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(99, 'harum itaque ea', 'harum-itaque-ea', 2, 32.24, 7, 1, '2025-04-03 18:13:38', '2025-04-03 18:13:38'),
(100, 'animi est vel', 'animi-est-vel', 10, 60.70, 5, 0, '2025-04-03 18:13:38', '2025-04-03 18:13:38');



/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;