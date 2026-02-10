-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 10, 2026 at 08:10 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `aibub`
--

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `message`, `is_read`, `created_at`) VALUES
(1, 1, 'Your video is ready', 0, '2026-01-30 12:40:24'),
(2, 1, 'Subscription upgraded successfully', 0, '2026-01-30 12:40:24'),
(3, 1, 'Processing completed', 1, '2026-01-30 12:40:24'),
(4, 1, 'Welcome to AI Dub!', 0, '2026-01-30 12:59:57'),
(5, 2, 'Welcome to AI Dub!', 0, '2026-01-30 12:59:57'),
(6, 3, 'Welcome to AI Dub!', 0, '2026-01-30 12:59:57'),
(7, 4, 'Welcome to AI Dub!', 0, '2026-01-30 12:59:57'),
(8, 1, '🎉 Your video dubbing is complete!', 0, '2026-02-05 18:59:26'),
(9, 1, '📥 Your video is ready for download', 0, '2026-02-05 18:59:26'),
(10, 1, '🚀 Welcome to AI Dub!', 1, '2026-02-05 18:59:26'),
(11, 1, 'Your dubbed video is ready 🎬', 0, '2026-02-06 14:39:32'),
(12, 1, 'Your dubbed video is ready 🎬', 0, '2026-02-06 14:39:34'),
(13, 1, 'Your dubbed video is ready 🎬', 0, '2026-02-06 14:39:35'),
(14, 1, 'Audio extracted successfully 🎧', 0, '2026-02-06 14:52:24'),
(15, 1, 'Speech converted to text ✍️', 0, '2026-02-06 14:52:48'),
(16, 1, 'Emotion tuning saved 🎚', 0, '2026-02-06 14:54:06'),
(17, 1, 'Voice tuning saved 🎤', 0, '2026-02-06 14:54:39'),
(18, 1, 'AI voice generated 🔊', 0, '2026-02-06 14:54:53'),
(19, 1, 'Your dubbed video is ready 🎬', 0, '2026-02-06 14:54:57'),
(20, 1, 'Audio extracted successfully 🎧', 0, '2026-02-06 14:56:40'),
(21, 1, 'Speech converted to text ✍️', 0, '2026-02-06 14:57:08'),
(22, 1, 'Audio extracted successfully 🎧', 0, '2026-02-06 14:59:17'),
(23, 1, 'Audio extracted successfully 🎧', 0, '2026-02-06 15:01:34'),
(24, 1, 'Speech converted to text ✍️', 0, '2026-02-06 15:02:55'),
(25, 1, 'Translation completed 🌍', 0, '2026-02-06 15:03:03'),
(26, 1, 'Emotion tuning saved 🎚', 0, '2026-02-06 15:04:44'),
(27, 1, 'Emotion tuning saved 🎚', 0, '2026-02-06 15:05:54'),
(28, 1, 'Emotion tuning saved 🎚', 0, '2026-02-06 15:06:19'),
(29, 1, 'Voice tuning saved 🎤', 0, '2026-02-06 15:08:19'),
(30, 1, 'Emotion tuning saved 🎚', 0, '2026-02-06 15:08:35'),
(31, 1, 'AI voice generated 🔊', 0, '2026-02-06 15:08:37'),
(32, 1, 'Voice tuning saved 🎤', 0, '2026-02-06 15:08:39'),
(33, 1, 'Emotion tuning saved 🎚', 0, '2026-02-06 15:08:39'),
(34, 1, 'AI voice generated 🔊', 0, '2026-02-06 15:08:41'),
(35, 1, 'Voice tuning saved 🎤', 0, '2026-02-06 15:10:34'),
(36, 1, 'Your dubbed video is ready 🎬', 0, '2026-02-06 15:10:52'),
(37, 1, 'Voice tuning saved 🎤', 0, '2026-02-06 15:10:55'),
(38, 1, 'Voice tuning saved 🎤', 0, '2026-02-06 15:10:59'),
(39, 1, 'Video uploaded successfully 🎬', 0, '2026-02-07 06:54:03'),
(40, 1, 'Video uploaded successfully 🎬', 0, '2026-02-07 07:13:26'),
(41, 1, 'Audio extracted successfully 🎧', 0, '2026-02-07 07:13:32'),
(42, 1, 'Audio extracted successfully 🎧', 0, '2026-02-07 07:16:49'),
(43, 1, 'Speech converted to text ✍️', 0, '2026-02-07 07:17:21'),
(44, 1, 'Video uploaded successfully 🎬', 0, '2026-02-07 07:21:33'),
(45, 1, 'Audio extracted successfully 🎧', 0, '2026-02-07 07:21:53');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `original_language` varchar(50) DEFAULT 'Telugu',
  `target_language` varchar(50) DEFAULT 'English',
  `status` enum('uploaded','extracting_audio','transcribing','translating','detecting_emotion','generating_voice','completed','failed') DEFAULT 'uploaded',
  `video_path` text DEFAULT NULL,
  `output_video_path` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `user_id`, `title`, `original_language`, `target_language`, `status`, `video_path`, `output_video_path`, `created_at`, `updated_at`) VALUES
(1, 4, 'My AI Dub Video', 'Telugu', 'English', 'uploaded', '../../uploads/videos/1769233249_Screenrecording_20251204_121243.mp4', NULL, '2026-01-24 05:40:49', NULL),
(3, 4, 'My AI Dub Video', 'Telugu', 'English', 'completed', '../../uploads/videos/1769240074_Screenrecording_20251204_121243.mp4', 'uploads/output/1769236514_Screenrecording_20251204_121243.mp4', '2026-01-24 07:34:34', '2026-01-24 13:05:00'),
(4, 4, 'AI Dub Video', 'Telugu', 'English', 'completed', '../../uploads/videos/1769435884_Screenrecording_20251204_121243.mp4', 'uploads/output/1769236514_Screenrecording_20251204_121243.mp4', '2026-01-26 13:58:04', NULL),
(5, 4, 'AI Dub Video', 'Telugu', 'English', 'completed', 'uploads/videos/1769437146_Screenrecording_20251204_121243.mp4', 'uploads/output/1769236514_Screenrecording_20251204_121243.mp4', '2026-01-26 14:19:06', NULL),
(6, 4, 'AI Dub Video', 'Telugu', 'English', 'uploaded', 'uploads/videos/1769595418_Screenrecording_20251204_121243.mp4', NULL, '2026-01-28 10:16:58', NULL),
(7, 4, 'AI Dub', 'Telugu', 'English', 'uploaded', 'uploads/videos/1769601322_final.mp4', NULL, '2026-01-28 11:55:22', NULL),
(8, 4, 'AI Dub', 'Telugu', 'English', 'uploaded', 'uploads/videos/1769603587_final.mp4', NULL, '2026-01-28 12:33:07', NULL),
(9, 4, 'AI Dub', 'Telugu', 'English', 'uploaded', 'uploads/videos/1769605261_f1.mp4', NULL, '2026-01-28 13:01:01', NULL),
(10, 4, 'AI Dub', 'Telugu', 'English', 'uploaded', 'uploads/videos/1769609637_f1.mp4', NULL, '2026-01-28 14:13:57', NULL),
(11, 4, 'AI Dub', 'Telugu', 'English', 'uploaded', 'uploads/videos/1769621578_f1.mp4', NULL, '2026-01-28 17:32:58', NULL),
(12, 4, 'AI Dub', 'Telugu', 'English', 'uploaded', 'uploads/videos/1769622548_f1.mp4', NULL, '2026-01-28 17:49:08', NULL),
(13, 4, 'AI Dub', 'Telugu', 'English', 'uploaded', 'uploads/videos/1769663470_f1.mp4', NULL, '2026-01-29 05:11:10', NULL),
(14, 4, 'AI Dub', 'Telugu', 'English', 'uploaded', 'uploads/videos/1769693638_f1.mp4', NULL, '2026-01-29 13:33:58', NULL),
(15, 4, 'AI Dub', 'Telugu', 'English', 'uploaded', 'uploads/videos/1769696686_f1.mp4', NULL, '2026-01-29 14:24:46', NULL),
(16, 4, 'AI Dub', 'Telugu', 'English', 'uploaded', 'uploads/videos/1769749974_f1.mp4', NULL, '2026-01-30 05:12:54', NULL),
(17, 4, 'AI Dub', 'Telugu', 'English', 'completed', 'uploads/videos/1769765676_f1.mp4', 'uploads/output/1769765676_f1.mp4', '2026-01-30 09:34:36', NULL),
(18, 1, 'My First Project', 'Telugu', 'English', 'uploaded', 'uploads/videos/1770295804_upload.mp4', NULL, '2026-02-05 12:50:04', NULL),
(19, 1, 'My First Project', 'Telugu', 'English', 'uploaded', 'uploads/videos/1770298471_upload.mp4', NULL, '2026-02-05 13:34:31', NULL),
(20, 1, 'My First Project', 'Telugu', 'English', 'uploaded', 'uploads/videos/1770298626_upload.mp4', NULL, '2026-02-05 13:37:06', NULL),
(21, 1, 'My First Project', 'Telugu', 'English', 'uploaded', 'uploads/videos/1770302530_upload.mp4', NULL, '2026-02-05 14:42:10', NULL),
(22, 1, 'My First Project', 'Telugu', 'English', 'uploaded', 'uploads/videos/1770303261_upload.mp4', NULL, '2026-02-05 14:54:21', NULL),
(23, 1, 'My First Project', 'Telugu', 'English', 'uploaded', 'uploads/videos/1770307504_upload.mp4', NULL, '2026-02-05 16:05:04', NULL),
(24, 1, 'My First Project', 'Telugu', 'English', 'uploaded', 'uploads/videos/1770313447_upload.mp4', NULL, '2026-02-05 17:44:07', NULL),
(25, 1, 'My First Project', 'Telugu', 'English', 'uploaded', 'uploads/videos/1770314061_upload.mp4', NULL, '2026-02-05 17:54:21', NULL),
(26, 1, 'My First Project', 'Telugu', 'English', 'uploaded', 'uploads/videos/1770315140_upload.mp4', NULL, '2026-02-05 18:12:20', NULL),
(27, 1, 'My First Project', 'Telugu', 'English', 'uploaded', 'uploads/videos/1770316648_upload.mp4', NULL, '2026-02-05 18:37:28', NULL),
(28, 1, 'My First Project', 'Telugu', 'English', 'uploaded', 'uploads/videos/1770318066_upload.mp4', NULL, '2026-02-05 19:01:06', NULL),
(29, 1, 'My First Project', 'Telugu', 'English', 'uploaded', 'uploads/videos/1770318592_upload.mp4', NULL, '2026-02-05 19:09:52', NULL),
(30, 1, 'My First Project', 'Telugu', 'English', 'uploaded', 'uploads/videos/1770385115_upload.mp4', NULL, '2026-02-06 13:38:35', NULL),
(31, 1, 'My First Project', 'Telugu', 'English', 'uploaded', 'uploads/videos/1770386178_upload.mp4', NULL, '2026-02-06 13:56:18', NULL),
(32, 1, 'My First Project', 'Telugu', 'English', 'uploaded', 'uploads/videos/1770387016_upload.mp4', NULL, '2026-02-06 14:10:16', NULL),
(33, 1, 'My First Project', 'Telugu', 'English', 'uploaded', 'uploads/videos/1770387056_upload.mp4', NULL, '2026-02-06 14:10:56', NULL),
(34, 1, 'My First Project', 'Telugu', 'English', 'uploaded', 'uploads/videos/1770387567_upload.mp4', NULL, '2026-02-06 14:19:27', NULL),
(35, 1, 'My First Project', 'Telugu', 'English', 'uploaded', 'uploads/videos/1770388526_upload.mp4', NULL, '2026-02-06 14:35:26', NULL),
(36, 1, 'My First Project', 'Telugu', 'English', 'uploaded', 'uploads/videos/1770389363_upload.mp4', NULL, '2026-02-06 14:49:23', NULL),
(37, 1, 'My First Project', 'Telugu', 'English', 'uploaded', 'uploads/videos/1770389782_upload.mp4', NULL, '2026-02-06 14:56:22', NULL),
(38, 1, 'My First Project', 'Telugu', 'English', 'uploaded', 'uploads/videos/1770389950_upload.mp4', NULL, '2026-02-06 14:59:10', NULL),
(39, 1, 'My First Project', 'Telugu', 'English', 'uploaded', 'uploads/videos/1770446586_upload.mp4', NULL, '2026-02-07 06:43:06', NULL),
(40, 1, 'My First Project', 'Telugu', 'English', 'uploaded', 'uploads/videos/1770446600_upload.mp4', NULL, '2026-02-07 06:43:20', NULL),
(41, 1, 'My First Project', 'Telugu', 'English', 'uploaded', 'uploads/videos/1770446607_upload.mp4', NULL, '2026-02-07 06:43:27', NULL),
(42, 1, 'My First Project', 'Telugu', 'English', 'uploaded', 'uploads/videos/1770447235_upload.mp4', NULL, '2026-02-07 06:53:55', NULL),
(43, 1, 'My First Project', 'Telugu', 'English', 'uploaded', 'uploads/videos/1770448399_upload.mp4', NULL, '2026-02-07 07:13:19', NULL),
(44, 1, 'My First Project', 'Telugu', 'English', 'uploaded', 'uploads/videos/1770448887_upload.mp4', NULL, '2026-02-07 07:21:27', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `project_history`
--

CREATE TABLE `project_history` (
  `id` int(11) NOT NULL,
  `project_id` int(11) DEFAULT NULL,
  `action` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `project_history`
--

INSERT INTO `project_history` (`id`, `project_id`, `action`, `created_at`) VALUES
(1, 1, 'Video Uploaded', '2026-01-30 12:43:40'),
(2, 1, 'Audio Extracted', '2026-01-30 12:43:40'),
(3, 1, 'Translation Completed', '2026-01-30 12:43:40'),
(4, 1, 'Voice Generated', '2026-01-30 12:43:40'),
(5, 1, 'Video Uploaded', '2026-01-30 12:51:08'),
(6, 1, 'Video Uploaded', '2026-01-30 12:55:38'),
(7, 3, 'Video Uploaded', '2026-01-30 12:55:38'),
(8, 4, 'Video Uploaded', '2026-01-30 12:55:38'),
(9, 5, 'Video Uploaded', '2026-01-30 12:55:38'),
(10, 6, 'Video Uploaded', '2026-01-30 12:55:38'),
(11, 7, 'Video Uploaded', '2026-01-30 12:55:38'),
(12, 8, 'Video Uploaded', '2026-01-30 12:55:38'),
(13, 9, 'Video Uploaded', '2026-01-30 12:55:38'),
(14, 10, 'Video Uploaded', '2026-01-30 12:55:38'),
(15, 11, 'Video Uploaded', '2026-01-30 12:55:38'),
(16, 12, 'Video Uploaded', '2026-01-30 12:55:38'),
(17, 13, 'Video Uploaded', '2026-01-30 12:55:38'),
(18, 14, 'Video Uploaded', '2026-01-30 12:55:38'),
(19, 15, 'Video Uploaded', '2026-01-30 12:55:38'),
(20, 16, 'Video Uploaded', '2026-01-30 12:55:38'),
(21, 17, 'Video Uploaded', '2026-01-30 12:55:38'),
(37, 1, 'Audio Extracted', '2026-01-30 12:55:38'),
(38, 3, 'Audio Extracted', '2026-01-30 12:55:38'),
(39, 4, 'Audio Extracted', '2026-01-30 12:55:38'),
(40, 5, 'Audio Extracted', '2026-01-30 12:55:38'),
(41, 6, 'Audio Extracted', '2026-01-30 12:55:38'),
(42, 7, 'Audio Extracted', '2026-01-30 12:55:38'),
(43, 8, 'Audio Extracted', '2026-01-30 12:55:38'),
(44, 9, 'Audio Extracted', '2026-01-30 12:55:38'),
(45, 10, 'Audio Extracted', '2026-01-30 12:55:38'),
(46, 11, 'Audio Extracted', '2026-01-30 12:55:38'),
(47, 12, 'Audio Extracted', '2026-01-30 12:55:38'),
(48, 13, 'Audio Extracted', '2026-01-30 12:55:38'),
(49, 14, 'Audio Extracted', '2026-01-30 12:55:38'),
(50, 15, 'Audio Extracted', '2026-01-30 12:55:38'),
(51, 16, 'Audio Extracted', '2026-01-30 12:55:38'),
(52, 17, 'Audio Extracted', '2026-01-30 12:55:38'),
(68, 1, 'Translation Completed', '2026-01-30 12:55:38'),
(69, 3, 'Translation Completed', '2026-01-30 12:55:38'),
(70, 4, 'Translation Completed', '2026-01-30 12:55:38'),
(71, 5, 'Translation Completed', '2026-01-30 12:55:38'),
(72, 6, 'Translation Completed', '2026-01-30 12:55:38'),
(73, 7, 'Translation Completed', '2026-01-30 12:55:38'),
(74, 8, 'Translation Completed', '2026-01-30 12:55:38'),
(75, 9, 'Translation Completed', '2026-01-30 12:55:38'),
(76, 10, 'Translation Completed', '2026-01-30 12:55:38'),
(77, 11, 'Translation Completed', '2026-01-30 12:55:38'),
(78, 12, 'Translation Completed', '2026-01-30 12:55:38'),
(79, 13, 'Translation Completed', '2026-01-30 12:55:38'),
(80, 14, 'Translation Completed', '2026-01-30 12:55:38'),
(81, 15, 'Translation Completed', '2026-01-30 12:55:38'),
(82, 16, 'Translation Completed', '2026-01-30 12:55:38'),
(83, 17, 'Translation Completed', '2026-01-30 12:55:38'),
(99, 1, 'Voice Generated', '2026-01-30 12:55:38'),
(100, 3, 'Voice Generated', '2026-01-30 12:55:38'),
(101, 4, 'Voice Generated', '2026-01-30 12:55:38'),
(102, 5, 'Voice Generated', '2026-01-30 12:55:38'),
(103, 6, 'Voice Generated', '2026-01-30 12:55:38'),
(104, 7, 'Voice Generated', '2026-01-30 12:55:38'),
(105, 8, 'Voice Generated', '2026-01-30 12:55:38'),
(106, 9, 'Voice Generated', '2026-01-30 12:55:38'),
(107, 10, 'Voice Generated', '2026-01-30 12:55:38'),
(108, 11, 'Voice Generated', '2026-01-30 12:55:38'),
(109, 12, 'Voice Generated', '2026-01-30 12:55:38'),
(110, 13, 'Voice Generated', '2026-01-30 12:55:38'),
(111, 14, 'Voice Generated', '2026-01-30 12:55:38'),
(112, 15, 'Voice Generated', '2026-01-30 12:55:38'),
(113, 16, 'Voice Generated', '2026-01-30 12:55:38'),
(114, 17, 'Voice Generated', '2026-01-30 12:55:38');

-- --------------------------------------------------------

--
-- Table structure for table `subscription_plans`
--

CREATE TABLE `subscription_plans` (
  `id` int(11) NOT NULL,
  `plan_name` varchar(50) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `monthly_minutes` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subscription_plans`
--

INSERT INTO `subscription_plans` (`id`, `plan_name`, `price`, `monthly_minutes`) VALUES
(1, 'Free', 0, 45),
(2, 'Pro', 29, 300),
(3, 'Enterprise', 99, 9999);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `subscription_plan` enum('Free','Pro','Enterprise') DEFAULT 'Free',
  `available_minutes` int(11) DEFAULT 45,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `token`, `subscription_plan`, `available_minutes`, `created_at`) VALUES
(1, '', 'arthi@gmail.com', '$2y$10$rdM7bujxEZLZJHmYbEBZE.Iufkv2eidKsnRVqoFHs0JzuNahil9zm', '28673e6245c5ea501777c08c2bb8ae4d21f99874114bd2cb2fb2f6e75d563dc3', 'Pro', 45, '2026-01-06 19:11:14'),
(2, '', 'arthip@gmail.com', '$2y$10$UryL6qhZbUr1szn3S54sh.xKaM23VhRKw8jvSLnIoWSli4Mo6slgG', 'a13482727f253361962b3d23e3a2a931c66a64a3dc3ec62ecddab35a4f6e1947', 'Pro', 45, '2026-01-08 03:14:49'),
(3, '', 'cherry@gmail.com', '$2y$10$B3rCnf4QTrPerrZLM5jFmeksZk9xWRKiCj3F0AxjXJAysoVsvwwLO', NULL, 'Free', 45, '2026-01-08 04:36:53'),
(4, 'P.chikki', 'chikki@gmail.com', '$2y$10$ETEkdHcr6/U2EeflvaDWve5c1vhYx01JFYUkrFejr4b492BieFbqS', '2e3ceb3c7ee1f013bea07ea1671cc6e2f823a67ed14a24c9e6d29147cf3a9d22', 'Free', 45, '2026-01-24 05:03:38'),
(5, 'Ruthish', 'peddaganiruthish@gmail.com', '$2y$10$9HWcAsqRq71dhOqZyLWnM.D7chPBTJdCA6b2Cvi9apcsOcaeb2LEC', 'b9a50ece9e4030d81e4bfb6a327d516035460f1bcde6b1aed3750c669ab18caf', 'Free', 45, '2026-02-02 16:28:26'),
(6, 'Pasupuleti Arthi', 'p.arthi0526@gmail.com', '$2y$10$1XS9gFbfjRfbRcplv4QGq.y0856Yf5es6xexA6vfZlgW6rkHrzKKa', '29960ac3599677b35cedecc86582eec2b5877e28c1c3fa7121b0d307beb0361a', 'Free', 45, '2026-02-07 06:37:04'),
(8, 'soni', 'soni@gmail.com', '$2y$10$1zyPmHaogeVa05BJ1F1N9.2xdlpwNz/MDXrAkginGQtJ/flxfbrLa', 'f023e9f7ae3bd1a6899e572b421a3de6ab848d25513e6b8418f7db54278750bd', 'Free', 45, '2026-02-10 04:55:38');

-- --------------------------------------------------------

--
-- Table structure for table `video_settings`
--

CREATE TABLE `video_settings` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `voice_name` varchar(100) DEFAULT NULL,
  `voice_style` enum('Professional','Casual','Energetic','Calm') DEFAULT 'Professional',
  `emotion_happiness` int(11) DEFAULT 50,
  `emotion_sadness` int(11) DEFAULT 0,
  `emotion_anger` int(11) DEFAULT 0,
  `emotion_neutral` int(11) DEFAULT 50,
  `speed` int(11) DEFAULT 50,
  `pitch` int(11) DEFAULT 50,
  `subtitle_enabled` tinyint(1) DEFAULT 1,
  `sync_offset` float DEFAULT 0,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `video_settings`
--

INSERT INTO `video_settings` (`id`, `project_id`, `voice_name`, `voice_style`, `emotion_happiness`, `emotion_sadness`, `emotion_anger`, `emotion_neutral`, `speed`, `pitch`, `subtitle_enabled`, `sync_offset`, `updated_at`) VALUES
(5, 3, 'Indian Male', '', 80, 0, 0, 0, 120, 90, 1, 0, '2026-01-24 07:34:50'),
(6, 4, 'Indian Male', '', 80, 0, 0, 0, 120, 90, 1, 0, '2026-01-26 13:58:43'),
(7, 5, 'Indian Male', '', 80, 0, 0, 0, 120, 90, 1, 0, '2026-01-26 14:19:39'),
(8, 6, 'Indian Male', '', 80, 0, 0, 0, 120, 90, 1, 0, '2026-01-28 10:17:35'),
(9, 17, 'Indian Male', '', 80, 0, 0, 0, 120, 90, 1, 0, '2026-01-30 12:21:28');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `project_history`
--
ALTER TABLE `project_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`);

--
-- Indexes for table `subscription_plans`
--
ALTER TABLE `subscription_plans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `video_settings`
--
ALTER TABLE `video_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `project_history`
--
ALTER TABLE `project_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- AUTO_INCREMENT for table `subscription_plans`
--
ALTER TABLE `subscription_plans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `video_settings`
--
ALTER TABLE `video_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `project_history`
--
ALTER TABLE `project_history`
  ADD CONSTRAINT `project_history_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `video_settings`
--
ALTER TABLE `video_settings`
  ADD CONSTRAINT `video_settings_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
