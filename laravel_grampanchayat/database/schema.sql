-- =============================================
-- डिजिटल ग्रामपंचायत Database Schema
-- MySQL / MariaDB
-- Charset: utf8mb4_unicode_ci
-- =============================================

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+05:30";

-- =============================================
-- Table: admins
-- =============================================
DROP TABLE IF EXISTS `admins`;
CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `role` enum('super_admin','editor') NOT NULL DEFAULT 'editor',
  `avatar` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `last_login` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admins_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: pages
-- =============================================
DROP TABLE IF EXISTS `pages`;
CREATE TABLE `pages` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content` longtext DEFAULT NULL,
  `featured_image` varchar(255) DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_keywords` varchar(255) DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pages_slug_unique` (`slug`),
  KEY `pages_created_by_foreign` (`created_by`),
  KEY `pages_updated_by_foreign` (`updated_by`),
  CONSTRAINT `pages_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `admins` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pages_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `admins` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: notices
-- =============================================
DROP TABLE IF EXISTS `notices`;
CREATE TABLE `notices` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `notice_date` date NOT NULL,
  `expiry_date` date DEFAULT NULL,
  `is_important` tinyint(1) NOT NULL DEFAULT 0,
  `show_in_ticker` tinyint(1) NOT NULL DEFAULT 1,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notices_created_by_foreign` (`created_by`),
  CONSTRAINT `notices_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `admins` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: news
-- =============================================
DROP TABLE IF EXISTS `news`;
CREATE TABLE `news` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `excerpt` text DEFAULT NULL,
  `content` longtext NOT NULL,
  `featured_image` varchar(255) DEFAULT NULL,
  `published_date` date NOT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `views` int(11) NOT NULL DEFAULT 0,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `news_slug_unique` (`slug`),
  KEY `news_created_by_foreign` (`created_by`),
  CONSTRAINT `news_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `admins` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: schemes
-- =============================================
DROP TABLE IF EXISTS `schemes`;
CREATE TABLE `schemes` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `eligibility` longtext DEFAULT NULL,
  `benefits` longtext DEFAULT NULL,
  `documents_required` longtext DEFAULT NULL,
  `how_to_apply` longtext DEFAULT NULL,
  `gr_link` varchar(500) DEFAULT NULL,
  `pdf_file` varchar(255) DEFAULT NULL,
  `featured_image` varchar(255) DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `schemes_slug_unique` (`slug`),
  KEY `schemes_created_by_foreign` (`created_by`),
  CONSTRAINT `schemes_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `admins` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: development_works
-- =============================================
DROP TABLE IF EXISTS `development_works`;
CREATE TABLE `development_works` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `budget` decimal(12,2) DEFAULT NULL,
  `spent_amount` decimal(12,2) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `completion_date` date DEFAULT NULL,
  `status` enum('planned','in_progress','completed','on_hold') NOT NULL DEFAULT 'planned',
  `progress_percentage` int(11) NOT NULL DEFAULT 0,
  `contractor_name` varchar(255) DEFAULT NULL,
  `featured_image` varchar(255) DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `development_works_created_by_foreign` (`created_by`),
  CONSTRAINT `development_works_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `admins` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: gallery_albums
-- =============================================
DROP TABLE IF EXISTS `gallery_albums`;
CREATE TABLE `gallery_albums` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `event_date` date DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `gallery_albums_slug_unique` (`slug`),
  KEY `gallery_albums_created_by_foreign` (`created_by`),
  CONSTRAINT `gallery_albums_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `admins` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: gallery_photos
-- =============================================
DROP TABLE IF EXISTS `gallery_photos`;
CREATE TABLE `gallery_photos` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `album_id` bigint(20) UNSIGNED NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `thumbnail_path` varchar(255) DEFAULT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `gallery_photos_album_id_foreign` (`album_id`),
  CONSTRAINT `gallery_photos_album_id_foreign` FOREIGN KEY (`album_id`) REFERENCES `gallery_albums` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: public_services
-- =============================================
DROP TABLE IF EXISTS `public_services`;
CREATE TABLE `public_services` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `process` longtext DEFAULT NULL,
  `documents_required` longtext DEFAULT NULL,
  `fees` varchar(255) DEFAULT NULL,
  `time_duration` varchar(255) DEFAULT NULL,
  `pdf_file` varchar(255) DEFAULT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `public_services_slug_unique` (`slug`),
  KEY `public_services_created_by_foreign` (`created_by`),
  CONSTRAINT `public_services_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `admins` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: contact_inquiries
-- =============================================
DROP TABLE IF EXISTS `contact_inquiries`;
CREATE TABLE `contact_inquiries` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` enum('new','read','replied','closed') NOT NULL DEFAULT 'new',
  `admin_notes` text DEFAULT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `replied_by` bigint(20) UNSIGNED DEFAULT NULL,
  `replied_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `contact_inquiries_replied_by_foreign` (`replied_by`),
  CONSTRAINT `contact_inquiries_replied_by_foreign` FOREIGN KEY (`replied_by`) REFERENCES `admins` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: settings
-- =============================================
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'text',
  `group` varchar(255) NOT NULL DEFAULT 'general',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: members
-- =============================================
DROP TABLE IF EXISTS `members`;
CREATE TABLE `members` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `designation` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `ward_number` varchar(255) DEFAULT NULL,
  `term_start` date DEFAULT NULL,
  `term_end` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: visitors
-- =============================================
DROP TABLE IF EXISTS `visitors`;
CREATE TABLE `visitors` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(255) NOT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `page_visited` varchar(255) DEFAULT NULL,
  `referrer` varchar(255) DEFAULT NULL,
  `visit_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `visitors_visit_date_ip_address_index` (`visit_date`,`ip_address`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Default Admin User
-- Email: admin@grampanchayat.gov.in
-- Password: Admin@123
-- =============================================
INSERT INTO `admins` (`name`, `email`, `password`, `phone`, `role`, `is_active`, `created_at`, `updated_at`) VALUES
('प्रशासक', 'admin@grampanchayat.gov.in', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543210', 'super_admin', 1, NOW(), NOW()),
('संपादक', 'editor@grampanchayat.gov.in', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543211', 'editor', 1, NOW(), NOW());

-- =============================================
-- Default Settings
-- =============================================
INSERT INTO `settings` (`key`, `value`, `type`, `group`, `created_at`, `updated_at`) VALUES
('site_name', 'ग्रामपंचायत आदर्शगाव', 'text', 'general', NOW(), NOW()),
('site_tagline', 'स्वच्छ गाव, समृद्ध गाव', 'text', 'general', NOW(), NOW()),
('contact_email', 'grampanchayat@gov.in', 'email', 'contact', NOW(), NOW()),
('contact_phone', '02XX-XXXXXX', 'text', 'contact', NOW(), NOW()),
('contact_address', 'ग्रामपंचायत कार्यालय, आदर्शगाव, तालुका - xyz, जिल्हा - xyz, महाराष्ट्र - 4XXXXX', 'textarea', 'contact', NOW(), NOW()),
('sarpanch_message', 'आपल्या गावाच्या सर्वांगीण विकासासाठी आम्ही कटिबद्ध आहोत.', 'textarea', 'content', NOW(), NOW()),
('footer_text', '© 2025 ग्रामपंचायत आदर्शगाव. सर्व हक्क राखीव.', 'text', 'content', NOW(), NOW()),
('meta_title', 'ग्रामपंचायत आदर्शगाव | अधिकृत वेबसाईट', 'text', 'seo', NOW(), NOW()),
('meta_description', 'ग्रामपंचायत आदर्शगाव ची अधिकृत वेबसाईट. येथे शासकीय योजना, विकासकामे, नोटीस आणि इतर माहिती मिळवा.', 'textarea', 'seo', NOW(), NOW());

SET FOREIGN_KEY_CHECKS=1;
