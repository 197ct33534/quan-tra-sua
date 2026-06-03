-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: tra_sua_shop
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `tra_sua_shop`
--

/*!40000 DROP DATABASE IF EXISTS `tra_sua_shop`*/;

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `tra_sua_shop` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */;

USE `tra_sua_shop`;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Trà sữa','tra-sua','Trà sữa thơm béo, nhiều topping hấp dẫn',0,1,'2026-06-03 00:08:34','2026-06-03 00:08:34'),(2,'Bánh tráng trộn','banh-trang-tron','Bánh tráng trộn chua cay đậm đà vị Sài Gòn',1,1,'2026-06-03 00:08:34','2026-06-03 00:08:34'),(3,'Bánh tráng nướng','banh-trang-nuong','Bánh tráng nướng giòn rụm kiểu Đà Lạt',2,1,'2026-06-03 00:08:34','2026-06-03 00:08:34');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_06_02_045543_create_categories_table',1),(5,'2026_06_02_045544_create_orders_table',1),(6,'2026_06_02_045544_create_products_table',1),(7,'2026_06_02_045544_create_toppings_table',1),(8,'2026_06_02_045545_create_order_items_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `unit_price` decimal(10,0) NOT NULL,
  `quantity` int(11) NOT NULL,
  `size` varchar(255) DEFAULT NULL,
  `toppings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`toppings`)),
  `options_fee` decimal(10,0) NOT NULL DEFAULT 0,
  `line_total` decimal(10,0) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_product_id_foreign` (`product_id`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (1,1,1,'Trà sữa truyền thống',30000,2,'M','[]',5000,60000,'2026-06-03 00:34:36','2026-06-03 00:34:36');
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_code` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `note` text DEFAULT NULL,
  `subtotal` decimal(10,0) NOT NULL DEFAULT 0,
  `shipping_fee` decimal(10,0) NOT NULL DEFAULT 0,
  `total` decimal(10,0) NOT NULL DEFAULT 0,
  `payment_method` varchar(255) NOT NULL DEFAULT 'cod',
  `status` enum('pending','confirmed','preparing','delivering','completed','cancelled') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_code_unique` (`order_code`),
  KEY `orders_user_id_foreign` (`user_id`),
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,'TS260603GW03',2,'Khach Test','0900000000','123 Test St','giao nhanh',60000,15000,75000,'cod','confirmed','2026-06-03 00:34:36','2026-06-03 00:45:37');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,0) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 1,
  `has_options` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_slug_unique` (`slug`),
  KEY `products_category_id_foreign` (`category_id`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,1,'Trà sữa truyền thống','tra-sua-truyen-thong','Vị trà sữa nguyên bản đậm đà.',25000,NULL,1,1,0,'2026-06-03 00:08:34','2026-06-03 00:08:34'),(2,1,'Trà sữa trân châu đường đen','tra-sua-tran-chau-duong-den','Trà sữa cùng trân châu đường đen dẻo thơm.',32000,NULL,1,1,1,'2026-06-03 00:08:34','2026-06-03 00:08:34'),(3,1,'Trà sữa matcha','tra-sua-matcha','Matcha Nhật Bản hòa quyện sữa tươi.',35000,NULL,1,1,2,'2026-06-03 00:08:34','2026-06-03 00:08:34'),(4,1,'Trà sữa khoai môn','tra-sua-khoai-mon','Khoai môn bùi béo, màu tím đẹp mắt.',33000,NULL,1,1,3,'2026-06-03 00:08:34','2026-06-03 00:08:34'),(5,1,'Hồng trà sữa','hong-tra-sua','Hồng trà đậm vị kết hợp sữa.',28000,NULL,1,1,4,'2026-06-03 00:08:34','2026-06-03 00:08:34'),(6,2,'Bánh tráng trộn đặc biệt','banh-trang-tron-dac-biet','Đầy đủ topping: khô bò, trứng cút, xoài, rau răm.',25000,NULL,1,0,0,'2026-06-03 00:08:34','2026-06-03 00:08:34'),(7,2,'Bánh tráng trộn sa tế','banh-trang-tron-sa-te','Cay nồng vị sa tế thơm lừng.',20000,NULL,1,0,1,'2026-06-03 00:08:34','2026-06-03 00:08:34'),(8,2,'Bánh tráng trộn bơ','banh-trang-tron-bo','Béo ngậy vị bơ hành phi.',22000,NULL,1,0,2,'2026-06-03 00:08:34','2026-06-03 00:08:34'),(9,2,'Bánh tráng trộn muối tôm','banh-trang-tron-muoi-tom','Đậm đà vị muối tôm Tây Ninh.',18000,NULL,1,0,3,'2026-06-03 00:08:34','2026-06-03 00:08:34'),(10,3,'Bánh tráng nướng thập cẩm','banh-trang-nuong-thap-cam','Trứng, xúc xích, phô mai, hành, pa-tê.',30000,NULL,1,0,0,'2026-06-03 00:08:34','2026-06-03 00:08:34'),(11,3,'Bánh tráng nướng trứng','banh-trang-nuong-trung','Giòn rụm với trứng và hành lá.',20000,NULL,1,0,1,'2026-06-03 00:08:34','2026-06-03 00:08:34'),(12,3,'Bánh tráng nướng phô mai','banh-trang-nuong-pho-mai','Phô mai kéo sợi béo ngậy.',28000,NULL,1,0,2,'2026-06-03 00:08:34','2026-06-03 00:08:34'),(13,3,'Bánh tráng nướng bò khô','banh-trang-nuong-bo-kho','Thêm khô bò đậm đà.',32000,NULL,1,0,3,'2026-06-03 00:08:34','2026-06-03 00:08:34');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('2htgtKxr8ZR4EpPXrqouqOdJf7WWxJnVWmv9AueC',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.1882','YTozOntzOjY6Il90b2tlbiI7czo0MDoiQzVqWFUxNGYyVGZNdXhDN2x2YVlxTHgzTjJqVGhhT3c3OVpNUEdCYyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kYW5nLW5oYXAiO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1780470567),('3XJ2tUdbGeINrurLFUs4W5Y09r7IFzRx2NIj3m3N',1,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.1882','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTnJ5UzBHalY1VjdIZGlSelhldWE5aWd2UTBIdUMwV2R4YW5jam9YSCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kYW5nLW5oYXAiO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=',1780471331),('5HDLJxLgL9WFU9f38qQ2wVeNQnMFsUFdF2u6D74m',2,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.1882','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRWVVSUI1U3NjV0xmRjN5NkZjT2RLTTNwTFJmN05vUEc4WWVXNEpidyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbiI7czo1OiJyb3V0ZSI7czoxNToiYWRtaW4uZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mjt9',1780473296),('aFKtvm6MG3NJcE5UwsOlKdAF5IVYhoYOM3lqjBPk',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.1882','YTozOntzOjY6Il90b2tlbiI7czo0MDoiQjRQb1F1NUExbXdiRXI4Wm9EOEhvSnpuV2U4bEEzQnlCYnRxbUVPVyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kYW5nLWt5IjtzOjU6InJvdXRlIjtzOjg6InJlZ2lzdGVyIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1780470568),('ExV3BZjwkS1PpochZSppIf5g2YPRvpsTGYLxX83U',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.1882','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMWFOUE43U1MxOEtacEI4dDFXcjgxTExqUFBPNE1tdGxYSk03bEVVbCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9naW8taGFuZyI7czo1OiJyb3V0ZSI7czoxMDoiY2FydC5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1780470567),('L3ijaTNPRHFbmt9bQBXnSvKzNyrTQ8AsfTzLxJdh',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.1882','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUjFIRnhLdnh1YnJHdHBFNU5pYWhwcWNEdzNyRjVUVmQ2VU5hQ1dRNCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1780470566),('LKMLy5gxR4r0HJazBP9PQVLFOdjDClIys7CmxSaT',2,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.1882','YTo1OntzOjY6Il90b2tlbiI7czo0MDoibUpQS3hRdFJmZkxhTGVhRVJRcXRqcjVZeHlBVEM3Vk5qRkxtSzJ0ViI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kYW5nLW5oYXAiO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YToxOntpOjA7czo3OiJzdWNjZXNzIjt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO3M6Nzoic3VjY2VzcyI7czoyNzoixJDEg25nIG5o4bqtcCB0aMOgbmggY8O0bmchIjt9',1780471330),('Ms1B4QcApEtetUtav00AdZsUmbXebBDkujId1rOc',1,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.1882','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiVnVEMERzVFpoUG5IVWd0Q3RRUmFBTXBKVUpZakJZcm4wOE16UlJVSyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9vcmRlcnMiO3M6NToicm91dGUiO3M6MTg6ImFkbWluLm9yZGVycy5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==',1780471681),('OYWXHv0WQuf6fuUuw1vNdKSvv4utSvQfIIaQ2pAB',2,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.1882','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiVVZ6SnZFeGRGYW5iSEtqRlhUelY3OWdjcldFZWxRQTNFWXh2Y1pEeSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC90aGFuaC10b2FuIjtzOjU6InJvdXRlIjtzOjE0OiJjaGVja291dC5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjE6e2k6MDtzOjc6InN1Y2Nlc3MiO31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7czo3OiJzdWNjZXNzIjtzOjUwOiLEkOG6t3QgaMOgbmcgdGjDoG5oIGPDtG5nISBNw6MgxJHGoW46IFRTMjYwNjAzR1cwMyI7fQ==',1780472076),('PDCjXwlX6xJwWYahvCcMWGYErMnlQAPJ5HF9eTSR',2,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.1882','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZEFBdUpqWHlsMG42SVhRYW9rU3M3cEV1aVRSdmdBMTZzbm1SM1VHVyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC90aGFuaC10b2FuIjtzOjU6InJvdXRlIjtzOjE0OiJjaGVja291dC5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7fQ==',1780471856),('qpSNlz0MFoMxoMKxUUwSZcTqqACp3bUCavQFmcbK',2,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.1882','YTo0OntzOjY6Il90b2tlbiI7czo0MDoieEdnWldibG9LT2NFMnQ5R1JZaXlRbXlzcVg5eW9SRmxzaWcwbnNqWSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kb24taGFuZy9UUzI2MDYwM0dXMDMiO3M6NToicm91dGUiO3M6MTE6Im9yZGVycy5zaG93Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mjt9',1780472736),('rztbQKk1KnBFoPxMYNxTITm2YR8SxwMgtnQkXinN',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.1882','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUFpMUFRxTHdpZXhLQ1VMY2NqWXE4NGNVaHdVS0VMUWFDOVJZVGhqYyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1780470567),('SR3PBBdpAeGKZcLcN8fLP0aDb24diptNHH7A6OtR',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.1882','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRnB3MWw2cVM5dlJ0MHViN2NwdzJOanZTblBld00wY01qRkRabE5kUyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozMjoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL3RoYW5oLXRvYW4iO31zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czozMjoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL3RoYW5oLXRvYW4iO3M6NToicm91dGUiO3M6MTQ6ImNoZWNrb3V0LmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1780470568),('TRgf7KDLsHjy0bncFl8uVwpm0LWhpceNdkNf7XwC',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.1882','YTozOntzOjY6Il90b2tlbiI7czo0MDoiU0Z3V1VpSGlieVZ3UmpSM2RZeFhudE81M2l1aEpMNlREYVRZUGF4SyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tZW51IjtzOjU6InJvdXRlIjtzOjQ6Im1lbnUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1780470567),('tXoo3qftybdpXMzZtGPLvf0ZK3qobvj8ljq2W3vo',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.1882','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSXZNZmRJeFFwb1BTeGtiREoxR3ZZTTZMbFNqV3RESDl4bm1OUVNqeCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozMDoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2Rvbi1oYW5nIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kb24taGFuZyI7czo1OiJyb3V0ZSI7czoxMjoib3JkZXJzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1780470568),('UbllAKT10KmL9OLqDDSDdk8nsJFk8anl8kzp6IDK',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.1882','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiS2R2bThPWlc5alRwNzZiYURjMTZYbHFzeW9HWkNoOTVSUkNrcFY5VSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyNzoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbiI7czo1OiJyb3V0ZSI7czoxNToiYWRtaW4uZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1780470568),('x7dOjKuRylSvd2xGa2eabrEMGMSZPA3qJgkMpDLZ',2,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.1882','YTo0OntzOjY6Il90b2tlbiI7czo0MDoidTNyWG5GeHVIeWtvV0ttUVpwQkpRSng4cm5xQkFJV0NpU3BiOUt3diI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbiI7czo1OiJyb3V0ZSI7czoxNToiYWRtaW4uZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mjt9',1780471679),('XjTSF78Q4hgmPpI1yN4rIQJWJfBxmJUPKgnegcx0',1,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.1882','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiNWczTWZobTNiWFlsQlRhWlliUTlCMU52cnc2WjB2Nkx5eTRObTNrWSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9vcmRlcnMvMSI7czo1OiJyb3V0ZSI7czoxNzoiYWRtaW4ub3JkZXJzLnNob3ciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YToxOntpOjA7czo3OiJzdWNjZXNzIjt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6Nzoic3VjY2VzcyI7czo0NDoixJDDoyBj4bqtcCBuaOG6rXQgdHLhuqFuZyB0aMOhaSDEkcahbiBow6BuZy4iO30=',1780472737);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `toppings`
--

DROP TABLE IF EXISTS `toppings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `toppings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,0) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `toppings`
--

LOCK TABLES `toppings` WRITE;
/*!40000 ALTER TABLE `toppings` DISABLE KEYS */;
INSERT INTO `toppings` VALUES (1,'Trân châu đen',5000,1,'2026-06-03 00:08:34','2026-06-03 00:08:34'),(2,'Trân châu trắng',5000,1,'2026-06-03 00:08:34','2026-06-03 00:08:34'),(3,'Thạch phô mai',7000,1,'2026-06-03 00:08:34','2026-06-03 00:08:34'),(4,'Pudding trứng',8000,1,'2026-06-03 00:08:34','2026-06-03 00:08:34'),(5,'Kem cheese',10000,1,'2026-06-03 00:08:34','2026-06-03 00:08:34'),(6,'Thạch dừa',5000,1,'2026-06-03 00:08:34','2026-06-03 00:08:34');
/*!40000 ALTER TABLE `toppings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `role` enum('customer','admin') NOT NULL DEFAULT 'customer',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Quản trị viên','admin@trasua.test','0900000001','Cửa hàng Trà Sữa','admin',NULL,'$2y$12$SzX3W.EyHGKPni3XXfK8ou.5JmO/KQlVs46W9bm/I.HsJu11o88Hm',NULL,'2026-06-03 00:08:34','2026-06-03 00:08:34'),(2,'Khách Demo','khach@trasua.test','0900000002','123 Đường ABC, Quận 1','customer',NULL,'$2y$12$j0XGZx3HnG6vjzCJWwmxduV0b.BiA9dxcNxPwkBun./Jv87I1S7MS',NULL,'2026-06-03 00:08:34','2026-06-03 00:08:34');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-06-03 16:14:19
