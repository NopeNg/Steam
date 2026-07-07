-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 07, 2026 at 11:54 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `game_key_marketplace`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `action` varchar(255) NOT NULL,
  `details` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `action`, `details`, `created_at`, `updated_at`) VALUES
(1, 'Cập nhật game', 'Đã cập nhật game \"Returnal\" (ID: 200), trạng thái: Active', '2026-06-21 23:24:57', '2026-06-21 23:24:57'),
(2, 'Cập nhật trạng thái đơn hàng', 'Đơn hàng #8: API_Error → Completed', '2026-06-21 23:31:19', '2026-06-21 23:31:19'),
(3, 'Tạo key giveaway', 'Đã tạo key giveaway: fâfa', '2026-06-21 23:31:32', '2026-06-21 23:31:32'),
(4, 'Xóa khuyến mãi', 'Đã xóa chiến dịch \"Weekend Deal\" (ID: 2)', '2026-06-22 01:44:21', '2026-06-22 01:44:21'),
(5, 'Xóa khuyến mãi', 'Đã xóa chiến dịch \"Summer Sale 2026\" (ID: 1)', '2026-06-22 01:44:24', '2026-06-22 01:44:24'),
(6, 'Thêm khuyến mãi', 'Đã thêm chiến dịch \"cs2\" (giảm 15%) áp dụng cho 1 phiên bản', '2026-06-22 01:53:02', '2026-06-22 01:53:02'),
(7, 'Tạo key giveaway', 'Đã tạo key giveaway: gamengon cho game version ID: 1', '2026-06-22 02:10:38', '2026-06-22 02:10:38'),
(8, 'Cập nhật game', 'Đã cập nhật game \"Dying Light 2 Stay Human\" (ID: 192), trạng thái: Active', '2026-06-22 02:27:47', '2026-06-22 02:27:47'),
(9, 'Cập nhật game', 'Đã cập nhật game \"Dying Light 2 Stay Human\" (ID: 192), trạng thái: ComingSoon', '2026-06-22 02:27:58', '2026-06-22 02:27:58'),
(10, 'Cập nhật game', 'Đã cập nhật game \"Osu!\" (ID: 196), trạng thái: ComingSoon', '2026-06-22 02:28:17', '2026-06-22 02:28:17'),
(11, 'Cập nhật game', 'Đã cập nhật game \"Slime Rancher\" (ID: 193), trạng thái: ComingSoon', '2026-06-22 02:28:32', '2026-06-22 02:28:32'),
(12, 'Thêm nhà cung cấp', 'Đã thêm nhà cung cấp \"TEST FAIL\" (code: WILL FAIL)', '2026-06-23 15:30:05', '2026-06-23 15:30:05'),
(13, 'Cập nhật mapping game-supplier', 'Đã cập nhật mapping (ID: 28)', '2026-06-23 15:32:11', '2026-06-23 15:32:11'),
(14, 'Cập nhật mapping game-supplier', 'Đã cập nhật mapping (ID: 191)', '2026-06-23 15:32:18', '2026-06-23 15:32:18'),
(15, 'Cập nhật mapping game-supplier', 'Đã cập nhật mapping (ID: 191)', '2026-06-23 15:32:22', '2026-06-23 15:32:22'),
(16, 'Cập nhật mapping game-supplier', 'Đã cập nhật mapping (ID: 53)', '2026-06-23 15:32:25', '2026-06-23 15:32:25'),
(17, 'Xóa game', 'Đã xóa game \"Call of Duty: Modern Warfare III\" (ID: 134)', '2026-06-23 15:39:14', '2026-06-23 15:39:14'),
(18, 'Cập nhật trạng thái đơn hàng', 'Đơn hàng #11: API_Error → Completed', '2026-06-23 15:42:38', '2026-06-23 15:42:38'),
(19, 'Cập nhật mapping game-supplier', 'Đã cập nhật mapping (ID: 102)', '2026-06-23 15:56:46', '2026-06-23 15:56:46'),
(20, 'Cập nhật mapping game-supplier', 'Đã cập nhật mapping (ID: 141)', '2026-06-23 15:56:52', '2026-06-23 15:56:52'),
(21, 'Cập nhật mapping game-supplier', 'Đã cập nhật mapping (ID: 79)', '2026-06-23 15:56:57', '2026-06-23 15:56:57'),
(22, 'Thêm game mới', 'Đã thêm game \"a\" (ID: 203)', '2026-07-07 13:24:26', '2026-07-07 13:24:26'),
(23, 'Xóa game', 'Đã xóa game \"a\" (ID: 203)', '2026-07-07 13:24:31', '2026-07-07 13:24:31'),
(24, 'Xóa ảnh game', 'Đã xóa ảnh (ID: 198) của game (ID: 198)', '2026-07-07 13:24:38', '2026-07-07 13:24:38'),
(25, 'Cập nhật game', 'Đã cập nhật game \"Ghost of Tsushima\" (ID: 198), trạng thái: Active', '2026-07-07 13:24:57', '2026-07-07 13:24:57'),
(26, 'Cập nhật game', 'Đã cập nhật game \"Returnala\" (ID: 200), trạng thái: Active', '2026-07-07 13:31:52', '2026-07-07 13:31:52'),
(27, 'Cập nhật game', 'Đã cập nhật game \"Returnala\" (ID: 200), trạng thái: Active', '2026-07-07 13:32:10', '2026-07-07 13:32:10'),
(28, 'Cập nhật game', 'Đã cập nhật game \"Returnala\" (ID: 200), trạng thái: ComingSoon', '2026-07-07 13:32:28', '2026-07-07 13:32:28'),
(29, 'Xóa ảnh game', 'Đã xóa ảnh (ID: 200) của game (ID: 200)', '2026-07-07 13:32:43', '2026-07-07 13:32:43'),
(30, 'Cập nhật game', 'Đã cập nhật game \"Returnala\" (ID: 200), trạng thái: ComingSoon', '2026-07-07 13:33:06', '2026-07-07 13:33:06'),
(31, 'Cập nhật game', 'Đã cập nhật game \"Returnal\" (ID: 200), trạng thái: ComingSoon', '2026-07-07 13:33:51', '2026-07-07 13:33:51'),
(32, 'Xóa ảnh game', 'Đã xóa ảnh (ID: 205) của game (ID: 200)', '2026-07-07 13:33:56', '2026-07-07 13:33:56'),
(33, 'Xóa game', 'Đã xóa game \"Osu!\" (ID: 196)', '2026-07-07 13:34:13', '2026-07-07 13:34:13'),
(34, 'Thêm khuyến mãi', 'Đã thêm chiến dịch \"tast\" (giảm 15%) áp dụng cho 1 phiên bản', '2026-07-07 13:37:49', '2026-07-07 13:37:49'),
(35, 'Cập nhật trạng thái đơn hàng', 'Đơn hàng #12: Completed → API_Error', '2026-07-07 13:54:18', '2026-07-07 13:54:18'),
(36, 'Cập nhật trạng thái đơn hàng', 'Đơn hàng #13: Completed → API_Error', '2026-07-07 13:55:07', '2026-07-07 13:55:07'),
(37, 'Hoàn tiền key', 'Đã hoàn 0 VNĐ cho key ID: 11 (game: Counter-Strike 2, đơn hàng #13)', '2026-07-07 13:55:18', '2026-07-07 13:55:18'),
(38, 'Cập nhật trạng thái đơn hàng', 'Đơn hàng #14: Completed → API_Error', '2026-07-07 13:56:01', '2026-07-07 13:56:01'),
(39, 'Hoàn tiền đơn hàng', 'Đã hoàn 40 VNĐ cho đơn hàng #18 (người chơi ID: 1)', '2026-07-07 13:57:23', '2026-07-07 13:57:23'),
(40, 'Cập nhật trạng thái đơn hàng', 'Đơn hàng #18: Completed → API_Error', '2026-07-07 14:06:33', '2026-07-07 14:06:33'),
(41, 'Cập nhật trạng thái đơn hàng', 'Đơn hàng #18: API_Error → Completed', '2026-07-07 14:06:44', '2026-07-07 14:06:44'),
(42, 'Hoàn tiền đơn hàng', 'Đã hoàn 40 VNĐ cho đơn hàng #23 (người chơi ID: 1)', '2026-07-07 14:08:42', '2026-07-07 14:08:42'),
(43, 'Hoàn tiền đơn hàng', 'Đã hoàn 40 VNĐ cho đơn hàng #23 (người chơi ID: 1)', '2026-07-07 14:08:46', '2026-07-07 14:08:46'),
(44, 'Hoàn tiền đơn hàng', 'Đã hoàn 40 VNĐ cho đơn hàng #23 (người chơi ID: 1)', '2026-07-07 14:08:49', '2026-07-07 14:08:49'),
(45, 'Cập nhật trạng thái đơn hàng', 'Đơn hàng #23: Failed → API_Error', '2026-07-07 14:09:46', '2026-07-07 14:09:46'),
(46, 'Hoàn tiền đơn hàng', 'Đã hoàn 40 VNĐ cho đơn hàng #23 (người chơi ID: 1)', '2026-07-07 14:09:49', '2026-07-07 14:09:49'),
(47, 'Hoàn tiền đơn hàng', 'Đã hoàn 40 VNĐ cho đơn hàng #24 (người chơi ID: 1)', '2026-07-07 14:12:30', '2026-07-07 14:12:30'),
(48, 'Cập nhật trạng thái đơn hàng', 'Đơn hàng #24: API_Error → Failed', '2026-07-07 14:17:12', '2026-07-07 14:17:12'),
(49, 'Hoàn tiền đơn hàng', 'Đã hoàn 40 VNĐ cho đơn hàng #28 (người chơi ID: 1)', '2026-07-07 14:18:51', '2026-07-07 14:18:51'),
(50, 'Xóa ảnh game', 'Đã xóa ảnh (ID: 50) của game (ID: 50)', '2026-07-07 14:20:31', '2026-07-07 14:20:31'),
(51, 'Cập nhật game', 'Đã cập nhật game \"Slay the Spire\" (ID: 50), trạng thái: Active', '2026-07-07 14:20:49', '2026-07-07 14:20:49'),
(52, 'Xóa ảnh game', 'Đã xóa ảnh (ID: 49) của game (ID: 49)', '2026-07-07 14:21:15', '2026-07-07 14:21:15'),
(53, 'Cập nhật game', 'Đã cập nhật game \"Dead Cells\" (ID: 49), trạng thái: Active', '2026-07-07 14:21:37', '2026-07-07 14:21:37'),
(54, 'Xóa ảnh game', 'Đã xóa ảnh (ID: 211) của game (ID: 49)', '2026-07-07 14:21:45', '2026-07-07 14:21:45'),
(55, 'Cập nhật game', 'Đã cập nhật game \"Dead Cells\" (ID: 49), trạng thái: Active', '2026-07-07 14:21:53', '2026-07-07 14:21:53'),
(56, 'Xóa ảnh game', 'Đã xóa ảnh (ID: 48) của game (ID: 48)', '2026-07-07 14:22:06', '2026-07-07 14:22:06'),
(57, 'Cập nhật game', 'Đã cập nhật game \"Celeste\" (ID: 48), trạng thái: Active', '2026-07-07 14:22:41', '2026-07-07 14:22:41'),
(58, 'Xóa ảnh game', 'Đã xóa ảnh (ID: 47) của game (ID: 47)', '2026-07-07 14:22:48', '2026-07-07 14:22:48'),
(59, 'Cập nhật game', 'Đã cập nhật game \"Hades\" (ID: 47), trạng thái: Active', '2026-07-07 14:23:33', '2026-07-07 14:23:33'),
(60, 'Xóa ảnh game', 'Đã xóa ảnh (ID: 46) của game (ID: 46)', '2026-07-07 14:23:52', '2026-07-07 14:23:52'),
(61, 'Cập nhật game', 'Đã cập nhật game \"Hollow Knight\" (ID: 46), trạng thái: Active', '2026-07-07 14:24:16', '2026-07-07 14:24:16'),
(62, 'Xóa ảnh game', 'Đã xóa ảnh (ID: 45) của game (ID: 45)', '2026-07-07 14:24:36', '2026-07-07 14:24:36'),
(63, 'Cập nhật game', 'Đã cập nhật game \"No Man\'s Sky\" (ID: 45), trạng thái: Active', '2026-07-07 14:25:39', '2026-07-07 14:25:39'),
(64, 'Xóa ảnh game', 'Đã xóa ảnh (ID: 44) của game (ID: 44)', '2026-07-07 14:26:39', '2026-07-07 14:26:39'),
(65, 'Cập nhật game', 'Đã cập nhật game \"Starfield\" (ID: 44), trạng thái: Active', '2026-07-07 14:27:02', '2026-07-07 14:27:02'),
(66, 'Xóa ảnh game', 'Đã xóa ảnh (ID: 43) của game (ID: 43)', '2026-07-07 14:27:18', '2026-07-07 14:27:18'),
(67, 'Cập nhật game', 'Đã cập nhật game \"Fallout 4\" (ID: 43), trạng thái: Active', '2026-07-07 14:27:39', '2026-07-07 14:27:39'),
(68, 'Xóa ảnh game', 'Đã xóa ảnh (ID: 42) của game (ID: 42)', '2026-07-07 14:27:48', '2026-07-07 14:27:48'),
(69, 'Cập nhật game', 'Đã cập nhật game \"The Elder Scrolls V: Skyrim\" (ID: 42), trạng thái: Active', '2026-07-07 14:28:14', '2026-07-07 14:28:14'),
(70, 'Xóa ảnh game', 'Đã xóa ảnh (ID: 41) của game (ID: 41)', '2026-07-07 14:28:26', '2026-07-07 14:28:26'),
(71, 'Cập nhật game', 'Đã cập nhật game \"Monster Hunter: World\" (ID: 41), trạng thái: Active', '2026-07-07 14:29:37', '2026-07-07 14:29:37'),
(72, 'Xóa ảnh game', 'Đã xóa ảnh (ID: 40) của game (ID: 40)', '2026-07-07 14:29:54', '2026-07-07 14:29:54'),
(73, 'Cập nhật game', 'Đã cập nhật game \"Mortal Kombat 1\" (ID: 40), trạng thái: Active', '2026-07-07 14:30:11', '2026-07-07 14:30:11'),
(74, 'Xóa ảnh game', 'Đã xóa ảnh (ID: 39) của game (ID: 39)', '2026-07-07 14:30:26', '2026-07-07 14:30:26'),
(75, 'Cập nhật game', 'Đã cập nhật game \"Street Fighter 6\" (ID: 39), trạng thái: Active', '2026-07-07 14:30:42', '2026-07-07 14:30:42'),
(76, 'Xóa ảnh game', 'Đã xóa ảnh (ID: 38) của game (ID: 38)', '2026-07-07 14:30:59', '2026-07-07 14:30:59'),
(77, 'Cập nhật game', 'Đã cập nhật game \"Tekken 8\" (ID: 38), trạng thái: Active', '2026-07-07 14:31:20', '2026-07-07 14:31:20'),
(78, 'Xóa ảnh game', 'Đã xóa ảnh (ID: 37) của game (ID: 37)', '2026-07-07 14:31:41', '2026-07-07 14:31:41'),
(79, 'Cập nhật game', 'Đã cập nhật game \"NBA 2K24\" (ID: 37), trạng thái: Active', '2026-07-07 14:32:00', '2026-07-07 14:32:00'),
(80, 'Xóa ảnh game', 'Đã xóa ảnh (ID: 36) của game (ID: 36)', '2026-07-07 14:32:16', '2026-07-07 14:32:16'),
(81, 'Cập nhật game', 'Đã cập nhật game \"EA SPORTS FC 24\" (ID: 36), trạng thái: Active', '2026-07-07 14:32:33', '2026-07-07 14:32:33'),
(82, 'Xóa ảnh game', 'Đã xóa ảnh (ID: 35) của game (ID: 35)', '2026-07-07 14:32:43', '2026-07-07 14:32:43'),
(83, 'Cập nhật game', 'Đã cập nhật game \"Forza Horizon 5\" (ID: 35), trạng thái: Active', '2026-07-07 14:33:04', '2026-07-07 14:33:04'),
(84, 'Xóa ảnh game', 'Đã xóa ảnh (ID: 34) của game (ID: 34)', '2026-07-07 14:33:19', '2026-07-07 14:33:19'),
(85, 'Cập nhật game', 'Đã cập nhật game \"Sea of Thieves\" (ID: 34), trạng thái: Active', '2026-07-07 14:33:40', '2026-07-07 14:33:40'),
(86, 'Xóa ảnh game', 'Đã xóa ảnh (ID: 33) của game (ID: 33)', '2026-07-07 14:33:50', '2026-07-07 14:33:50'),
(87, 'Cập nhật game', 'Đã cập nhật game \"Lethal Company\" (ID: 33), trạng thái: Active', '2026-07-07 14:34:09', '2026-07-07 14:34:09'),
(88, 'Xóa ảnh game', 'Đã xóa ảnh (ID: 32) của game (ID: 32)', '2026-07-07 14:34:16', '2026-07-07 14:34:16'),
(89, 'Cập nhật game', 'Đã cập nhật game \"Helldivers 2\" (ID: 32), trạng thái: Active', '2026-07-07 14:34:35', '2026-07-07 14:34:35'),
(90, 'Xóa ảnh game', 'Đã xóa ảnh (ID: 31) của game (ID: 31)', '2026-07-07 14:34:44', '2026-07-07 14:34:44'),
(91, 'Cập nhật game', 'Đã cập nhật game \"Palworld\" (ID: 31), trạng thái: Active', '2026-07-07 14:35:01', '2026-07-07 14:35:01'),
(92, 'Xóa ảnh game', 'Đã xóa ảnh (ID: 30) của game (ID: 30)', '2026-07-07 14:35:19', '2026-07-07 14:35:19'),
(93, 'Cập nhật game', 'Đã cập nhật game \"Fall Guys\" (ID: 30), trạng thái: Active', '2026-07-07 14:35:35', '2026-07-07 14:35:35'),
(94, 'Xóa ảnh game', 'Đã xóa ảnh (ID: 29) của game (ID: 29)', '2026-07-07 14:35:42', '2026-07-07 14:35:42'),
(95, 'Cập nhật game', 'Đã cập nhật game \"Among Us\" (ID: 29), trạng thái: Active', '2026-07-07 14:36:01', '2026-07-07 14:36:01'),
(96, 'Xóa ảnh game', 'Đã xóa ảnh (ID: 28) của game (ID: 28)', '2026-07-07 14:36:13', '2026-07-07 14:36:13'),
(97, 'Cập nhật game', 'Đã cập nhật game \"Phasmophobia\" (ID: 28), trạng thái: Active', '2026-07-07 14:36:29', '2026-07-07 14:36:29'),
(98, 'Xóa ảnh game', 'Đã xóa ảnh (ID: 27) của game (ID: 27)', '2026-07-07 14:36:40', '2026-07-07 14:36:40'),
(99, 'Cập nhật game', 'Đã cập nhật game \"Garry\'s Mod\" (ID: 27), trạng thái: Active', '2026-07-07 14:37:00', '2026-07-07 14:37:00'),
(100, 'Xóa ảnh game', 'Đã xóa ảnh (ID: 26) của game (ID: 26)', '2026-07-07 14:37:07', '2026-07-07 14:37:07'),
(101, 'Cập nhật game', 'Đã cập nhật game \"Team Fortress 2\" (ID: 26), trạng thái: Active', '2026-07-07 14:37:33', '2026-07-07 14:37:33'),
(102, 'Xóa ảnh game', 'Đã xóa ảnh (ID: 25) của game (ID: 25)', '2026-07-07 14:37:44', '2026-07-07 14:37:44'),
(103, 'Cập nhật game', 'Đã cập nhật game \"Half-Life 2\" (ID: 25), trạng thái: Active', '2026-07-07 14:38:03', '2026-07-07 14:38:03'),
(104, 'Xóa ảnh game', 'Đã xóa ảnh (ID: 24) của game (ID: 24)', '2026-07-07 14:38:16', '2026-07-07 14:38:16'),
(105, 'Cập nhật game', 'Đã cập nhật game \"Portal 2\" (ID: 24), trạng thái: Active', '2026-07-07 14:38:31', '2026-07-07 14:38:31'),
(106, 'Xóa ảnh game', 'Đã xóa ảnh (ID: 23) của game (ID: 23)', '2026-07-07 14:38:43', '2026-07-07 14:38:43'),
(107, 'Cập nhật game', 'Đã cập nhật game \"Left 4 Dead 2\" (ID: 23), trạng thái: Active', '2026-07-07 14:39:02', '2026-07-07 14:39:02'),
(108, 'Xóa ảnh game', 'Đã xóa ảnh (ID: 22) của game (ID: 22)', '2026-07-07 14:39:09', '2026-07-07 14:39:09'),
(109, 'Cập nhật game', 'Đã cập nhật game \"Warframe\" (ID: 22), trạng thái: Active', '2026-07-07 14:39:29', '2026-07-07 14:39:29'),
(110, 'Xóa ảnh game', 'Đã xóa ảnh (ID: 21) của game (ID: 21)', '2026-07-07 14:39:45', '2026-07-07 14:39:45'),
(111, 'Cập nhật game', 'Đã cập nhật game \"Destiny 2\" (ID: 21), trạng thái: Active', '2026-07-07 14:40:01', '2026-07-07 14:40:01'),
(112, 'Xóa ảnh game', 'Đã xóa ảnh (ID: 20) của game (ID: 20)', '2026-07-07 14:40:16', '2026-07-07 14:40:16'),
(113, 'Cập nhật game', 'Đã cập nhật game \"Dead by Daylight\" (ID: 20), trạng thái: Active', '2026-07-07 14:40:31', '2026-07-07 14:40:31'),
(114, 'Xóa ảnh game', 'Đã xóa ảnh (ID: 19) của game (ID: 19)', '2026-07-07 14:40:42', '2026-07-07 14:40:42'),
(115, 'Cập nhật game', 'Đã cập nhật game \"Rust\" (ID: 19), trạng thái: Active', '2026-07-07 14:40:59', '2026-07-07 14:40:59'),
(116, 'Xóa ảnh game', 'Đã xóa ảnh (ID: 18) của game (ID: 18)', '2026-07-07 14:41:09', '2026-07-07 14:41:09'),
(117, 'Cập nhật game', 'Đã cập nhật game \"Rocket League\" (ID: 18), trạng thái: Active', '2026-07-07 14:41:24', '2026-07-07 14:41:24'),
(118, 'Xóa ảnh game', 'Đã xóa ảnh (ID: 17) của game (ID: 17)', '2026-07-07 14:41:45', '2026-07-07 14:41:45'),
(119, 'Cập nhật game', 'Đã cập nhật game \"Overwatch 2\" (ID: 17), trạng thái: Active', '2026-07-07 14:42:53', '2026-07-07 14:42:53'),
(120, 'Xóa ảnh game', 'Đã xóa ảnh (ID: 16) của game (ID: 16)', '2026-07-07 14:43:06', '2026-07-07 14:43:06'),
(121, 'Cập nhật game', 'Đã cập nhật game \"Tom Clancy\'s Rainbow Six Siege\" (ID: 16), trạng thái: Active', '2026-07-07 14:43:21', '2026-07-07 14:43:21'),
(122, 'Xóa ảnh game', 'Đã xóa ảnh (ID: 15) của game (ID: 15)', '2026-07-07 14:43:33', '2026-07-07 14:43:33'),
(123, 'Cập nhật game', 'Đã cập nhật game \"Stardew Valley\" (ID: 15), trạng thái: Active', '2026-07-07 14:43:50', '2026-07-07 14:43:50'),
(124, 'Xóa ảnh game', 'Đã xóa ảnh (ID: 14) của game (ID: 14)', '2026-07-07 14:44:01', '2026-07-07 14:44:01'),
(125, 'Cập nhật game', 'Đã cập nhật game \"Terraria\" (ID: 14), trạng thái: Active', '2026-07-07 14:44:17', '2026-07-07 14:44:17'),
(126, 'Xóa ảnh game', 'Đã xóa ảnh (ID: 13) của game (ID: 13)', '2026-07-07 14:44:27', '2026-07-07 14:44:27'),
(127, 'Cập nhật game', 'Đã cập nhật game \"Minecraft\" (ID: 13), trạng thái: Active', '2026-07-07 14:45:05', '2026-07-07 14:45:05'),
(128, 'Xóa ảnh game', 'Đã xóa ảnh (ID: 12) của game (ID: 12)', '2026-07-07 14:45:21', '2026-07-07 14:45:21'),
(129, 'Cập nhật game', 'Đã cập nhật game \"Baldur\'s Gate 3\" (ID: 12), trạng thái: Active', '2026-07-07 14:45:37', '2026-07-07 14:45:37'),
(130, 'Xóa ảnh game', 'Đã xóa ảnh (ID: 11) của game (ID: 11)', '2026-07-07 14:45:48', '2026-07-07 14:45:48'),
(131, 'Cập nhật game', 'Đã cập nhật game \"Elden Ring\" (ID: 11), trạng thái: Active', '2026-07-07 14:46:03', '2026-07-07 14:46:03'),
(132, 'Xóa ảnh game', 'Đã xóa ảnh (ID: 10) của game (ID: 10)', '2026-07-07 14:46:18', '2026-07-07 14:46:18'),
(133, 'Cập nhật game', 'Đã cập nhật game \"Red Dead Redemption 2\" (ID: 10), trạng thái: Active', '2026-07-07 14:46:36', '2026-07-07 14:46:36'),
(134, 'Xóa ảnh game', 'Đã xóa ảnh (ID: 9) của game (ID: 9)', '2026-07-07 14:46:48', '2026-07-07 14:46:48'),
(135, 'Cập nhật game', 'Đã cập nhật game \"The Witcher 3: Wild Hunt\" (ID: 9), trạng thái: Active', '2026-07-07 14:47:07', '2026-07-07 14:47:07'),
(136, 'Xóa ảnh game', 'Đã xóa ảnh (ID: 8) của game (ID: 8)', '2026-07-07 14:47:17', '2026-07-07 14:47:17'),
(137, 'Cập nhật game', 'Đã cập nhật game \"Cyberpunk 2077\" (ID: 8), trạng thái: Active', '2026-07-07 14:47:38', '2026-07-07 14:47:38'),
(138, 'Xóa ảnh game', 'Đã xóa ảnh (ID: 7) của game (ID: 7)', '2026-07-07 14:47:49', '2026-07-07 14:47:49'),
(139, 'Cập nhật game', 'Đã cập nhật game \"Grand Theft Auto V\" (ID: 7), trạng thái: Active', '2026-07-07 14:48:14', '2026-07-07 14:48:14'),
(140, 'Xóa ảnh game', 'Đã xóa ảnh (ID: 6) của game (ID: 6)', '2026-07-07 14:48:34', '2026-07-07 14:48:34'),
(141, 'Cập nhật game', 'Đã cập nhật game \"Apex Legends\" (ID: 6), trạng thái: Active', '2026-07-07 14:48:51', '2026-07-07 14:48:51'),
(142, 'Xóa ảnh game', 'Đã xóa ảnh (ID: 5) của game (ID: 5)', '2026-07-07 14:49:12', '2026-07-07 14:49:12'),
(143, 'Cập nhật game', 'Đã cập nhật game \"PUBG: BATTLEGROUNDS\" (ID: 5), trạng thái: Active', '2026-07-07 14:49:30', '2026-07-07 14:49:30'),
(144, 'Cập nhật game', 'Đã cập nhật game \"League of Legends\" (ID: 4), trạng thái: Active', '2026-07-07 14:49:51', '2026-07-07 14:49:51'),
(145, 'Xóa ảnh game', 'Đã xóa ảnh (ID: 3) của game (ID: 3)', '2026-07-07 14:50:12', '2026-07-07 14:50:12'),
(146, 'Cập nhật game', 'Đã cập nhật game \"Valorant\" (ID: 3), trạng thái: Active', '2026-07-07 14:50:35', '2026-07-07 14:50:35'),
(147, 'Xóa ảnh game', 'Đã xóa ảnh (ID: 2) của game (ID: 2)', '2026-07-07 14:50:46', '2026-07-07 14:50:46'),
(148, 'Cập nhật game', 'Đã cập nhật game \"Dota 2\" (ID: 2), trạng thái: Active', '2026-07-07 14:51:04', '2026-07-07 14:51:04'),
(149, 'Xóa ảnh game', 'Đã xóa ảnh (ID: 1) của game (ID: 1)', '2026-07-07 14:51:14', '2026-07-07 14:51:14'),
(150, 'Cập nhật game', 'Đã cập nhật game \"Counter-Strike 2\" (ID: 1), trạng thái: Active', '2026-07-07 14:51:55', '2026-07-07 14:51:55');

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'admin', 'a@a.com', '$2y$12$E85Ga0hGaArth15DcTXxzOLLF8m.KbBVg.vLApXjIeciF/h9z3qfy', '2026-06-08 16:55:58');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` int(11) NOT NULL,
  `player_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`id`, `player_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2026-06-09 10:46:55', '2026-06-09 10:46:55');

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL,
  `cart_id` int(11) NOT NULL,
  `game_version_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category_name`) VALUES
(4, 'Action - Hành động'),
(9, 'Adventure - Phiêu lưu'),
(1, 'FPS - Bắn súng góc nhìn thứ nhất'),
(10, 'Horror - Kinh dị'),
(2, 'MOBA - Đấu trường trực tuyến'),
(7, 'Racing - Đua xe'),
(3, 'RPG - Nhập vai'),
(8, 'Simulation - Mô phỏng'),
(6, 'Sports - Thể thao'),
(5, 'Strategy - Chiến thuật');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `friendships`
--

CREATE TABLE `friendships` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `friendships`
--

INSERT INTO `friendships` (`id`, `sender_id`, `receiver_id`, `status`, `created_at`) VALUES
(1, 1, 2, 'Accepted', '2026-06-22 09:17:47');

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

CREATE TABLE `games` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `release_date` date DEFAULT NULL,
  `description` text DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `publisher` varchar(150) DEFAULT NULL,
  `developer` varchar(150) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Active',
  `requirements` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `games`
--

INSERT INTO `games` (`id`, `name`, `release_date`, `description`, `cover_image`, `publisher`, `developer`, `status`, `requirements`) VALUES
(1, 'Counter-Strike 2', '2023-09-27', 'Game bắn súng chiến thuật nhiều người chơi', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/730/header.jpg', 'Valve', 'Valve', 'Active', 'Intel Core i5-11400H, RTX 3050 4GB, 24GB RAM, 512GB SSD'),
(2, 'Dota 2', '2013-07-09', 'Game MOBA chiến thuật 5v5', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/570/header.jpg', 'Valve', 'Valve', 'Active', 'Intel Core i5-11400H, RTX 3050 4GB, 24GB RAM, 512GB SSD'),
(3, 'Valorant', '2020-06-02', 'Bắn súng chiến thuật kết hợp kỹ năng đặc biệt', 'https://static.toiimg.com/thumb/msid-119049992,width-400,resizemode-4/119049992.jpg', 'Riot Games', 'Riot Games', 'Active', 'Intel Core i5-11400H, RTX 3050 4GB, 24GB RAM, 512GB SSD'),
(4, 'League of Legends', '2009-10-27', 'Game MOBA phổ biến nhất thế giới', 'https://ddragon.leagueoflegends.com/cdn/img/champion/splash/Ahri_0.jpg', 'Riot Games', 'Riot Games', 'Active', 'Intel Core i5-11400H, RTX 3050 4GB, 24GB RAM, 512GB SSD'),
(5, 'PUBG: BATTLEGROUNDS', '2017-12-21', 'Sinh tồn Battle Royale', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/578080/header.jpg', 'KRAFTON', 'PUBG Studios', 'Active', 'Intel Core i5-11400H, RTX 3050 4GB, 24GB RAM, 512GB SSD'),
(6, 'Apex Legends', '2019-02-04', 'Battle Royale nhịp độ nhanh', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1172470/header.jpg', 'Electronic Arts', 'Respawn Entertainment', 'Active', 'Intel Core i5-11400H, RTX 3050 4GB, 24GB RAM, 512GB SSD'),
(7, 'Grand Theft Auto V', '2015-04-14', 'Hành động thế giới mở', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/271590/header.jpg', 'Rockstar Games', 'Rockstar North', 'Active', 'Intel Core i5-11400H, RTX 3050 4GB, 24GB RAM, 512GB SSD'),
(8, 'Cyberpunk 2077', '2020-12-10', 'Nhập vai hành động thế giới tương lai', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1091500/header.jpg', 'CD PROJEKT RED', 'CD PROJEKT RED', 'Active', 'Intel Core i5-11400H, RTX 3050 4GB, 24GB RAM, 512GB SSD'),
(9, 'The Witcher 3: Wild Hunt', '2015-05-18', 'Siêu phẩm RPG thế giới mở', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/292030/header.jpg', 'CD PROJEKT RED', 'CD PROJEKT RED', 'Active', 'Intel Core i5-11400H, RTX 3050 4GB, 24GB RAM, 512GB SSD'),
(10, 'Red Dead Redemption 2', '2019-12-05', 'Hành động phiêu lưu miền viễn tây', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1174180/header.jpg', 'Rockstar Games', 'Rockstar Games', 'Active', 'Intel Core i5-11400H, RTX 3050 4GB, 24GB RAM, 512GB SSD'),
(11, 'Elden Ring', '2022-02-24', 'Hành động nhập vai Souls-like', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1245620/header.jpg', 'FromSoftware', 'FromSoftware', 'Active', 'Intel Core i5-11400H, RTX 3050 4GB, 24GB RAM, 512GB SSD'),
(12, 'Baldur\'s Gate 3', '2023-08-03', 'Siêu phẩm RPG chiến thuật', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1086940/header.jpg', 'Larian Studios', 'Larian Studios', 'Active', 'Intel Core i5-11400H, RTX 3050 4GB, 24GB RAM, 512GB SSD'),
(13, 'Minecraft', '2011-11-18', 'Sinh tồn sáng tạo khối vuông', 'https://cdn-media.sforum.vn/storage/app/media/thanhhuyen/c%C3%A1c%20ph%C3%ADm%20t%E1%BA%AFt%20trong%20minecraft/cac-phim-tat-trong-minecraft-thumbnail.jpg', 'Mojang', 'Mojang', 'Active', 'Intel Core i5-11400H, RTX 3050 4GB, 24GB RAM, 512GB SSD'),
(14, 'Terraria', '2011-05-16', 'Phiêu lưu sinh tồn 2D', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/105600/header.jpg', 'Re-Logic', 'Re-Logic', 'Active', 'Intel Core i5-11400H, RTX 3050 4GB, 24GB RAM, 512GB SSD'),
(15, 'Stardew Valley', '2016-02-26', 'Mô phỏng nông trại RPG', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/413150/header.jpg', 'ConcernedApe', 'ConcernedApe', 'Active', 'Intel Core i5-11400H, RTX 3050 4GB, 24GB RAM, 512GB SSD'),
(16, 'Tom Clancy\'s Rainbow Six Siege', '2015-12-01', 'Bắn súng chiến thuật đối kháng', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/359550/header.jpg', 'Ubisoft', 'Ubisoft', 'Active', 'Intel Core i5-11400H, RTX 3050 4GB, 24GB RAM, 512GB SSD'),
(17, 'Overwatch 2', '2022-10-04', 'Hero shooter đội hình', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS0dB-0f89UGFDiIL0Y8dqySpq3d4NFaQsvmSxQixvLoQ&s=10', 'Blizzard', 'Blizzard', 'Active', 'Intel Core i5-11400H, RTX 3050 4GB, 24GB RAM, 512GB SSD'),
(18, 'Rocket League', '2015-07-07', 'Bóng đá kết hợp đua xe', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/252950/header.jpg', 'Psyonix LLC', 'Psyonix LLC', 'Active', 'Intel Core i5-11400H, RTX 3050 4GB, 24GB RAM, 512GB SSD'),
(19, 'Rust', '2018-02-08', 'Sinh tồn khắc nghiệt', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/252490/header.jpg', 'Facepunch Studios', 'Facepunch Studios', 'Active', 'Intel Core i5-11400H, RTX 3050 4GB, 24GB RAM, 512GB SSD'),
(20, 'Dead by Daylight', '2016-06-14', 'Kinh dị sinh tồn 4vs1', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/381210/header.jpg', 'Behaviour Interactive', 'Behaviour Interactive', 'Active', 'Intel Core i5-11400H, RTX 3050 4GB, 24GB RAM, 512GB SSD'),
(21, 'Destiny 2', '2019-10-01', 'Bắn súng MMO', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1085660/header.jpg', 'Bungie', 'Bungie', 'Active', 'Intel Core i5-11400H, RTX 3050 4GB, 24GB RAM, 512GB SSD'),
(22, 'Warframe', '2013-03-25', 'Hành động ninja viễn tưởng', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/230410/header.jpg', 'Digital Extremes', 'Digital Extremes', 'Active', 'Intel Core i5-11400H, RTX 3050 4GB, 24GB RAM, 512GB SSD'),
(23, 'Left 4 Dead 2', '2009-11-16', 'Bắn súng diệt zombie co-op', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/550/header.jpg', 'Valve', 'Valve', 'Active', 'Intel Core i5-11400H, RTX 3050 4GB, 24GB RAM, 512GB SSD'),
(24, 'Portal 2', '2011-04-18', 'Giải đố góc nhìn thứ nhất', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/620/header.jpg', 'Valve', 'Valve', 'Active', 'Intel Core i5-11400H, RTX 3050 4GB, 24GB RAM, 512GB SSD'),
(25, 'Half-Life 2', '2004-11-16', 'Siêu phẩm FPS cổ điển', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/220/header.jpg', 'Valve', 'Valve', 'Active', 'Intel Core i5-11400H, RTX 3050 4GB, 24GB RAM, 512GB SSD'),
(26, 'Team Fortress 2', '2007-10-10', 'FPS đội hình đa dạng', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/440/header.jpg', 'Valve', 'Valve', 'Active', 'Intel Core i5-11400H, RTX 3050 4GB, 24GB RAM, 512GB SSD'),
(27, 'Garry\'s Mod', '2006-11-29', 'Game Sandbox vật lý', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/4000/header.jpg', 'Valve', 'Facepunch Studios', 'Active', 'Intel Core i5-11400H, RTX 3050 4GB, 24GB RAM, 512GB SSD'),
(28, 'Phasmophobia', '2020-09-18', 'Kinh dị săn ma co-op', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/739630/header.jpg', 'Kinetic Games', 'Kinetic Games', 'Active', 'Intel Core i5-11400H, RTX 3050 4GB, 24GB RAM, 512GB SSD'),
(29, 'Among Us', '2018-11-16', 'Truy tìm kẻ giả mạo', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/945360/header.jpg', 'Innersloth', 'Innersloth', 'Active', 'Intel Core i5-11400H, RTX 3050 4GB, 24GB RAM, 512GB SSD'),
(30, 'Fall Guys', '2020-08-04', 'Vượt chướng ngại vật vui nhộn', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1097150/header.jpg', 'Epic Games', 'Mediatonic', 'Active', 'Intel Core i5-11400H, RTX 3050 4GB, 24GB RAM, 512GB SSD'),
(31, 'Palworld', '2024-01-19', 'Sinh tồn bắt thú thế giới mở', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1623730/header.jpg', 'Pocketpair', 'Pocketpair', 'Active', 'Intel Core i5-11400H, RTX 3050 4GB, 24GB RAM, 512GB SSD'),
(32, 'Helldivers 2', '2024-02-08', 'Bắn súng co-op bảo vệ dải ngân hà', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/553850/header.jpg', 'PlayStation PC', 'Arrowhead', 'Active', 'Intel Core i5-11400H, RTX 3050 4GB, 24GB RAM, 512GB SSD'),
(33, 'Lethal Company', '2023-10-23', 'Co-op thám hiểm kinh dị', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1966720/header.jpg', 'Zeekerss', 'Zeekerss', 'Active', 'Intel Core i5-11400H, RTX 3050 4GB, 24GB RAM, 512GB SSD'),
(34, 'Sea of Thieves', '2020-06-03', 'Hành động cướp biển', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1172620/header.jpg', 'Xbox Game Studios', 'Rare', 'Active', 'Intel Core i5-11400H, RTX 3050 4GB, 24GB RAM, 512GB SSD'),
(35, 'Forza Horizon 5', '2021-11-09', 'Đua xe thế giới mở', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1551360/header.jpg', 'Xbox Game Studios', 'Playground Games', 'Active', 'Intel Core i5-11400H, RTX 3050 4GB, 24GB RAM, 512GB SSD'),
(36, 'EA SPORTS FC 24', '2023-09-29', 'Game bóng đá thực tế', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/2195250/header.jpg', 'Electronic Arts', 'EA Sports', 'Active', 'Intel Core i5-11400H, RTX 3050 4GB, 24GB RAM, 512GB SSD'),
(37, 'NBA 2K24', '2023-09-08', 'Bóng rổ chân thực', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/2338770/header.jpg', '2K', 'Visual Concepts', 'Active', 'Intel Core i5-11400H, RTX 3050 4GB, 24GB RAM, 512GB SSD'),
(38, 'Tekken 8', '2024-01-25', 'Đối kháng võ thuật 3D', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1778820/header.jpg', 'Bandai Namco', 'Bandai Namco', 'Active', 'Intel Core i5-11400H, RTX 3050 4GB, 24GB RAM, 512GB SSD'),
(39, 'Street Fighter 6', '2023-06-01', 'Siêu phẩm đối kháng 2D', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1364780/header.jpg', 'Capcom', 'Capcom', 'Active', 'Intel Core i5-11400H, RTX 3050 4GB, 24GB RAM, 512GB SSD'),
(40, 'Mortal Kombat 1', '2023-09-19', 'Đối kháng bạo lực', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1971870/header.jpg', 'Warner Bros', 'NetherRealm', 'Active', 'Intel Core i5-11400H, RTX 3050 4GB, 24GB RAM, 512GB SSD'),
(41, 'Monster Hunter: World', '2018-08-09', 'Săn quái vật khổng lồ', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/582010/header.jpg', 'Capcom', 'Capcom', 'Active', 'Intel Core i5-11400H, RTX 3050 4GB, 24GB RAM, 512GB SSD'),
(42, 'The Elder Scrolls V: Skyrim', '2016-10-27', 'RPG huyền thoại', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/489830/header.jpg', 'Bethesda Softworks', 'Bethesda Game Studios', 'Active', 'Intel Core i5-11400H, RTX 3050 4GB, 24GB RAM, 512GB SSD'),
(43, 'Fallout 4', '2015-11-10', 'Hành động sinh tồn hậu tận thế', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/377160/header.jpg', 'Bethesda Softworks', 'Bethesda Game Studios', 'Active', 'Intel Core i5-11400H, RTX 3050 4GB, 24GB RAM, 512GB SSD'),
(44, 'Starfield', '2023-09-06', 'RPG thám hiểm không gian', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1716740/header.jpg', 'Bethesda Softworks', 'Bethesda Game Studios', 'Active', 'Intel Core i5-11400H, RTX 3050 4GB, 24GB RAM, 512GB SSD'),
(45, 'No Man\'s Sky', '2016-08-12', 'Khám phá vũ trụ vô tận', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/275850/header.jpg', 'Hello Games', 'Hello Games', 'Active', 'Intel Core i5-11400H, RTX 3050 4GB, 24GB RAM, 512GB SSD'),
(46, 'Hollow Knight', '2017-02-24', 'Phiêu lưu Metroidvania tuyệt đỉnh', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/367520/header.jpg', 'Team Cherry', 'Team Cherry', 'Active', 'Intel Core i5-11400H, RTX 3050 4GB, 24GB RAM, 512GB SSD'),
(47, 'Hades', '2020-09-17', 'Hành động roguelike', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1145360/header.jpg', 'Supergiant Games', 'Supergiant Games', 'Active', 'Intel Core i5-11400H, RTX 3050 4GB, 24GB RAM, 512GB SSD'),
(48, 'Celeste', '2018-01-25', 'Nền tảng vượt ải thử thách', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/504230/header.jpg', 'Matt Makes Games', 'Matt Makes Games', 'Active', 'Intel Core i5-11400H, RTX 3050 4GB, 24GB RAM, 512GB SSD'),
(49, 'Dead Cells', '2018-08-06', 'Hành động rogue-lite nhịp độ nhanh', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/588650/header.jpg', 'Motion Twin', 'Motion Twin', 'Active', 'Intel Core i5-11400H, RTX 3050 4GB, 24GB RAM, 512GB SSD'),
(50, 'Slay the Spire', '2019-01-23', 'Thẻ bài roguelike chiến thuật', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/646570/header.jpg', 'Mega Crit Games', 'Mega Crit Games', 'Active', 'Intel Core i5-11400H, RTX 3050 4GB, 24GB RAM, 512GB SSD');

-- --------------------------------------------------------

--
-- Table structure for table `game_categories`
--

CREATE TABLE `game_categories` (
  `id` int(11) NOT NULL,
  `game_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `game_categories`
--

INSERT INTO `game_categories` (`id`, `game_id`, `category_id`) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 3, 1),
(4, 4, 2),
(5, 5, 1),
(6, 6, 1),
(7, 7, 4),
(8, 8, 3),
(9, 9, 3),
(10, 10, 4),
(11, 11, 3),
(12, 12, 3),
(13, 13, 8),
(14, 14, 8),
(15, 15, 8),
(16, 16, 1),
(17, 17, 1),
(18, 18, 6),
(19, 19, 8),
(20, 20, 10),
(21, 21, 1),
(22, 22, 4),
(23, 23, 1),
(24, 24, 9),
(25, 25, 1),
(26, 26, 1),
(27, 27, 8),
(28, 28, 10),
(29, 29, 9),
(30, 30, 4),
(31, 31, 8),
(32, 32, 1),
(33, 33, 10),
(34, 34, 9),
(35, 35, 7),
(36, 36, 6),
(37, 37, 6),
(38, 38, 4),
(39, 39, 4),
(40, 40, 4),
(41, 41, 4),
(42, 42, 3),
(43, 43, 3),
(44, 44, 3),
(45, 45, 9),
(46, 46, 9),
(47, 47, 4),
(48, 48, 9),
(49, 49, 4),
(50, 50, 5),
(66, 66, 10),
(67, 67, 10),
(68, 68, 8),
(69, 69, 8),
(70, 70, 8),
(71, 71, 8),
(72, 72, 8),
(73, 73, 10),
(74, 74, 10),
(75, 75, 10),
(76, 76, 10),
(77, 77, 10),
(78, 78, 10),
(79, 79, 10),
(80, 80, 10),
(81, 81, 5),
(82, 82, 5),
(83, 83, 5),
(84, 84, 5),
(85, 85, 5),
(86, 86, 5),
(87, 87, 8),
(88, 88, 8),
(89, 89, 5),
(90, 90, 8),
(91, 91, 8),
(92, 92, 8),
(93, 93, 8),
(94, 94, 8),
(95, 95, 8),
(96, 96, 8),
(97, 97, 8),
(98, 98, 8),
(99, 99, 7),
(100, 100, 7),
(101, 101, 7),
(102, 102, 7),
(103, 103, 7),
(104, 104, 6),
(105, 105, 4),
(106, 106, 3),
(107, 107, 3),
(108, 108, 4),
(109, 109, 3),
(110, 110, 4),
(111, 111, 4),
(112, 112, 3),
(113, 113, 4),
(114, 114, 4),
(115, 115, 4),
(116, 116, 4),
(117, 117, 4),
(118, 118, 4),
(119, 119, 4),
(120, 120, 9),
(121, 121, 4),
(122, 122, 4),
(123, 123, 4),
(124, 124, 1),
(125, 125, 1),
(126, 126, 4),
(127, 127, 1),
(128, 128, 9),
(129, 129, 9),
(130, 130, 9),
(131, 131, 1),
(132, 132, 1),
(133, 133, 4),
(135, 135, 1),
(136, 136, 1),
(137, 137, 1),
(138, 138, 1),
(139, 139, 1),
(140, 140, 1),
(141, 141, 1),
(142, 142, 1),
(143, 143, 1),
(144, 144, 4),
(145, 145, 1),
(146, 146, 1),
(147, 147, 4),
(148, 148, 10),
(149, 149, 4),
(150, 150, 4),
(151, 151, 9),
(198, 198, 4),
(199, 199, 4),
(200, 200, 4);

-- --------------------------------------------------------

--
-- Table structure for table `game_images`
--

CREATE TABLE `game_images` (
  `id` int(11) NOT NULL,
  `game_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `image_type` varchar(50) DEFAULT NULL,
  `game_part` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `game_images`
--

INSERT INTO `game_images` (`id`, `game_id`, `image_path`, `image_type`, `game_part`) VALUES
(4, 4, 'https://ddragon.leagueoflegends.com/cdn/img/champion/splash/Ahri_1.jpg', 'Screenshot', 'Gameplay'),
(66, 66, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1326470/ss_1.jpg', 'Screenshot', 'Gameplay'),
(67, 67, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/242760/ss_1.jpg', 'Screenshot', 'Gameplay'),
(68, 68, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/264710/ss_1.jpg', 'Screenshot', 'Gameplay'),
(69, 69, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/848450/ss_1.jpg', 'Screenshot', 'Gameplay'),
(70, 70, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/322330/ss_1.jpg', 'Screenshot', 'Gameplay'),
(71, 71, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/108600/ss_1.jpg', 'Screenshot', 'Gameplay'),
(72, 72, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/495420/ss_1.jpg', 'Screenshot', 'Gameplay'),
(73, 73, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/2050650/ss_1.jpg', 'Screenshot', 'Gameplay'),
(74, 74, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1196590/ss_1.jpg', 'Screenshot', 'Gameplay'),
(75, 75, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/883710/ss_1.jpg', 'Screenshot', 'Gameplay'),
(76, 76, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/238320/ss_1.jpg', 'Screenshot', 'Gameplay'),
(77, 77, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/414700/ss_1.jpg', 'Screenshot', 'Gameplay'),
(78, 78, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/228280/ss_1.jpg', 'Screenshot', 'Gameplay'),
(79, 79, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1693980/ss_1.jpg', 'Screenshot', 'Gameplay'),
(80, 80, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/214490/ss_1.jpg', 'Screenshot', 'Gameplay'),
(81, 81, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/289070/ss_1.jpg', 'Screenshot', 'Gameplay'),
(82, 82, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/281990/ss_1.jpg', 'Screenshot', 'Gameplay'),
(83, 83, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1158310/ss_1.jpg', 'Screenshot', 'Gameplay'),
(84, 84, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/394360/ss_1.jpg', 'Screenshot', 'Gameplay'),
(85, 85, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/813780/ss_1.jpg', 'Screenshot', 'Gameplay'),
(86, 86, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1466860/ss_1.jpg', 'Screenshot', 'Gameplay'),
(87, 87, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/255710/ss_1.jpg', 'Screenshot', 'Gameplay'),
(88, 88, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/949230/ss_1.jpg', 'Screenshot', 'Gameplay'),
(89, 89, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/323190/ss_1.jpg', 'Screenshot', 'Gameplay'),
(90, 90, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/294100/ss_1.jpg', 'Screenshot', 'Gameplay'),
(91, 91, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/227300/ss_1.jpg', 'Screenshot', 'Gameplay'),
(92, 92, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/270880/ss_1.jpg', 'Screenshot', 'Gameplay'),
(93, 93, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1250410/ss_1.jpg', 'Screenshot', 'Gameplay'),
(94, 94, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/427520/ss_1.jpg', 'Screenshot', 'Gameplay'),
(95, 95, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/526870/ss_1.jpg', 'Screenshot', 'Gameplay'),
(96, 96, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/703080/ss_1.jpg', 'Screenshot', 'Gameplay'),
(97, 97, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1248130/ss_1.jpg', 'Screenshot', 'Gameplay'),
(98, 98, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1222670/ss_1.jpg', 'Screenshot', 'Gameplay'),
(99, 99, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/2440510/ss_1.jpg', 'Screenshot', 'Gameplay'),
(100, 100, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/244210/ss_1.jpg', 'Screenshot', 'Gameplay'),
(101, 101, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1846380/ss_1.jpg', 'Screenshot', 'Gameplay'),
(102, 102, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/2108330/ss_1.jpg', 'Screenshot', 'Gameplay'),
(103, 103, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/690790/ss_1.jpg', 'Screenshot', 'Gameplay'),
(104, 104, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/2140330/ss_1.jpg', 'Screenshot', 'Gameplay'),
(105, 105, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/814380/ss_1.jpg', 'Screenshot', 'Gameplay'),
(106, 106, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/374320/ss_1.jpg', 'Screenshot', 'Gameplay'),
(107, 107, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1687950/ss_1.jpg', 'Screenshot', 'Gameplay'),
(108, 108, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/638970/ss_1.jpg', 'Screenshot', 'Gameplay'),
(109, 109, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/2070850/ss_1.jpg', 'Screenshot', 'Gameplay'),
(110, 110, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1446780/ss_1.jpg', 'Screenshot', 'Gameplay'),
(111, 111, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/601150/ss_1.jpg', 'Screenshot', 'Gameplay'),
(112, 112, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/524220/ss_1.jpg', 'Screenshot', 'Gameplay'),
(113, 113, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1593500/ss_1.jpg', 'Screenshot', 'Gameplay'),
(114, 114, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1817070/ss_1.jpg', 'Screenshot', 'Gameplay'),
(115, 115, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1817190/ss_1.jpg', 'Screenshot', 'Gameplay'),
(116, 116, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1151640/ss_1.jpg', 'Screenshot', 'Gameplay'),
(117, 117, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/2420110/ss_1.jpg', 'Screenshot', 'Gameplay'),
(118, 118, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1888930/ss_1.jpg', 'Screenshot', 'Gameplay'),
(119, 119, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1259420/ss_1.jpg', 'Screenshot', 'Gameplay'),
(120, 120, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1659420/ss_1.jpg', 'Screenshot', 'Gameplay'),
(121, 121, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/2208920/ss_1.jpg', 'Screenshot', 'Gameplay'),
(122, 122, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/812140/ss_1.jpg', 'Screenshot', 'Gameplay'),
(123, 123, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/582160/ss_1.jpg', 'Screenshot', 'Gameplay'),
(124, 124, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/2369390/ss_1.jpg', 'Screenshot', 'Gameplay'),
(125, 125, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/552520/ss_1.jpg', 'Screenshot', 'Gameplay'),
(126, 126, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/2239550/ss_1.jpg', 'Screenshot', 'Gameplay'),
(127, 127, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/460930/ss_1.jpg', 'Screenshot', 'Gameplay'),
(128, 128, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/750920/ss_1.jpg', 'Screenshot', 'Gameplay'),
(129, 129, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/391220/ss_1.jpg', 'Screenshot', 'Gameplay'),
(130, 130, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/203160/ss_1.jpg', 'Screenshot', 'Gameplay'),
(131, 131, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1240440/ss_1.jpg', 'Screenshot', 'Gameplay'),
(132, 132, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/976730/ss_1.jpg', 'Screenshot', 'Gameplay'),
(133, 133, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1097840/ss_1.jpg', 'Screenshot', 'Gameplay'),
(135, 135, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1938090/ss_1.jpg', 'Screenshot', 'Gameplay'),
(136, 136, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1985810/ss_1.jpg', 'Screenshot', 'Gameplay'),
(137, 137, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1517290/ss_1.jpg', 'Screenshot', 'Gameplay'),
(138, 138, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1238810/ss_1.jpg', 'Screenshot', 'Gameplay'),
(139, 139, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1238840/ss_1.jpg', 'Screenshot', 'Gameplay'),
(140, 140, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1237970/ss_1.jpg', 'Screenshot', 'Gameplay'),
(141, 141, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/782330/ss_1.jpg', 'Screenshot', 'Gameplay'),
(142, 142, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/379720/ss_1.jpg', 'Screenshot', 'Gameplay'),
(143, 143, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/612880/ss_1.jpg', 'Screenshot', 'Gameplay'),
(144, 144, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/403640/ss_1.jpg', 'Screenshot', 'Gameplay'),
(145, 145, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/480490/ss_1.jpg', 'Screenshot', 'Gameplay'),
(146, 146, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1252330/ss_1.jpg', 'Screenshot', 'Gameplay'),
(147, 147, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/870780/ss_1.jpg', 'Screenshot', 'Gameplay'),
(148, 148, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/108710/ss_1.jpg', 'Screenshot', 'Gameplay'),
(149, 149, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/474960/ss_1.jpg', 'Screenshot', 'Gameplay'),
(150, 150, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/204100/ss_1.jpg', 'Screenshot', 'Gameplay'),
(151, 151, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/110800/ss_1.jpg', 'Screenshot', 'Gameplay'),
(199, 199, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1895880/ss_1.jpg', 'Screenshot', 'Gameplay'),
(204, 198, '/storage/gallery/1ou1MN4MChtQ1Nka5vPtvoDNfXwog3BHUKPmcKN4.jpg', 'Screenshot', 'Gameplay'),
(206, 50, 'https://cdn-media.sforum.vn/storage/app/media/Bookgrinder2/danh-gia-early-access-slay-the-spire-2-1.jpg', 'Screenshot', 'Gameplay'),
(207, 50, 'https://play-lh.googleusercontent.com/UdbrOveoUCM54GPqg2G-U0rdOt3zdMeTw96760mB157NCvWd2j1nFPFQs4V64Udmyx6KKrXTkV1j9Llic-LnP5Y=w526-h296-rw', 'Screenshot', 'Gameplay'),
(208, 50, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTZmmASe9sP8ipsJKOxWihmp6tMm3qPPuK4tDopK5NdPlzaNrZSaUpTl2k&s=10', 'Screenshot', 'Gameplay'),
(209, 49, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTZgtVFxQU9dWel6NPunbDC-vQt80GxZzRM41RUAQoOrQ&s=10', 'Screenshot', 'Gameplay'),
(210, 49, 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/588650/ss_ac28000ade40cc2fe5c128f32ac98ba33c008a7a.1920x1080.jpg?t=1779086887', 'Screenshot', 'Gameplay'),
(212, 49, 'https://assetsio.gnwcdn.com/dead-cells-queen-and-the-sea-shark.jpg?width=690&quality=85&format=jpg&dpr=3&auto=webp', 'Screenshot', 'Gameplay'),
(213, 48, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQDXAcy_wRMjZewuRyOFgRr7oFp_e_1Vl-5ke9QLhRKWAga6FSPk9M-7ns&s=10', 'Screenshot', 'Gameplay'),
(214, 48, 'https://assets.nintendo.com/image/upload/f_auto/q_auto/dpr_1.5/c_scale,w_400/store/software/switch/70010000006442/691ba3e0801180a9864cc8a7694b6f98097f9d9799bc7e3dc6db92f086759252', 'Screenshot', 'Gameplay'),
(215, 48, 'https://image.api.playstation.com/cdn/UP2120/CUSA11302_00/FREE_CONTENT8dLigN91u10590gFwsBX/PREVIEW_SCREENSHOT2_161659.png', 'Screenshot', 'Gameplay'),
(216, 47, 'https://cdn-media.sforum.vn/storage/app/media/Bookgrinder2/hades-2-ra-mat-1.jpg', 'Screenshot', 'Gameplay'),
(217, 47, 'https://www.nintendo.com/eu/media/images/10_share_images/games_15/nintendo_switch_download_software_1/H2x1_NSwitchDS_Hades.png', 'Screenshot', 'Gameplay'),
(218, 47, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTLYsqk3ElPkYXeTwgqQVTmtKWRi1swaMFkLk6hlwmUNmu12jX0Ek1Xp_Ei&s=10', 'Screenshot', 'Gameplay'),
(219, 46, 'https://cdn-media.sforum.vn/storage/app/media/hoangnv/Hollow%20Knight%20Silksongq.jpg', 'Screenshot', 'Gameplay'),
(220, 46, 'https://gamelade.vn/wp-content/uploads/2025/09/fXnpuvisMEcNreX7gogg6K.jpg', 'Screenshot', 'Gameplay'),
(221, 46, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ5S9UrMIyHvHS-Gi0m2rKyPpOQcjVU-rckSTKGBLL4hJ8G0bUAWB6_SIbz&s=10', 'Screenshot', 'Gameplay'),
(222, 45, 'https://xboxwire.thesourcemediaassets.com/sites/2/2026/02/No-Mans-Sky-Remnant-Key-Art-165a5f0f9826ddb8a97d.jpg', 'Screenshot', 'Gameplay'),
(223, 45, 'https://kamikey.com/wp-content/uploads/2026/04/No-Mans-Sky-14.jpg', 'Screenshot', 'Gameplay'),
(224, 45, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSVUXhXjKlB_cdylWxi_USaXTF-ubXuaF2iEuM3HdetBdlmKFVyG4nrGMNy&s=10', 'Screenshot', 'Gameplay'),
(225, 44, 'https://photo2.tinhte.vn/data/attachment-files/2024/09/8445801_RT47jcRTS4Xq5jm3s2DrtF-1200-80.jpg', 'Screenshot', 'Gameplay'),
(226, 44, 'https://cdn.mos.cms.futurecdn.net/q5ZN58NJat9sBjQt4KKzvW.jpg', 'Screenshot', 'Gameplay'),
(227, 44, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR7axxuTxdpzSwtgP2m2H-INHYLU9-WfG_dwY_U3RP24pGD7KVZcDBlqLo&s=10', 'Screenshot', 'Gameplay'),
(228, 43, 'https://cellphones.com.vn/sforum/wp-content/uploads/2023/10/mat-hon-2-nam-dje-hoan-tat-fallout-4-thu-thach-100-va-khong-trung-thuong-thumb.jpg', 'Screenshot', 'Gameplay'),
(229, 43, 'https://cdn.mos.cms.futurecdn.net/3e3310653388e1ca665e3391a35874a4-1200-80.png', 'Screenshot', 'Gameplay'),
(230, 43, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT8UINogk6e0aIHrcefFdL24GW_0XgvipjDMlthR_Vea7lJsBfqKJUioRY&s=10', 'Screenshot', 'Gameplay'),
(231, 42, 'https://cdn.tgdd.vn/GameApp/4/250627/Screentshots/the-elder-scrolls-v-skyrim-kiet-tac-rpg-gia-tuong-the-12-09-2021-1.jpg', 'Screenshot', 'Gameplay'),
(232, 42, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT9A10HbRlP57V1GnU4Hwoji1STSmPA_lUh2VisSUFSDatuzD44-BN_wYYh&s=10', 'Screenshot', 'Gameplay'),
(233, 42, 'https://www.nintendo.com/eu/media/images/10_share_images/games_15/nintendo_switch_4/H2x1_NSwitch_TheElderScrollsVSkyrim_image1600w.jpg', 'Screenshot', 'Gameplay'),
(234, 41, 'https://monsterhunterworld.wiki.fextralife.com/file/Monster-Hunter-World/monster_hunter_world_wiki_fextralife_wiki_guide_600px.jpg', 'Screenshot', 'Gameplay'),
(235, 41, 'https://mh-hr.com.vn/wp-content/uploads/2025/10/Screenshot-2025-10-01-233607.jpg', 'Screenshot', 'Gameplay'),
(236, 41, 'https://cdn.mos.cms.futurecdn.net/cktziwMLuMkcv2CkqojB2k-1200-80.jpg', 'Screenshot', 'Gameplay'),
(237, 40, 'https://cdn.dlcompare.com/game_tetiere/upload/gameimage/file/36734.jpeg.webp', 'Screenshot', 'Gameplay'),
(238, 40, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQdUjJR2oelTzuDecV2X_Wk-Q6wH9UL-hcqBAVlO9NP4qHEaZryB5TKqS05&s=10', 'Screenshot', 'Gameplay'),
(239, 40, 'https://i.ytimg.com/vi/9NT7MUHRcKY/maxresdefault.jpg', 'Screenshot', 'Gameplay'),
(240, 39, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/3401790/2f8034b705037abd9ef0a5ac377f174cc4a94293/ss_2f8034b705037abd9ef0a5ac377f174cc4a94293.1920x1080.jpg?t=1749095975', 'Screenshot', 'Gameplay'),
(241, 39, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTP_FyUBTdWwjqxEltlUpS7SLf4moAgGm4goPFBcYErDz2njD6E0tRH1k0J&s=10', 'Screenshot', 'Gameplay'),
(242, 39, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRLQw8jUKMO4efk984rZzT3lR4Y38VxJ81arKfVjqGmcLR3GsN5MM0SYdE&s=10', 'Screenshot', 'Gameplay'),
(243, 38, 'https://cdn2.fptshop.com.vn/unsafe/Uploads/images/tin-tuc/176692/Originals/tekken-8-4%20(Custom).png', 'Screenshot', 'Gameplay'),
(244, 38, 'https://st.quantrimang.com/photos/image/2024/02/13/Cau-hinh-choi-Tekken-8-2.jpg', 'Screenshot', 'Gameplay'),
(245, 38, 'https://static.gamehub.vn/img/files/2024/01/29/Gamehubvn-tekken-8-khoi-dau-bung-no-tren-steam-2.jpg', 'Screenshot', 'Gameplay'),
(246, 37, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRlnpwTXYH76QH48WY-kcDjjTk7Z8_HNgIk9tSGPG6boCTvmMVdZpbWWV8&s=10', 'Screenshot', 'Gameplay'),
(247, 37, 'https://cdn2.fptshop.com.vn/unsafe/Uploads/images/tin-tuc/187149/Originals/nba-2k24-5.jpg', 'Screenshot', 'Gameplay'),
(248, 37, 'https://www.tncstore.vn/media/lib/02-04-2025/tnc-store-the-game-nintendo-nba-2k24-kobe-bryant2.jpg', 'Screenshot', 'Gameplay'),
(249, 36, 'https://cdn-media.sforum.vn/storage/app/media/wp-content/uploads/2023/09/cach-tai-ea-sports-fc-24-thumb.jpg', 'Screenshot', 'Gameplay'),
(250, 36, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSZVryS66iDNgkLKElVKsC2u3mLXebVUUY5demBBt_Scxg9oEeZakx_v_vz&s=10', 'Screenshot', 'Gameplay'),
(251, 36, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQHOX0ZMFcNionDIxXDIpXPFmoefvGo4DQofhpBQvAbodLFuYzMmPetfMY&s=10', 'Screenshot', 'Gameplay'),
(252, 35, 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/1613284/capsule_616x353.jpg?t=1703992150', 'Screenshot', 'Gameplay'),
(253, 35, 'https://cdn-media.sforum.vn/storage/app/media/nhuy/nhuy/Nhu-Y/cau-hinh-choi-forza-horizon-5-4.jpg', 'Screenshot', 'Gameplay'),
(254, 35, 'https://traxion.gg/wp-content/uploads/2025/01/Forza-Horizon-5-playlist-voting-2.jpg', 'Screenshot', 'Gameplay'),
(255, 34, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS8W3Q2ficYweiWcv0I4_1UyMj_wSJf-EiyWZQ8vTKlqA&s=10', 'Screenshot', 'Gameplay'),
(256, 34, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQKGmeGwLUSAwEq6RQ_TgjA7DbfZ_4hsvDcmVm0lvvcI0K7DyAxcevWtFQ&s=10', 'Screenshot', 'Gameplay'),
(257, 34, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ9PdZp9_tgbZNV31HQm1dcuD4nw9m_EXJ9crVKp0BwD2_uUsSuxdPA72M7&s=10', 'Screenshot', 'Gameplay'),
(258, 33, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1966720/ss_78075e9a94675823024f12fce9d69b243cca94f8.1920x1080.jpg?t=1775380053', 'Screenshot', 'Gameplay'),
(259, 33, 'https://assetsio.gnwcdn.com/Lethal-Company-business-walk.jpeg?width=1200&height=630&fit=crop&enable=upscale&auto=webp', 'Screenshot', 'Gameplay'),
(260, 33, 'https://i.ytimg.com/vi/qZmrV5dbmik/hq720.jpg?sqp=-oaymwEhCK4FEIIDSFryq4qpAxMIARUAAAAAGAElAADIQj0AgKJD&rs=AOn4CLDvFxWtAb8vHHGdI0rkLuMIWbjt6Q', 'Screenshot', 'Gameplay'),
(261, 32, 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/553850/f11c258b04a5e3e7771c1bab60f324ebf6c6c6fb/header.jpg?t=1779899567', 'Screenshot', 'Gameplay'),
(262, 32, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSceG44F6wwhwcEhpe9H6_h13Lp22U_3UvAX2RTd-wOoAjzOSpC7jsu7hk&s=10', 'Screenshot', 'Gameplay'),
(263, 32, 'https://i.ytimg.com/vi/1Ihs5xS0NKM/hq720.jpg?sqp=-oaymwEhCK4FEIIDSFryq4qpAxMIARUAAAAAGAElAADIQj0AgKJD&rs=AOn4CLBtMqiDRViT73EzvOhdtgxz8XLHNA', 'Screenshot', 'Gameplay'),
(264, 31, 'https://cdn-media.sforum.vn/storage/app/media/wp-content/uploads/2024/01/palworld-thumbnail.jpg', 'Screenshot', 'Gameplay'),
(265, 31, 'https://www.topgear.com/sites/default/files/images/news-article/2024/01/d712c29e11b2044be9b3d9447a839c44/Palworld.jpg?w=1280&h=720', 'Screenshot', 'Gameplay'),
(266, 31, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTYSEJCOPbpcMptJic2n8zS6MOeNqi78TDuynrm3UtqDX2wqVAdGpAij3w&s=10', 'Screenshot', 'Gameplay'),
(267, 30, 'https://assets.nintendo.com/image/upload/q_auto/f_auto/store/software/switch/70010000042975/937afd0c84319831009b44c93369faf0a2c926a454809f73523df9bfb6cf6233', 'Screenshot', 'Gameplay'),
(268, 30, 'https://img.utdstc.com/screen/422/1dd/4221dd42e2cd7e7f0f64dd3245bf988e5c7351784de4e78f76394615dd4e0409:600', 'Screenshot', 'Gameplay'),
(269, 30, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQkA7YrTU-ryROK4C9TaZ49k_jAfxVxTZasNR2nVwN4FA&s', 'Screenshot', 'Gameplay'),
(270, 29, 'https://cdn2.fptshop.com.vn/unsafe/1920x0/filters:format(webp):quality(75)/among_us_online_thumb_73b4a49c7b.jpg', 'Screenshot', 'Gameplay'),
(271, 29, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRh3PyoWdtoZZ9Re3ZcJ6v6loMtdw-p5Lq2dKpSqJcREMP-tYG8wUrlU-k&s=10', 'Screenshot', 'Gameplay'),
(272, 29, 'https://www.anphatpc.com.vn/media/news/2805_among_us_nintendo_switch_1608097196570.jpg', 'Screenshot', 'Gameplay'),
(273, 28, 'https://cellphones.com.vn/sforum/wp-content/uploads/2023/08/ba-hoa-ghe-tham-khien-phasmophobia-phai-doi-ngay-ra-mat-thumb.jpg', 'Screenshot', 'Gameplay'),
(274, 28, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ7c4DQ_lp6MBujm2zypWKOBMv3EWyNGzaqH0q4pgDqUF52d6vSO1VGFTw&s=10', 'Screenshot', 'Gameplay'),
(275, 28, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/257297201/d919e648e05dc0f79928d63d3def70804af08a6b/movie_232x130.jpg?t=1778584293', 'Screenshot', 'Gameplay'),
(276, 27, 'https://cellphones.com.vn/sforum/wp-content/uploads/2024/01/tac-gia-garrys-mod-ly-giai-quyet-dinh-danh-ban-quyen-cua-valve-nham-vao-game-fan-lam-thumb.jpg', 'Screenshot', 'Gameplay'),
(277, 27, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ4lAT4wDQsU1T9Gx2pbshZxbU82QJmwwrA-ypo9rH9UX7iTqJBLVzsKKE&s=10', 'Screenshot', 'Gameplay'),
(278, 27, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTRyxE0OSUangytYiykxGOYxRofq0h6KiaOaURjbq6y7CaRTThq-Zk9Shg&s=10', 'Screenshot', 'Gameplay'),
(279, 26, 'https://cdn2.fptshop.com.vn/unsafe/1920x0/filters:format(webp):quality(75)/2015_1_29_201501291033309560_Team_Fortress_2(1).jpg', 'Screenshot', 'Gameplay'),
(280, 26, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/2635440/c77ad643d98444923384b6d570a303f78f0af3f8/ss_c77ad643d98444923384b6d570a303f78f0af3f8.1920x1080.jpg?t=1763743452', 'Screenshot', 'Gameplay'),
(281, 26, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQN-1Izs3F_aKw39nPIs7LCvu1nf-IdmhA-Meuy_LZlOw&s=10', 'Screenshot', 'Gameplay'),
(282, 25, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ2bBeYtPG2nMhu0XT4wq7_zCHYOzEv0sTJ9m94j-S4E2fw4FPFi1OBHkA&s=10', 'Screenshot', 'Gameplay'),
(283, 25, 'https://tintuc-divineshop.cdn.vccloud.vn/wp-content/uploads/2024/11/hq720.jpg', 'Screenshot', 'Gameplay'),
(284, 25, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQBNWxZGIOWimTRE_4m0n3r5IiTdNS-zhAfTVlapVvwR9S7CnadrR3HmhYG&s=10', 'Screenshot', 'Gameplay'),
(285, 24, 'https://cdn.tgdd.vn/GameApp/4/249295/Screentshots/portal-2--game-nhap-vai-di-tim-an-so-danh-cho-pc-22-08-2021-2.jpg', 'Screenshot', 'Gameplay'),
(286, 24, 'https://i.guim.co.uk/img/static/sys-images/Technology/Pix/pictures/2011/4/15/1302883732465/Portal-2-007.jpg?width=465&dpr=1&s=none&crop=none', 'Screenshot', 'Gameplay'),
(287, 24, 'https://cdn.tgdd.vn/GameApp/4/249295/Screentshots/portal-2--game-nhap-vai-di-tim-an-so-danh-cho-pc-22-08-2021-1.jpg', 'Screenshot', 'Gameplay'),
(288, 23, 'https://cdn.hstatic.net/200000722513/file/cau-hinh-choi-left-4-dead-2-1_1b0ec4a9e75242eeb983245e63ea66b6.jpg', 'Screenshot', 'Gameplay'),
(289, 23, 'https://cellphones.com.vn/sforum/wp-content/uploads/2022/12/Screenshot_45.jpg', 'Screenshot', 'Gameplay'),
(290, 23, 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/550/ss_9488e329bb42d792a059fb44cb7135d25b6262f5.1920x1080.jpg?t=1772742214', 'Screenshot', 'Gameplay'),
(291, 22, 'https://cdn2.fptshop.com.vn/unsafe/Uploads/images/tin-tuc/169824/Originals/warframe-1.png', 'Screenshot', 'Gameplay'),
(292, 22, 'https://cdn.hstatic.net/200000722513/file/cau-hinh-choi-warframe-tren-pc-3_d6ca2739050c468cbf60598a81fa7734.jpg', 'Screenshot', 'Gameplay'),
(293, 22, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSZvHQRAf_ydsla1ngiYhWZFCVDjMKK8o6EjG5rN5PHgT1gbgSPM3wNlQU&s=10', 'Screenshot', 'Gameplay'),
(294, 21, 'https://cdn1.epicgames.com/offer/428115def4ca4deea9d69c99c5a5a99e/EN_Bungie_D2_v950_OfferLandscape_S1_2560x1440_2560x1440-61cd46cb2d27e41f358293225ec354c8', 'Screenshot', 'Gameplay'),
(295, 21, 'https://cavchronline.com/wp-content/uploads/2023/02/Prompt_-Destiny-2-Inventory-Screenshot-by-Thomas-Clark.png', 'Screenshot', 'Gameplay'),
(296, 21, 'https://phongvu.vn/cong-nghe/wp-content/uploads/2018/08/destiny-2-forsaken-se-co-ban-patch-day-one-anh-3.jpg', 'Screenshot', 'Gameplay'),
(297, 20, 'https://assets.deadbydaylight.com/10th_YR_Key_Art_Horizontal_1080x1920_f499973e7b.jpg', 'Screenshot', 'Gameplay'),
(298, 20, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/381210/a1fd8eb372877c6939bbd62ca325ad710b5e24c6/capsule_616x353_alt_assets_8.jpg?t=1782395976', 'Screenshot', 'Gameplay'),
(299, 20, 'https://cdn2.unrealengine.com/egs-deadbydaylightgoldedition-behaviourinteractive-editions-s1-2560x1440-4169096b6e12.jpg', 'Screenshot', 'Gameplay'),
(300, 19, 'https://ccdn.g-portal.com/xlarge_webp_News_header_image_1920px_Rust_Early_Access_1d5cdd8255.webp', 'Screenshot', 'Gameplay'),
(301, 19, 'https://images.squarespace-cdn.com/content/v1/64b9a29ce2f8b717c65126a7/c584a7c8-7207-4bfa-b417-a797dc2eba2f/rust+gameplay.jpg', 'Screenshot', 'Gameplay'),
(302, 19, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRRxUd6n26nzKW7_G6JYRQeL_qn1AHmDvPG0uN5JrdAxH3MeKdFOVvVVb1h&s=10', 'Screenshot', 'Gameplay'),
(303, 18, 'https://cellphones.com.vn/sforum/wp-content/uploads/2022/02/rl-platform-keyart-2019-2021-04-13.jpg', 'Screenshot', 'Gameplay'),
(304, 18, 'https://cdn1.epicgames.com/offer/9773aa1aa54f4f7b80e44bef04986cea/EGS_RocketLeague_PsyonixLLC_S1_2560x1440-1a37e26b20fb4f3ebd825e64bc7914eb', 'Screenshot', 'Gameplay'),
(305, 18, 'https://cms-assets.unrealengine.com/cmkr1i7c9047e07n0es5291ez/resize=fit:clip,height:720,width:1280/quality=value:50/output=format:webp/cmis3q7us1wos07oone26adc0', 'Screenshot', 'Gameplay'),
(306, 17, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTUCzqvWVzFRPecQVIvf7v_9PBq9jfDgDAPgPEZXBQ3Ix4rV5BW6SgZ7rA&s=10', 'Screenshot', 'Gameplay'),
(307, 17, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQBF3jku5m5KdYvfAazFZtO5R4amSkeWL2DbhcSUFZ52Z2EO17RdgNOdckt&s=10', 'Screenshot', 'Gameplay'),
(308, 17, 'https://file.hstatic.net/200000722513/article/overwatch-2-beta-review_99e64f1cecbc42e6ab0ab1e55d9c33e8_master.jpg', 'Screenshot', 'Gameplay'),
(309, 16, 'https://staticctf.ubisoft.com/J3yJr34U2pZ2Ieem48Dwy9uqj5PNUQTn/4IZecJyhvcIUxxu0Rd1vjX/99fe1a724d46a4d9ca70c76c7a78496f/r6s-homepage-meta__1_.jpg', 'Screenshot', 'Gameplay'),
(310, 16, 'https://cdn2.fptshop.com.vn/unsafe/Uploads/images/tin-tuc/174016/Originals/rainbow-six-siege-2.jpg', 'Screenshot', 'Gameplay'),
(311, 16, 'https://cdn-media.sforum.vn/storage/app/media/phuonganh/rainbow-six-siege.jpg', 'Screenshot', 'Gameplay'),
(312, 15, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTd_zpxHQG58rkzK2p9pJBbk0vkhzBIp_bShXsgoG1wBK0LiXt6I1Woark&s=10', 'Screenshot', 'Gameplay'),
(313, 15, 'https://cdn-media.sforum.vn/storage/app/media/tai-stardew-valley-thumbnail.jpg', 'Screenshot', 'Gameplay'),
(314, 15, 'https://file.hstatic.net/200000722513/article/stardew-valley_042b3ba652024c61af7df1f60583ea54_master.jpg', 'Screenshot', 'Gameplay'),
(315, 14, 'https://cellphones.com.vn/sforum/wp-content/uploads/2023/09/nha-phat-trien-terraria-quyen-tien-cho-engine-ma-nguon-mo-de-chong-lai-unity-thumb.jpg', 'Screenshot', 'Gameplay'),
(316, 14, 'https://cdn.dlcompare.com/others_jpg/upload/news/image/en-terraria-1-4-5-is-huge-bigges-f8b605e1-image-f8b605c9.jpg.webp', 'Screenshot', 'Gameplay'),
(317, 14, 'https://i.ytimg.com/vi/UGo7iMUcxLc/hq720.jpg?sqp=-oaymwE7CK4FEIIDSFryq4qpAy0IARUAAAAAGAElAADIQj0AgKJD8AEB-AG-B4AC0AWKAgwIABABGGUgWShMMA8=&rs=AOn4CLA4WZ4A2oQUuSywx28JZK76MenWBg', 'Screenshot', 'Gameplay'),
(318, 13, 'https://cdn-media.sforum.vn/storage/app/media/bookgrinder/minecraft-vr-.jpg', 'Screenshot', 'Gameplay'),
(319, 13, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSDiiRuA-R9Vb5bk7Eyi5g8705r3THGEm1pK6fUbhbQrF09GaYi5slhwnwE&s=10', 'Screenshot', 'Gameplay'),
(320, 13, 'https://image.dienthoaivui.com.vn/x,webp,q90/https://media-asset.dienthoaivui.com.vn/uploads/dashboard/editor_upload/minecraft-ra-doi-nam-nao-3.jpg', 'Screenshot', 'Gameplay'),
(321, 12, 'https://sm.pcmag.com/pcmag_uk/photo/default/01u6e0flowo77jd3c3p4vaf-18_h18d.jpg', 'Screenshot', 'Gameplay'),
(322, 12, 'https://gamek.mediacdn.vn/133514250583805952/2023/8/28/8829371994611563-1691465363601-1691465364410810961003-1691475236244-16914752363351897498845-1693206246004-16932062463281986979985-1693210221470-1693210221811162294329.jpg', 'Screenshot', 'Gameplay'),
(323, 12, 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/2378500/ss_3250c1e6aeae1968dfa085eac1cd33da2b00b253.1920x1080.jpg?t=1692306965', 'Screenshot', 'Gameplay'),
(324, 11, 'https://i.ytimg.com/vi/OT8if6DXOFQ/maxresdefault.jpg', 'Screenshot', 'Gameplay'),
(325, 11, 'https://d28jzcg6y4v9j1.cloudfront.net/media/social/articles/2023/2/28/elden-ring-bosses-in-order-main-mandatory-8042-1647011924713%20(1).jpg', 'Screenshot', 'Gameplay'),
(326, 11, 'https://cdn.tgdd.vn/GameApp/4/273501/Screentshots/elden-ring-tro-thanh-chua-te-elden-o-vung-dat-giua-02-03-2022-2.jpg', 'Screenshot', 'Gameplay'),
(327, 10, 'https://cdn.tgdd.vn/Files/2018/11/04/1128872/red-dead-redemption2_800x450.jpg', 'Screenshot', 'Gameplay'),
(328, 10, 'https://cdn2.fptshop.com.vn/unsafe/Uploads/images/tin-tuc/169463/Originals/Red-Dead-Redemption-2-7.jpg', 'Screenshot', 'Gameplay'),
(329, 10, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSTcolb7lDONdUe3wSps1G5JJfkW1RKwhgZB9wKr2SQL3Pn4UFg2FFPlF9d&s=10', 'Screenshot', 'Gameplay'),
(330, 9, 'https://cdn1.epicgames.com/offer/14ee004dadc142faaaece5a6270fb628/EGS_TheWitcher3WildHuntCompleteEdition_CDPROJEKTRED_S1_2560x1440-82eb5cf8f725e329d3194920c0c0b64f', 'Screenshot', 'Gameplay'),
(331, 9, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQR4TC0DME-n0K3CG-shiMOYuNIf5SSPqcEtIMbLOcuq5ZRCtN6RjLHi6o&s=10', 'Screenshot', 'Gameplay'),
(332, 9, 'https://cdn2.fptshop.com.vn/unsafe/Uploads/images/tin-tuc/176398/Originals/wild-hunt-1.jpg', 'Screenshot', 'Gameplay'),
(333, 8, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1091500/644eb087007768417a847e52c38eeaf34b57fd12/page_bg_raw.jpg?t=1769690377', 'Screenshot', 'Gameplay'),
(334, 8, 'https://sm.ign.com/t/ign_nl/video/c/cyberpunk-/cyberpunk-2077-ultimate-edition-official-nintendo-switch-2-l_zja6.1280.png', 'Screenshot', 'Gameplay'),
(335, 8, 'https://kamikey.com/wp-content/uploads/2024/12/Cyberpunk-2077-5.jpg', 'Screenshot', 'Gameplay'),
(336, 7, 'https://cellphones.com.vn/sforum/wp-content/uploads/2023/01/gta-5-thumb.jpg', 'Screenshot', 'Gameplay'),
(337, 7, 'https://images2.thanhnien.vn/zoom/686_429/Uploaded/badiep/2020_12_28/res_33ce7d5f5baeb85748f744ec9c5ac79b_QYBQ.jpg', 'Screenshot', 'Gameplay'),
(338, 7, 'https://store-images.s-microsoft.com/image/apps.28865.13827124301078315.8451c006-d969-493e-b6bd-27c25b136528.ecb452ef-a496-4fd7-90d4-c3a4375c0c6f', 'Screenshot', 'Gameplay'),
(339, 6, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1172470/0bd74245b869287a2dc7f682e6013f7ed08d98e3/header.jpg?t=1778502442', 'Screenshot', 'Gameplay'),
(340, 6, 'https://cdn.hstatic.net/200000722513/file/cau-hinh-choi-apex-legends-1_4fc17319ec9e40ca8f833c3586edc8b3.jpg', 'Screenshot', 'Gameplay'),
(341, 6, 'https://help.ea.com/_images/seegk6e7ypwi/34VPA47vjGmX0ii7UNxoYP/064d0544538aa676a76cdad8e861ee57/apex-legends-drop.webp', 'Screenshot', 'Gameplay'),
(342, 5, 'https://cdn1.epicgames.com/spt-assets/53ec4985296b4facbe3a8d8d019afba9/pubg-battlegrounds-16v1j.jpg', 'Screenshot', 'Gameplay'),
(343, 5, 'https://i.ytimg.com/vi/e90WhwN2QdQ/maxresdefault.jpg', 'Screenshot', 'Gameplay'),
(344, 5, 'https://cdn.tgdd.vn/Files/2021/08/12/1374628/pubg_2_1280x720-800-resize.jpg', 'Screenshot', 'Gameplay'),
(345, 4, 'https://cdn1.epicgames.com/offer/24b9b5e323bc40eea252a10cdd3b2f10/EGS_LeagueofLegends_RiotGames_S1_2560x1440-47eb328eac5ddd63ebd096ded7d0d5ab', 'Screenshot', 'Gameplay'),
(346, 4, 'https://cdn.tgdd.vn/2020/10/campaign/thumb-thuatngu-640x360.jpg', 'Screenshot', 'Gameplay'),
(347, 3, 'https://cmsassets.rgpub.io/sanity/images/dsfx7636/news/1c1776bd1bdd921061a53953d81a393ef69ce633-1920x1080.jpg?accountingTag=VAL', 'Screenshot', 'Gameplay'),
(348, 3, 'https://cdn.tgdd.vn/Files/2020/04/17/1249881/valorant-2_800x450.jpg', 'Screenshot', 'Gameplay'),
(349, 3, 'https://cdn1.epicgames.com/offer/cbd5b3d310a54b12bf3fe8c41994174f/EGS_VALORANT_RiotGames_S1_2560x1440-fcc266a074444c3ca10fd9c03a3ae0b9', 'Screenshot', 'Gameplay'),
(350, 2, 'https://miro.medium.com/v2/resize:fit:1200/1*bjX2HdOx9RyFecPCbmOl4w.jpeg', 'Screenshot', 'Gameplay'),
(351, 2, 'https://cdn-media.sforum.vn/storage/app/media/nhuy/Nhu-Y-1/cau-hinh-choi-dota-2-1.jpg', 'Screenshot', 'Gameplay'),
(352, 2, 'https://clan.fastly.steamstatic.com/images/3703047/3728d0dc0f78d43a27b431f8b5607ee31ca0987d.png', 'Screenshot', 'Gameplay'),
(353, 1, 'https://gamelade.vn/wp-content/uploads/2025/10/ccccc.jpg', 'Screenshot', 'Gameplay'),
(354, 1, 'https://www.anphatpc.com.vn/media/news/0604_Counter-Strike2vacnhhng2.png', 'Screenshot', 'Gameplay'),
(355, 1, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTPOPpAyrwDp3fYtjyNJCPFMBQKBDSrgAXSlrwoujWyxDteuN0HhZGR227R&s=10', 'Screenshot', 'Gameplay');

-- --------------------------------------------------------

--
-- Table structure for table `game_keys`
--

CREATE TABLE `game_keys` (
  `id` int(11) NOT NULL,
  `order_item_id` int(11) DEFAULT NULL,
  `game_version_id` bigint(20) UNSIGNED DEFAULT NULL,
  `key_code` varchar(100) NOT NULL,
  `status` varchar(50) DEFAULT 'Delivered',
  `fetched_at` datetime NOT NULL,
  `supplier_transaction_id` varchar(150) NOT NULL,
  `supplier_code` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `game_keys`
--

INSERT INTO `game_keys` (`id`, `order_item_id`, `game_version_id`, `key_code`, `status`, `fetched_at`, `supplier_transaction_id`, `supplier_code`) VALUES
(1, NULL, NULL, 'fâfa', 'Activated', '2026-06-22 06:31:32', 'cs2', NULL),
(2, NULL, 1, 'gamengon', 'Activated', '2026-06-22 09:10:38', 'tăng cs2', NULL),
(3, 10, NULL, 'STEAM-100200-44B08F49-0000', 'Pending', '2026-06-22 09:14:24', '09873885-8504-4335-acdc-dc1497e12b73', 'SUPPLIER_A'),
(4, 11, NULL, 'STEAM-100008-786E82B0-0000', 'Pending', '2026-06-22 20:53:50', '971483ba-16be-4228-9555-de8ea771f186', 'SUPPLIER_A'),
(5, 11, NULL, 'STEAM-100008-5ED123B8-0001', 'Pending', '2026-06-22 20:53:50', '971483ba-16be-4228-9555-de8ea771f186', 'SUPPLIER_A'),
(6, 13, NULL, 'STEAM-100198-E7E7E91E-0000', 'Pending', '2026-06-22 20:53:50', '1a7256ce-2020-4143-8683-acb86b29853f', 'SUPPLIER_A'),
(7, 14, NULL, 'STEAM-100131-A1D9EEB6-0000', 'Pending', '2026-06-22 20:53:50', '851c9a5b-50d8-4585-bb9c-cd073885ed71', 'SUPPLIER_A'),
(8, 15, NULL, 'STEAM-100135-E6718AFB-0000', 'Pending', '2026-06-22 20:53:50', '9a369042-42fe-45a3-a07c-da7c57906760', 'SUPPLIER_A'),
(9, 15, NULL, 'STEAM-100135-9DCA108C-0001', 'Pending', '2026-06-22 20:53:50', '9a369042-42fe-45a3-a07c-da7c57906760', 'SUPPLIER_A'),
(10, 16, 1, 'FREE-6A4D676CBC98E-1', 'Pending', '2026-07-07 20:54:04', 'FREE-GAME-1', 'INTERNAL'),
(11, 17, 1, 'FREE-6A4D67A19FE7E-1', 'Revoked', '2026-07-07 20:54:57', 'REFUNDED: FREE-6A4D67A19FE7E-1', 'INTERNAL'),
(12, 18, 32, 'STEAM-100032-74542043-0000', 'Pending', '2026-07-07 20:55:51', '5a58630d-57ea-4f72-b3d2-820e09b62098', 'SUPPLIER_A'),
(13, 18, 32, 'STEAM-100032-16C2851D-0001', 'Pending', '2026-07-07 20:55:51', '5a58630d-57ea-4f72-b3d2-820e09b62098', 'SUPPLIER_A'),
(14, 18, 32, 'STEAM-100032-70CF181D-0002', 'Pending', '2026-07-07 20:55:51', '5a58630d-57ea-4f72-b3d2-820e09b62098', 'SUPPLIER_A'),
(15, 18, 32, 'STEAM-100032-C753FF70-0003', 'Pending', '2026-07-07 20:55:51', '5a58630d-57ea-4f72-b3d2-820e09b62098', 'SUPPLIER_A'),
(16, 19, 32, 'STEAM-100032-D330FE19-0004', 'Pending', '2026-07-07 20:56:36', '32662cda-1139-4331-a676-249f4a090dbf', 'SUPPLIER_A'),
(17, 20, 8, 'STEAM-100008-674CE722-0000', 'Pending', '2026-07-07 20:56:49', 'cb31fcb6-5eb5-404b-ba10-b2b934ed06c0', 'SUPPLIER_A'),
(18, 21, 32, 'STEAM-100032-0BAC737D-0005', 'Pending', '2026-07-07 20:57:01', '58236b53-9df5-4da5-bcf9-6ab4bb303545', 'SUPPLIER_A'),
(19, 22, 32, 'STEAM-100032-0F4D22AA-0006', 'Pending', '2026-07-07 21:00:28', '855b3e9a-5a75-467b-9d87-91a7ec847296', 'SUPPLIER_A'),
(20, 23, 32, 'STEAM-100032-DC75F865-0007', 'Pending', '2026-07-07 21:07:46', '50361e8b-9575-4a71-9473-9be91c11fbd6', 'SUPPLIER_A'),
(21, 24, 32, 'STEAM-100032-D0FD3C3C-0008', 'Pending', '2026-07-07 21:07:59', 'b4a1b1ff-7145-41b3-92e3-7fa1fca3fef7', 'SUPPLIER_A'),
(22, 25, 32, 'STEAM-100032-F1C60941-0009', 'Pending', '2026-07-07 21:08:12', 'f99ce36b-0529-42ab-977a-d53bef0d0103', 'SUPPLIER_A'),
(23, 26, 32, 'STEAM-100032-8F89A5DD-0010', 'Pending', '2026-07-07 21:08:23', 'ee612ae3-f535-4b0b-a2ce-5b60caf18cff', 'SUPPLIER_A'),
(24, 27, 32, 'STEAM-100032-54CDEBDB-0011', 'Pending', '2026-07-07 21:10:11', '48803496-ac53-4377-8f78-372f201f1663', 'SUPPLIER_A'),
(25, 29, 32, 'STEAM-100032-BF7E7BE8-0012', 'Pending', '2026-07-07 21:17:31', 'e320a002-faf9-4f7d-ad45-7a2efd37482d', 'SUPPLIER_A'),
(26, 30, 32, 'STEAM-100032-C2BEC5D9-0013', 'Pending', '2026-07-07 21:17:45', '620191ca-c338-45c7-bd83-8a28e0960532', 'SUPPLIER_A'),
(27, 31, 32, 'STEAM-100032-2ECCA522-0014', 'Pending', '2026-07-07 21:18:03', 'c8eacb12-c1ad-4ae2-8db7-cb9be7100291', 'SUPPLIER_A');

-- --------------------------------------------------------

--
-- Table structure for table `game_supplier_mappings`
--

CREATE TABLE `game_supplier_mappings` (
  `id` int(11) NOT NULL,
  `game_id` int(11) NOT NULL,
  `supplier_provider_id` int(11) NOT NULL,
  `supplier_game_id` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `game_supplier_mappings`
--

INSERT INTO `game_supplier_mappings` (`id`, `game_id`, `supplier_provider_id`, `supplier_game_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 85, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(2, 86, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(3, 148, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(4, 80, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(5, 92, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(6, 78, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(7, 29, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(8, 6, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(9, 155, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(10, 122, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(11, 123, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(12, 121, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(13, 100, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(14, 12, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(15, 139, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(16, 137, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(17, 138, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(18, 58, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(19, 169, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(20, 136, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(21, 135, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(22, 134, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(23, 48, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(24, 87, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(25, 88, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(26, 188, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(27, 147, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(28, 1, 2, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-23 15:32:11'),
(29, 167, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(30, 83, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(31, 171, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(32, 177, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(33, 8, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(34, 106, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(35, 174, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(36, 175, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(37, 119, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(38, 159, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(39, 20, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(40, 49, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(41, 79, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(42, 146, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(43, 21, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(44, 111, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(45, 61, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(46, 103, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(47, 180, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(48, 144, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(49, 181, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(50, 70, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(51, 142, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(52, 141, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(53, 2, 2, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-23 15:32:25'),
(54, 183, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(55, 191, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(56, 192, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(57, 36, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(58, 11, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(59, 65, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(60, 161, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(61, 91, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(62, 102, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(63, 94, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(64, 30, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(65, 43, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(66, 125, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(67, 124, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(68, 97, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(69, 56, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(70, 35, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(71, 99, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(72, 89, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(73, 27, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(74, 133, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(75, 54, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(76, 195, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(77, 198, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(78, 113, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(79, 7, 2, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-23 15:56:57'),
(80, 57, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(81, 47, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(82, 25, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(83, 170, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(84, 131, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(85, 132, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(86, 84, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(87, 157, 1, NULL, 'Active', '2026-06-08 09:57:50', '2026-06-08 09:57:50'),
(88, 32, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(89, 52, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(90, 153, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(91, 46, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(92, 53, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(93, 117, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(94, 116, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(95, 160, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(96, 172, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(97, 158, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(98, 184, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(99, 189, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(100, 190, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(101, 151, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(102, 4, 2, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-23 15:56:46'),
(103, 23, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(104, 33, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(105, 109, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(106, 173, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(107, 62, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(108, 104, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(109, 152, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(110, 114, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(111, 115, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(112, 182, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(113, 150, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(114, 93, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(115, 13, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(116, 110, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(117, 41, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(118, 40, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(119, 185, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(120, 37, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(121, 101, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(122, 63, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(123, 112, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(124, 45, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(125, 178, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(126, 179, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(127, 196, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(128, 76, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(129, 77, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(130, 17, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(131, 31, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(132, 60, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(133, 163, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(134, 162, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(135, 107, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(136, 28, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(137, 96, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(138, 24, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(139, 145, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(140, 71, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(141, 5, 2, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-23 15:56:52'),
(142, 149, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(143, 199, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(144, 10, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(145, 75, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(146, 73, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(147, 74, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(148, 200, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(149, 90, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(150, 129, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(151, 18, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(152, 19, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(153, 95, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(154, 34, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(155, 105, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(156, 128, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(157, 81, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(158, 50, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(159, 193, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(160, 194, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(161, 51, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(162, 154, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(163, 66, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(164, 156, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(165, 15, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(166, 44, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(167, 72, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(168, 82, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(169, 39, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(170, 68, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(171, 69, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(172, 26, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(173, 38, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(174, 14, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(175, 59, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(176, 42, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(177, 67, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(178, 118, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(179, 98, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(180, 9, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(181, 140, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(182, 127, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(183, 16, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(184, 130, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(185, 187, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(186, 186, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(187, 120, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(188, 176, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(189, 64, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(190, 197, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(191, 3, 2, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-23 15:32:18'),
(192, 168, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(193, 164, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(194, 22, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(195, 126, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(196, 143, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(197, 165, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(198, 55, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(199, 166, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(200, 108, 1, NULL, 'Active', '2026-06-08 09:57:51', '2026-06-08 09:57:51'),
(201, 201, 1, NULL, 'Active', '2026-06-09 07:59:12', '2026-06-09 07:59:12'),
(202, 202, 1, NULL, 'Active', '2026-06-09 10:03:28', '2026-06-09 10:03:28');

-- --------------------------------------------------------

--
-- Table structure for table `game_versions`
--

CREATE TABLE `game_versions` (
  `id` int(11) NOT NULL,
  `game_id` int(11) NOT NULL,
  `promotion_id` int(11) DEFAULT NULL,
  `version_name` varchar(100) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `discount_price` decimal(15,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `game_versions`
--

INSERT INTO `game_versions` (`id`, `game_id`, `promotion_id`, `version_name`, `price`, `discount_price`) VALUES
(1, 1, NULL, 'Standard Edition', 0.00, 0.00),
(2, 2, NULL, 'Standard Edition', 0.00, 0.00),
(3, 3, NULL, 'Standard Edition', 0.00, 0.00),
(4, 4, NULL, 'Standard Edition', 0.00, 0.00),
(5, 5, NULL, 'Standard Edition', 0.00, 0.00),
(6, 6, NULL, 'Standard Edition', 0.00, 0.00),
(7, 7, NULL, 'Premium Edition', 29.99, NULL),
(8, 8, NULL, 'Standard Edition', 59.99, NULL),
(9, 9, NULL, 'Game of the Year', 39.99, NULL),
(10, 10, NULL, 'Ultimate Edition', 99.99, 99.99),
(11, 11, NULL, 'Standard Edition', 59.99, 59.99),
(12, 12, NULL, 'Deluxe Edition', 69.99, NULL),
(13, 13, NULL, 'Java Edition', 26.95, 26.95),
(14, 14, NULL, 'Standard Edition', 9.99, 9.99),
(15, 15, NULL, 'Standard Edition', 14.99, 14.99),
(16, 16, NULL, 'Deluxe Edition', 39.99, NULL),
(17, 17, NULL, 'Standard Edition', 0.00, 0.00),
(18, 18, NULL, 'Standard Edition', 0.00, 0.00),
(19, 19, NULL, 'Standard Edition', 39.99, NULL),
(20, 20, NULL, 'Standard Edition', 19.99, 19.99),
(21, 21, NULL, 'Standard Edition', 0.00, 0.00),
(22, 22, NULL, 'Standard Edition', 0.00, 0.00),
(23, 23, NULL, 'Standard Edition', 9.99, NULL),
(24, 24, NULL, 'Standard Edition', 9.99, 9.99),
(25, 25, NULL, 'Standard Edition', 9.99, 9.99),
(26, 26, NULL, 'Standard Edition', 0.00, 0.00),
(27, 27, NULL, 'Standard Edition', 9.99, NULL),
(28, 28, NULL, 'Standard Edition', 13.99, 13.99),
(29, 29, NULL, 'Standard Edition', 4.99, 4.99),
(30, 30, NULL, 'Standard Edition', 0.00, 0.00),
(31, 31, NULL, 'Standard Edition', 29.99, 29.99),
(32, 32, NULL, 'Standard Edition', 39.99, NULL),
(33, 33, NULL, 'Standard Edition', 9.99, 9.99),
(34, 34, NULL, 'Standard Edition', 39.99, NULL),
(35, 35, NULL, 'Standard Edition', 59.99, 59.99),
(36, 36, NULL, 'Standard Edition', 69.99, NULL),
(37, 37, NULL, 'Standard Edition', 59.99, 59.99),
(38, 38, NULL, 'Standard Edition', 69.99, 69.99),
(39, 39, NULL, 'Standard Edition', 59.99, NULL),
(40, 40, NULL, 'Standard Edition', 69.99, 69.99),
(41, 41, NULL, 'Standard Edition', 29.99, NULL),
(42, 42, NULL, 'Special Edition', 39.99, 39.99),
(43, 43, NULL, 'GOTY Edition', 39.99, 39.99),
(44, 44, NULL, 'Standard Edition', 69.99, NULL),
(45, 45, NULL, 'Standard Edition', 59.99, NULL),
(46, 46, NULL, 'Standard Edition', 14.99, 14.99),
(47, 47, NULL, 'Standard Edition', 24.99, 24.99),
(48, 48, NULL, 'Standard Edition', 19.99, NULL),
(49, 49, NULL, 'Standard Edition', 24.99, 24.99),
(50, 50, NULL, 'Standard Edition', 24.99, 24.99),
(66, 66, NULL, 'Standard Edition', 29.99, NULL),
(67, 67, NULL, 'Standard Edition', 19.99, 19.99),
(68, 68, NULL, 'Standard Edition', 29.99, NULL),
(69, 69, NULL, 'Standard Edition', 29.99, 29.99),
(70, 70, NULL, 'Standard Edition', 14.99, 14.99),
(71, 71, NULL, 'Standard Edition', 19.99, NULL),
(72, 72, NULL, 'Standard Edition', 29.99, 29.99),
(73, 73, NULL, 'Standard Edition', 59.99, NULL),
(74, 74, NULL, 'Standard Edition', 39.99, 39.99),
(75, 75, NULL, 'Standard Edition', 39.99, NULL),
(76, 76, NULL, 'Standard Edition', 19.99, 19.99),
(77, 77, NULL, 'Standard Edition', 29.99, NULL),
(78, 78, NULL, 'Standard Edition', 19.99, 19.99),
(79, 79, NULL, 'Standard Edition', 59.99, NULL),
(80, 80, NULL, 'Standard Edition', 39.99, 39.99),
(81, 81, NULL, 'Standard Edition', 59.99, NULL),
(82, 82, NULL, 'Standard Edition', 39.99, 39.99),
(83, 83, NULL, 'Standard Edition', 49.99, NULL),
(84, 84, NULL, 'Standard Edition', 39.99, 39.99),
(85, 85, NULL, 'Standard Edition', 19.99, NULL),
(86, 86, NULL, 'Standard Edition', 39.99, 39.99),
(87, 87, NULL, 'Standard Edition', 29.99, NULL),
(88, 88, NULL, 'Standard Edition', 49.99, 49.99),
(89, 89, NULL, 'Standard Edition', 29.99, NULL),
(90, 90, NULL, 'Standard Edition', 34.99, 34.99),
(91, 91, NULL, 'Standard Edition', 19.99, NULL),
(92, 92, NULL, 'Standard Edition', 19.99, 19.99),
(93, 93, NULL, 'Standard Edition', 59.99, NULL),
(94, 94, NULL, 'Standard Edition', 35.00, 35.00),
(95, 95, NULL, 'Standard Edition', 29.99, NULL),
(96, 96, NULL, 'Standard Edition', 44.99, 44.99),
(97, 97, NULL, 'Standard Edition', 29.99, NULL),
(98, 98, NULL, 'Standard Edition', 0.00, 0.00),
(99, 99, NULL, 'Standard Edition', 69.99, NULL),
(100, 100, NULL, 'Standard Edition', 19.99, 19.99),
(101, 101, NULL, 'Standard Edition', 69.99, NULL),
(102, 102, NULL, 'Standard Edition', 69.99, 69.99),
(103, 103, NULL, 'Standard Edition', 19.99, NULL),
(104, 104, NULL, 'Standard Edition', 69.99, 69.99),
(105, 105, NULL, 'Standard Edition', 59.99, NULL),
(106, 106, NULL, 'Standard Edition', 59.99, 59.99),
(107, 107, NULL, 'Standard Edition', 59.99, NULL),
(108, 108, NULL, 'Standard Edition', 19.99, 19.99),
(109, 109, NULL, 'Standard Edition', 69.99, NULL),
(110, 110, NULL, 'Standard Edition', 39.99, 39.99),
(111, 111, NULL, 'Standard Edition', 29.99, NULL),
(112, 112, NULL, 'Standard Edition', 39.99, 39.99),
(113, 113, NULL, 'Standard Edition', 49.99, NULL),
(114, 114, NULL, 'Standard Edition', 59.99, 59.99),
(115, 115, NULL, 'Standard Edition', 49.99, NULL),
(116, 116, NULL, 'Standard Edition', 49.99, 49.99),
(117, 117, NULL, 'Standard Edition', 59.99, NULL),
(118, 118, NULL, 'Standard Edition', 59.99, 59.99),
(119, 119, NULL, 'Standard Edition', 49.99, NULL),
(120, 120, NULL, 'Standard Edition', 49.99, 49.99),
(121, 121, NULL, 'Standard Edition', 59.99, NULL),
(122, 122, NULL, 'Standard Edition', 59.99, 59.99),
(123, 123, NULL, 'Standard Edition', 59.99, NULL),
(124, 124, NULL, 'Standard Edition', 59.99, 59.99),
(125, 125, NULL, 'Standard Edition', 59.99, NULL),
(126, 126, NULL, 'Standard Edition', 59.99, 59.99),
(127, 127, NULL, 'Standard Edition', 49.99, NULL),
(128, 128, NULL, 'Standard Edition', 39.99, 39.99),
(129, 129, NULL, 'Standard Edition', 29.99, NULL),
(130, 130, NULL, 'Standard Edition', 14.99, 14.99),
(131, 131, NULL, 'Campaign Edition', 59.99, NULL),
(132, 132, NULL, 'Standard Edition', 39.99, 39.99),
(133, 133, NULL, 'Standard Edition', 39.99, NULL),
(135, 135, NULL, 'Standard Edition', 69.99, NULL),
(136, 136, NULL, 'Standard Edition', 59.99, 59.99),
(137, 137, NULL, 'Standard Edition', 59.99, NULL),
(138, 138, NULL, 'Standard Edition', 49.99, 49.99),
(139, 139, NULL, 'Standard Edition', 39.99, NULL),
(140, 140, NULL, 'Standard Edition', 29.99, 29.99),
(141, 141, NULL, 'Standard Edition', 39.99, NULL),
(142, 142, NULL, 'Standard Edition', 19.99, 19.99),
(143, 143, NULL, 'Standard Edition', 39.99, NULL),
(144, 144, NULL, 'Standard Edition', 29.99, 29.99),
(145, 145, NULL, 'Standard Edition', 29.99, NULL),
(146, 146, NULL, 'Standard Edition', 59.99, 59.99),
(147, 147, NULL, 'Ultimate Edition', 39.99, NULL),
(148, 148, NULL, 'Standard Edition', 49.99, 49.99),
(149, 149, NULL, 'Standard Edition', 39.99, NULL),
(150, 150, NULL, 'Standard Edition', 19.99, 19.99),
(151, 151, NULL, 'Standard Edition', 19.99, NULL),
(198, 198, NULL, 'Standard Edition', 59.99, 59.99),
(199, 199, NULL, 'Standard Edition', 59.99, NULL),
(200, 200, 4, 'Standard Edition', 59.99, 50.99);

-- --------------------------------------------------------

--
-- Table structure for table `gifts`
--

CREATE TABLE `gifts` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `game_key_id` int(11) NOT NULL,
  `message` text DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Sent',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `library`
--

CREATE TABLE `library` (
  `id` int(11) NOT NULL,
  `player_id` int(11) NOT NULL,
  `game_key_id` int(11) NOT NULL,
  `game_id` int(11) DEFAULT NULL,
  `key_code` varchar(255) DEFAULT NULL,
  `version_id` int(11) DEFAULT NULL,
  `order_item_id` int(11) DEFAULT NULL,
  `purchased_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `library`
--

INSERT INTO `library` (`id`, `player_id`, `game_key_id`, `game_id`, `key_code`, `version_id`, `order_item_id`, `purchased_at`) VALUES
(1, 1, 1, NULL, 'fâfa', NULL, NULL, '2026-06-22 08:53:23'),
(2, 1, 2, NULL, 'gamengon', NULL, NULL, '2026-06-22 09:10:45'),
(3, 1, 3, 200, 'STEAM-100200-44B08F49-0000', 200, 10, '2026-06-22 09:14:24'),
(4, 1, 4, 8, 'STEAM-100008-786E82B0-0000', 8, 11, '2026-06-22 20:53:50'),
(5, 1, 6, 198, 'STEAM-100198-E7E7E91E-0000', 198, 13, '2026-06-22 20:53:50'),
(6, 1, 7, 131, 'STEAM-100131-A1D9EEB6-0000', 131, 14, '2026-06-22 20:53:50'),
(7, 1, 8, 135, 'STEAM-100135-E6718AFB-0000', 135, 15, '2026-06-22 20:53:50'),
(8, 1, 10, 1, 'FREE-6A4D676CBC98E-1', 1, 16, '2026-07-07 20:54:04'),
(9, 1, 11, 1, 'FREE-6A4D67A19FE7E-1', 1, 17, '2026-07-07 20:54:57'),
(10, 1, 12, 32, 'STEAM-100032-74542043-0000', 32, 18, '2026-07-07 20:55:51'),
(11, 1, 13, 32, 'STEAM-100032-16C2851D-0001', 32, 18, '2026-07-07 20:55:51'),
(12, 1, 14, 32, 'STEAM-100032-70CF181D-0002', 32, 18, '2026-07-07 20:55:51'),
(13, 1, 15, 32, 'STEAM-100032-C753FF70-0003', 32, 18, '2026-07-07 20:55:51'),
(14, 1, 16, 32, 'STEAM-100032-D330FE19-0004', 32, 19, '2026-07-07 20:56:36'),
(15, 1, 17, 8, 'STEAM-100008-674CE722-0000', 8, 20, '2026-07-07 20:56:49'),
(16, 1, 18, 32, 'STEAM-100032-0BAC737D-0005', 32, 21, '2026-07-07 20:57:01'),
(17, 1, 19, 32, 'STEAM-100032-0F4D22AA-0006', 32, 22, '2026-07-07 21:00:28'),
(18, 1, 20, 32, 'STEAM-100032-DC75F865-0007', 32, 23, '2026-07-07 21:07:46'),
(19, 1, 21, 32, 'STEAM-100032-D0FD3C3C-0008', 32, 24, '2026-07-07 21:07:59'),
(20, 1, 22, 32, 'STEAM-100032-F1C60941-0009', 32, 25, '2026-07-07 21:08:12'),
(21, 1, 23, 32, 'STEAM-100032-8F89A5DD-0010', 32, 26, '2026-07-07 21:08:23'),
(22, 1, 24, 32, 'STEAM-100032-54CDEBDB-0011', 32, 27, '2026-07-07 21:10:11'),
(23, 1, 25, 32, 'STEAM-100032-BF7E7BE8-0012', 32, 29, '2026-07-07 21:17:31'),
(24, 1, 26, 32, 'STEAM-100032-C2BEC5D9-0013', 32, 30, '2026-07-07 21:17:45'),
(25, 1, 27, 32, 'STEAM-100032-2ECCA522-0014', 32, 31, '2026-07-07 21:18:03');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_06_22_000002_add_game_version_id_to_game_keys_table', 2),
(5, '2026_06_22_000003_add_friend_id_to_orders_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `player_id` int(11) NOT NULL,
  `friend_id` bigint(20) UNSIGNED DEFAULT NULL,
  `handled_by_admin` int(11) DEFAULT NULL,
  `total_amount` decimal(15,2) NOT NULL,
  `order_type` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `payment_method` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `player_id`, `friend_id`, `handled_by_admin`, `total_amount`, `order_type`, `status`, `payment_method`, `created_at`) VALUES
(1, 1, NULL, NULL, 59.99, 'Personal', 'API_Error', 'VNPAY', '2026-06-09 17:47:00'),
(2, 1, NULL, NULL, 59.99, 'Personal', 'API_Error', 'VNPAY', '2026-06-09 17:49:12'),
(3, 1, NULL, NULL, 14.99, 'Personal', 'API_Error', 'VNPAY', '2026-06-09 17:49:33'),
(4, 1, NULL, NULL, 59.99, 'Personal', 'API_Error', 'VNPAY', '2026-06-09 17:50:03'),
(5, 1, NULL, NULL, 59.99, 'Personal', 'API_Error', 'VNPAY', '2026-06-09 17:50:21'),
(6, 1, NULL, NULL, 31.99, 'Personal', 'API_Error', 'VNPAY', '2026-06-09 17:50:35'),
(7, 1, NULL, NULL, 119.98, 'Personal', 'Pending', 'VNPAY', '2026-06-09 20:04:27'),
(8, 1, NULL, NULL, 59.99, 'Personal', 'Completed', 'VNPAY', '2026-06-09 20:04:35'),
(9, 1, NULL, NULL, 59.99, 'Personal', 'API_Error', 'VNPAY', '2026-06-22 09:13:52'),
(10, 1, NULL, NULL, 50.99, 'Personal', 'Completed', 'VNPAY', '2026-06-22 09:14:18'),
(11, 1, NULL, NULL, 439.93, 'Personal', 'Completed', 'VNPAY', '2026-06-22 20:53:44'),
(12, 1, NULL, NULL, 0.00, 'Personal', 'API_Error', 'VNPAY', '2026-07-07 20:53:59'),
(13, 1, NULL, NULL, 0.00, 'Other', 'API_Error', 'VNPAY', '2026-07-07 20:54:52'),
(14, 1, NULL, NULL, 159.96, 'Personal', 'API_Error', 'VNPAY', '2026-07-07 20:55:46'),
(15, 1, NULL, NULL, 39.99, 'Other', 'Completed', 'VNPAY', '2026-07-07 20:56:30'),
(16, 1, NULL, NULL, 59.99, 'Other', 'Completed', 'VNPAY', '2026-07-07 20:56:44'),
(17, 1, NULL, NULL, 39.99, 'Other', 'Completed', 'VNPAY', '2026-07-07 20:56:55'),
(18, 1, NULL, NULL, 39.99, 'Other', 'Completed', 'VNPAY', '2026-07-07 20:57:06'),
(19, 1, NULL, NULL, 39.99, 'Other', 'Completed', 'VNPAY', '2026-07-07 21:07:41'),
(20, 1, NULL, NULL, 39.99, 'Other', 'Completed', 'VNPAY', '2026-07-07 21:07:54'),
(21, 1, NULL, NULL, 39.99, 'Other', 'Completed', 'VNPAY', '2026-07-07 21:08:06'),
(22, 1, NULL, NULL, 39.99, 'Other', 'Completed', 'VNPAY', '2026-07-07 21:08:17'),
(23, 1, NULL, NULL, 39.99, 'Other', 'Completed', 'VNPAY', '2026-07-07 21:08:28'),
(24, 1, NULL, NULL, 39.99, 'Other', 'Failed', 'VNPAY', '2026-07-07 21:12:20'),
(25, 1, NULL, NULL, 39.99, 'Other', 'Completed', 'VNPAY', '2026-07-07 21:17:26'),
(26, 1, NULL, NULL, 39.99, 'Other', 'Completed', 'VNPAY', '2026-07-07 21:17:40'),
(27, 1, NULL, NULL, 39.99, 'Other', 'Completed', 'VNPAY', '2026-07-07 21:17:58'),
(28, 1, NULL, NULL, 39.99, 'Other', 'Failed', 'VNPAY', '2026-07-07 21:18:16');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `game_version_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price_at_purchase` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `game_version_id`, `quantity`, `price_at_purchase`) VALUES
(1, 1, 198, 1, 59.99),
(2, 2, 198, 1, 59.99),
(3, 3, 66, 1, 14.99),
(4, 4, 198, 1, 59.99),
(5, 5, 198, 1, 59.99),
(6, 6, 32, 1, 31.99),
(7, 7, 198, 2, 59.99),
(8, 8, 198, 1, 59.99),
(9, 9, 198, 1, 59.99),
(10, 10, 200, 1, 50.99),
(11, 11, 8, 2, 59.99),
(12, 11, 117, 1, 59.99),
(13, 11, 198, 1, 59.99),
(14, 11, 131, 1, 59.99),
(15, 11, 135, 2, 69.99),
(16, 12, 1, 1, 0.00),
(17, 13, 1, 1, 0.00),
(18, 14, 32, 4, 39.99),
(19, 15, 32, 1, 39.99),
(20, 16, 8, 1, 59.99),
(21, 17, 32, 1, 39.99),
(22, 18, 32, 1, 39.99),
(23, 19, 32, 1, 39.99),
(24, 20, 32, 1, 39.99),
(25, 21, 32, 1, 39.99),
(26, 22, 32, 1, 39.99),
(27, 23, 32, 1, 39.99),
(28, 24, 32, 1, 39.99),
(29, 25, 32, 1, 39.99),
(30, 26, 32, 1, 39.99),
(31, 27, 32, 1, 39.99),
(32, 28, 32, 1, 39.99);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `players`
--

CREATE TABLE `players` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fullname` varchar(150) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Active',
  `balance` decimal(15,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `players`
--

INSERT INTO `players` (`id`, `username`, `email`, `password`, `fullname`, `status`, `balance`, `created_at`, `updated_at`) VALUES
(1, 'quangminhvu1314@gmail.com', 'quangminhvu1314@gmail.com', '$2y$12$XbaplVOJT968CD3E.Xu9beJeMOv91fwwhRiPLQhyqZNcmyyRjF2Yu', 'Bee', 'Active', 279.93, '2026-06-09 07:50:10', '2026-07-07 14:18:51'),
(2, 'a@a.com', 'a@a.com', '$2y$12$CVyeD9mAr7rg4PTSuK7C0ucUCVTCyjrCwg0U7ooHKaI814JMvVd02', 'a', 'Active', 0.00, '2026-06-22 02:17:30', '2026-06-22 02:17:30');

-- --------------------------------------------------------

--
-- Table structure for table `promotions`
--

CREATE TABLE `promotions` (
  `id` int(11) NOT NULL,
  `campaign_name` varchar(255) NOT NULL,
  `discount_percent` int(11) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `promotions`
--

INSERT INTO `promotions` (`id`, `campaign_name`, `discount_percent`, `start_time`, `end_time`) VALUES
(3, 'cs2', 15, '2026-06-22 15:52:00', '2026-06-22 17:52:00'),
(4, 'tast', 15, '2026-07-08 03:37:00', '2026-07-08 03:39:00');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supplier_providers`
--

CREATE TABLE `supplier_providers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(100) NOT NULL,
  `base_url` varchar(500) NOT NULL,
  `api_key` varchar(500) DEFAULT NULL,
  `api_key_header` varchar(100) DEFAULT 'X-API-Key',
  `timeout` int(11) DEFAULT 15,
  `priority` int(11) DEFAULT 0,
  `headers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`headers`)),
  `purchase_endpoint` varchar(500) DEFAULT '/api/purchase',
  `verify_endpoint` varchar(500) DEFAULT '/api/verify-key',
  `status` varchar(50) DEFAULT 'Active',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier_providers`
--

INSERT INTO `supplier_providers` (`id`, `name`, `code`, `base_url`, `api_key`, `api_key_header`, `timeout`, `priority`, `headers`, `purchase_endpoint`, `verify_endpoint`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'A', 'SUPPLIER_A', 'http://127.0.0.1:4099', 'SUPPLIER_DEMO_KEY_2026', 'X-API-Key', 15, 0, NULL, '/api/purchase', '/api/verify-key', 'Active', NULL, '2026-06-08 09:57:30', '2026-06-09 18:03:50'),
(2, 'TEST FAIL', 'WILL FAIL', 'http://192.168.1.144:7580', NULL, 'X-API-Key', 15, 1, NULL, '/api/purchase', '/api/verify-key', 'Active', NULL, '2026-06-23 15:30:05', '2026-06-23 15:30:05');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `player_id` (`player_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_cart_game` (`cart_id`,`game_version_id`),
  ADD KEY `game_version_id` (`game_version_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `category_name` (`category_name`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `friendships`
--
ALTER TABLE `friendships`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `game_categories`
--
ALTER TABLE `game_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `game_id` (`game_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `game_images`
--
ALTER TABLE `game_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `game_id` (`game_id`);

--
-- Indexes for table `game_keys`
--
ALTER TABLE `game_keys`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_item_id` (`order_item_id`);

--
-- Indexes for table `game_supplier_mappings`
--
ALTER TABLE `game_supplier_mappings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_game_supplier` (`game_id`,`supplier_provider_id`),
  ADD KEY `supplier_provider_id` (`supplier_provider_id`);

--
-- Indexes for table `game_versions`
--
ALTER TABLE `game_versions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `game_id` (`game_id`),
  ADD KEY `promotion_id` (`promotion_id`);

--
-- Indexes for table `gifts`
--
ALTER TABLE `gifts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_gift_key` (`game_key_id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `library`
--
ALTER TABLE `library`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_player_key` (`player_id`,`game_key_id`),
  ADD KEY `game_key_id` (`game_key_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `player_id` (`player_id`),
  ADD KEY `handled_by_admin` (`handled_by_admin`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `game_version_id` (`game_version_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `players`
--
ALTER TABLE `players`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `promotions`
--
ALTER TABLE `promotions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `supplier_providers`
--
ALTER TABLE `supplier_providers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=151;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `friendships`
--
ALTER TABLE `friendships`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `games`
--
ALTER TABLE `games`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=204;

--
-- AUTO_INCREMENT for table `game_categories`
--
ALTER TABLE `game_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=201;

--
-- AUTO_INCREMENT for table `game_images`
--
ALTER TABLE `game_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=356;

--
-- AUTO_INCREMENT for table `game_keys`
--
ALTER TABLE `game_keys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `game_supplier_mappings`
--
ALTER TABLE `game_supplier_mappings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=203;

--
-- AUTO_INCREMENT for table `game_versions`
--
ALTER TABLE `game_versions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=201;

--
-- AUTO_INCREMENT for table `gifts`
--
ALTER TABLE `gifts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `library`
--
ALTER TABLE `library`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `players`
--
ALTER TABLE `players`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `promotions`
--
ALTER TABLE `promotions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `supplier_providers`
--
ALTER TABLE `supplier_providers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`player_id`) REFERENCES `players` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`game_version_id`) REFERENCES `game_versions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `friendships`
--
ALTER TABLE `friendships`
  ADD CONSTRAINT `friendships_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `players` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `friendships_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `players` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `game_categories`
--
ALTER TABLE `game_categories`
  ADD CONSTRAINT `game_categories_ibfk_1` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `game_categories_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `game_images`
--
ALTER TABLE `game_images`
  ADD CONSTRAINT `game_images_ibfk_1` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `game_keys`
--
ALTER TABLE `game_keys`
  ADD CONSTRAINT `game_keys_ibfk_1` FOREIGN KEY (`order_item_id`) REFERENCES `order_items` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `game_supplier_mappings`
--
ALTER TABLE `game_supplier_mappings`
  ADD CONSTRAINT `game_supplier_mappings_ibfk_1` FOREIGN KEY (`supplier_provider_id`) REFERENCES `supplier_providers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `game_versions`
--
ALTER TABLE `game_versions`
  ADD CONSTRAINT `game_versions_ibfk_1` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `game_versions_ibfk_2` FOREIGN KEY (`promotion_id`) REFERENCES `promotions` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `gifts`
--
ALTER TABLE `gifts`
  ADD CONSTRAINT `gifts_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `players` (`id`),
  ADD CONSTRAINT `gifts_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `players` (`id`),
  ADD CONSTRAINT `gifts_ibfk_3` FOREIGN KEY (`game_key_id`) REFERENCES `game_keys` (`id`);

--
-- Constraints for table `library`
--
ALTER TABLE `library`
  ADD CONSTRAINT `library_ibfk_1` FOREIGN KEY (`player_id`) REFERENCES `players` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `library_ibfk_2` FOREIGN KEY (`game_key_id`) REFERENCES `game_keys` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`player_id`) REFERENCES `players` (`id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`handled_by_admin`) REFERENCES `admins` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`game_version_id`) REFERENCES `game_versions` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
