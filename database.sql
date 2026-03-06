-- ============================================
-- Island Hotel (PHP) - Base de données MySQL
-- Compatible cPanel / phpMyAdmin
-- ============================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

-- ============================================
-- Table: hero_sections
-- ============================================
CREATE TABLE IF NOT EXISTS `hero_sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `subtitle` varchar(300) NOT NULL,
  `description` text,
  `background_image` varchar(500) DEFAULT NULL,
  `button_text` varchar(50) DEFAULT 'Book Now',
  `button_link` varchar(200) DEFAULT '/rooms.php',
  `is_active` tinyint(1) DEFAULT 1,
  `order_position` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Table: gallery_images
-- ============================================
CREATE TABLE IF NOT EXISTS `gallery_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `image` varchar(500) NOT NULL,
  `order_position` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Table: rooms
-- ============================================
CREATE TABLE IF NOT EXISTS `rooms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL UNIQUE,
  `description` text NOT NULL,
  `size` int(11) NOT NULL,
  `guests` int(11) NOT NULL,
  `beds` int(11) NOT NULL,
  `bed_type` varchar(100) DEFAULT 'King Bed',
  `price_per_night` decimal(10,2) NOT NULL,
  `image` varchar(500) NOT NULL,
  `is_featured` tinyint(1) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `order_position` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Table: room_images
-- ============================================
CREATE TABLE IF NOT EXISTS `room_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `room_id` int(11) NOT NULL,
  `image` varchar(500) NOT NULL,
  `caption` varchar(200) DEFAULT '',
  `order_position` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`room_id`) REFERENCES `rooms`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Table: amenities
-- ============================================
CREATE TABLE IF NOT EXISTS `amenities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `icon` varchar(100) NOT NULL,
  `description` text,
  `is_active` tinyint(1) DEFAULT 1,
  `order_position` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Table: services
-- ============================================
CREATE TABLE IF NOT EXISTS `services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL UNIQUE,
  `description` text NOT NULL,
  `image` varchar(500) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `order_position` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Table: testimonials
-- ============================================
CREATE TABLE IF NOT EXISTS `testimonials` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author` varchar(200) NOT NULL,
  `source` varchar(100) DEFAULT 'TripAdvisor',
  `content` text NOT NULL,
  `rating` int(11) DEFAULT 5,
  `image` varchar(500) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `order_position` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Table: site_settings (singleton)
-- ============================================
CREATE TABLE IF NOT EXISTS `site_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `site_name` varchar(100) DEFAULT 'CozyStay',
  `site_logo` varchar(500) DEFAULT NULL,
  `tagline` varchar(200) DEFAULT '',
  `check_in_time` varchar(10) DEFAULT '15:00',
  `check_out_time` varchar(10) DEFAULT '11:00',
  `min_age` int(11) DEFAULT 18,
  `currency` varchar(10) DEFAULT '$',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Table: contact_info (singleton)
-- ============================================
CREATE TABLE IF NOT EXISTS `contact_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `phone` varchar(50) NOT NULL DEFAULT '+41 22 345 67 88',
  `email` varchar(200) NOT NULL DEFAULT 'reservation@cozystay.com',
  `address` text NOT NULL,
  `facebook` varchar(500) DEFAULT '',
  `instagram` varchar(500) DEFAULT '',
  `twitter` varchar(500) DEFAULT '',
  `youtube` varchar(500) DEFAULT '',
  `google_maps_embed` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Table: bookings
-- ============================================
CREATE TABLE IF NOT EXISTS `bookings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `room_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(200) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `check_in` date NOT NULL,
  `check_out` date NOT NULL,
  `guests` int(11) NOT NULL,
  `message` text DEFAULT '',
  `status` enum('pending','confirmed','cancelled') DEFAULT 'pending',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`room_id`) REFERENCES `rooms`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Table: admin_users
-- ============================================
CREATE TABLE IF NOT EXISTS `admin_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `email` varchar(200) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Données initiales
-- ============================================

-- Admin par défaut (mot de passe: admin123)
INSERT INTO `admin_users` (`username`, `password`, `email`) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@cozystay.com');

-- Paramètres du site
INSERT INTO `site_settings` (`site_name`, `tagline`, `check_in_time`, `check_out_time`, `min_age`, `currency`) VALUES
('CozyStay', 'Boutique Private Island Resort', '15:00', '11:00', 18, '$');

-- Informations de contact
INSERT INTO `contact_info` (`phone`, `email`, `address`, `facebook`, `instagram`, `twitter`) VALUES
('+41 22 345 67 88', 'reservation@cozystay.com', '73120 Courchevel 1850, France', '#', '#', '#');

-- Équipements
INSERT INTO `amenities` (`name`, `icon`, `description`, `is_active`, `order_position`) VALUES
('Airport Pick-up', 'fas fa-plane-arrival', 'Service de transfert aéroport', 1, 1),
('Housekeeping', 'fas fa-broom', 'Service de ménage quotidien', 1, 2),
('Free WiFi', 'fas fa-wifi', 'WiFi haut débit gratuit', 1, 3),
('Laundry', 'fas fa-tshirt', 'Service de blanchisserie', 1, 4),
('Breakfast in Bed', 'fas fa-coffee', 'Petit-déjeuner servi en chambre', 1, 5),
('Swimming Pool', 'fas fa-swimming-pool', 'Piscine privée avec vue', 1, 6);

-- Hero section par défaut
INSERT INTO `hero_sections` (`title`, `subtitle`, `description`, `is_active`, `order_position`) VALUES
('Boutique Private Island Resort', 'The seaside haven of warmth, tranquility and restoration', '<p>Bienvenue dans notre resort 5 étoiles, un paradis tropical vous attend.</p>', 1, 0);

-- Témoignage par défaut
INSERT INTO `testimonials` (`author`, `source`, `content`, `rating`, `is_active`, `order_position`) VALUES
('Sarah & James', 'TripAdvisor', 'An absolutely magical experience. The resort exceeded all our expectations with its stunning views, impeccable service, and attention to every detail. We can\'t wait to return!', 5, 1, 0);

COMMIT;
