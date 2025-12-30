-- CMS Tables Setup
-- Database: ereal
-- These tables support Company Profile, About Us, CEO Message, Our Services, and Projects

USE `ereal`;


-- Table structure for table `admin`
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fname` varchar(100) NOT NULL,
  `lname` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample admin user
-- Email: admin@example.com
-- Password: admin123
INSERT INTO `admin` (`fname`, `lname`, `email`, `password`) VALUES
('Admin', 'User', 'admin@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- ============================================
-- Table: company_profile
-- Stores company information and settings
-- ============================================
CREATE TABLE IF NOT EXISTS `company_profile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comp_id` varchar(50) NOT NULL DEFAULT '999999',
  `company_name` varchar(255) NOT NULL,
  `company_logo` varchar(255) DEFAULT NULL,
  `company_background` varchar(255) DEFAULT NULL,
  `footer_image` varchar(255) DEFAULT NULL,
  `company_address` text DEFAULT NULL,
  `facebook_link` varchar(255) DEFAULT NULL,
  `youtube_link` varchar(255) DEFAULT NULL,
  `twitter_link` varchar(255) DEFAULT NULL,
  `instagram_link` varchar(255) DEFAULT NULL,
  `website_url` varchar(255) DEFAULT NULL,
  `email_address` varchar(255) NOT NULL,
  `uan` varchar(50) DEFAULT NULL,
  `mobile_1` varchar(50) DEFAULT NULL,
  `mobile_2` varchar(50) DEFAULT NULL,
  `ptcl_number` varchar(50) DEFAULT NULL,
  `longitude` varchar(50) DEFAULT NULL,
  `latitude` varchar(50) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `comp_id` (`comp_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default company profile
INSERT INTO `company_profile` (`comp_id`, `company_name`, `email_address`, `status`) 
VALUES ('999999', 'Your Company Name', 'info@company.com', 1)
ON DUPLICATE KEY UPDATE `updated_at` = CURRENT_TIMESTAMP;

-- ============================================
-- Table: about_us
-- Stores about us content
-- ============================================
CREATE TABLE IF NOT EXISTS `about_us` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `about_us_paragraph` text NOT NULL,
  `about_us_video` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default about us
INSERT INTO `about_us` (`about_us_paragraph`, `status`) 
VALUES ('<p>Welcome to our company. We are dedicated to providing excellent services.</p>', 1);

-- ============================================
-- Table: ceo_message
-- Stores CEO message content
-- ============================================
CREATE TABLE IF NOT EXISTS `ceo_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ceo_picture_1` varchar(255) DEFAULT NULL,
  `ceo_picture_2` varchar(255) DEFAULT NULL,
  `ceo_message_paragraph_1` text NOT NULL,
  `ceo_message_paragraph_2` text DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default CEO message
INSERT INTO `ceo_message` (`ceo_message_paragraph_1`, `status`) 
VALUES ('<p>Welcome message from our CEO.</p>', 1);

-- ============================================
-- Table: our_services
-- Stores services offered by the company
-- ============================================
CREATE TABLE IF NOT EXISTS `our_services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `service_title` varchar(255) NOT NULL,
  `service_description` text NOT NULL,
  `service_icon` varchar(100) DEFAULT NULL,
  `service_image` varchar(255) DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: projects
-- Stores project information
-- ============================================
CREATE TABLE IF NOT EXISTS `projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comp_id` varchar(50) NOT NULL DEFAULT '999999',
  `project_id` varchar(50) DEFAULT NULL,
  `project_name` varchar(255) NOT NULL,
  `project_map_thumbnail` varchar(255) DEFAULT NULL,
  `project_map_full` varchar(255) DEFAULT NULL,
  `project_payment_plan` varchar(255) DEFAULT NULL,
  `project_amenities` text DEFAULT NULL,
  `project_amenities_image` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `comp_id` (`comp_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: project_documents
-- Stores downloadable documents for projects
-- ============================================
CREATE TABLE IF NOT EXISTS `project_documents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `doc_id` varchar(50) DEFAULT NULL,
  `document_thumbnail` varchar(255) DEFAULT NULL,
  `document_name` varchar(255) NOT NULL,
  `document_file` varchar(255) DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`),
  CONSTRAINT `fk_project_documents` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: gallery_pictures
-- Stores gallery picture information
-- ============================================
CREATE TABLE IF NOT EXISTS `gallery_pictures` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `picture_id` varchar(50) DEFAULT NULL,
  `picture_title` varchar(255) NOT NULL,
  `picture_description` text DEFAULT NULL,
  `picture_file` varchar(255) NOT NULL,
  `picture_thumbnail` varchar(255) DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `picture_id` (`picture_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: gallery_videos
-- Stores gallery video information
-- ============================================
CREATE TABLE IF NOT EXISTS `gallery_videos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `video_id` varchar(50) DEFAULT NULL,
  `video_title` varchar(255) NOT NULL,
  `video_description` text DEFAULT NULL,
  `video_url` varchar(500) NOT NULL,
  `video_thumbnail` varchar(255) DEFAULT NULL,
  `video_embed_code` longtext DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `video_id` (`video_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: news_updates
-- Stores news and updates
-- ============================================
CREATE TABLE IF NOT EXISTS `news_updates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `news_id` varchar(50) DEFAULT NULL,
  `news_text` longtext NOT NULL,
  `news_image` varchar(255) DEFAULT NULL,
  `news_video` varchar(255) DEFAULT NULL,
  `youtube_link` varchar(500) DEFAULT NULL,
  `news_date` DATETIME DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `news_id` (`news_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================
-- Table: contact_messages
-- Stores contact form submissions
-- ============================================
CREATE TABLE IF NOT EXISTS `contact_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_id` varchar(50) DEFAULT NULL,
  `full_name` varchar(255) NOT NULL,
  `email_address` varchar(255) NOT NULL,
  `phone_number` varchar(50) DEFAULT NULL,
  `message_subject` varchar(255) NOT NULL,
  `message_body` longtext NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `contact_id` (`contact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Create uploads directory structure
-- ============================================
-- Note: You'll need to create these directories manually:
-- uploads/
-- ├── company/
-- ├── about/
-- ├── ceo/
-- ├── services/
-- ├── gallery/
-- │  ├── pictures/
-- │  └── videos/
-- └── projects/

-- Grant permissions (run as root/admin)
-- GRANT ALL PRIVILEGES ON ereal.* TO 'root'@'localhost';
-- FLUSH PRIVILEGES;


