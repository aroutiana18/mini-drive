-- Mini Drive - Database Schema
-- Authentication is handled by LDAP.
-- File ownership is identified by the user's LDAP email address.

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

SET NAMES utf8mb4;

--
-- Database: `mini_drive`
--

CREATE DATABASE IF NOT EXISTS `mini_drive`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;

USE `mini_drive`;

-- --------------------------------------------------------
-- Table structure for table `files`
-- --------------------------------------------------------

CREATE TABLE `files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_email` varchar(100) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `file_size` int(11) DEFAULT 0,
  `mime_type` varchar(100) DEFAULT NULL,
  `is_folder` tinyint(1) DEFAULT 0,
  `parent_id` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT 0,
  `deleted_at` datetime DEFAULT NULL,
  `share_token` varchar(64) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),

  PRIMARY KEY (`id`),
  UNIQUE KEY `share_token` (`share_token`),
  KEY `parent_id` (`parent_id`),
  KEY `idx_user_parent` (`user_email`, `parent_id`, `is_deleted`),
  KEY `idx_search` (`user_email`, `original_name`),

  CONSTRAINT `files_ibfk_2`
    FOREIGN KEY (`parent_id`)
    REFERENCES `files` (`id`)
    ON DELETE CASCADE
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_general_ci;

COMMIT;