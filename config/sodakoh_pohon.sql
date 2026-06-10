-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 21, 2026 at 05:06 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sodakoh_pohon`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_logs`
--

CREATE TABLE `admin_logs` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `campaigns`
--

CREATE TABLE `campaigns` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_by_user` tinyint(1) DEFAULT 0,
  `rejection_reason` text DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `long_description` text DEFAULT NULL,
  `location` varchar(255) NOT NULL,
  `tree_type` varchar(100) NOT NULL,
  `price_per_tree` decimal(12,2) NOT NULL,
  `target_trees` int(11) NOT NULL,
  `current_trees` int(11) DEFAULT 0,
  `planted_trees` int(11) DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `map_url` text DEFAULT NULL,
  `category` varchar(100) DEFAULT 'Umum',
  `status` enum('active','pending','completed','cancelled','pending_approval') DEFAULT 'pending_approval',
  `partner` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `deadline` date DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `campaigns`
--

INSERT INTO `campaigns` (`id`, `user_id`, `created_by_user`, `rejection_reason`, `title`, `description`, `long_description`, `location`, `tree_type`, `price_per_tree`, `target_trees`, `current_trees`, `planted_trees`, `image`, `map_url`, `category`, `status`, `partner`, `created_at`, `deadline`, `updated_at`) VALUES
(1, NULL, 0, NULL, 'Restorasi Mangrove Demak', 'Kawasan pesisir Demak mengalami abrasi yang cukup parah. Program ini bertujuan membangun sabuk hijau mangrove.', NULL, 'Demak, Jawa Tengah', 'Mangrove Rhizophora', 10000.00, 5000, 1450, 1090, 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09', NULL, 'Umum', 'active', 'Kelompok Tani Hutan Mangrove', '2026-02-16 14:16:54', '2026-03-30', NULL),
(2, NULL, 0, NULL, 'Reboisasi Lereng Merapi', 'Menyelamatkan hutan di lereng Merapi dari ancaman longsor dengan penanaman pohon keras.', NULL, 'Magelang, Jawa Tengah', 'Sengon & Mahoni', 12000.00, 4000, 2300, 2350, 'https://images.unsplash.com/photo-1472214103451-9374bd1c798e', NULL, 'Umum', 'active', 'Komunitas Pecinta Alam', '2026-02-16 14:16:54', '2026-03-15', NULL),
(3, NULL, 0, NULL, 'Penghijauan Hutan Lombok', 'Memulihkan ekosistem hutan yang rusak akibat kebakaran di Lombok.', NULL, 'Lombok, NTB', 'Mahoni', 15000.00, 3000, 800, 450, 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e', NULL, 'Umum', 'active', 'Green Lombok Foundation', '2026-02-16 14:16:54', '2026-04-20', NULL),
(4, NULL, 0, NULL, 'Hutan Pangan Kalimantan', 'Membangun hutan pangan untuk ketahanan pangan masyarakat sekitar hutan.', NULL, 'Kutai, Kaltim', 'Durian & Petai', 25000.00, 2000, 450, 120, 'https://images.unsplash.com/photo-1518531933037-91b2f5f229cc', NULL, 'Umum', 'active', 'Komunitas Adat Dayak', '2026-02-16 14:16:54', '2026-05-10', NULL),
(5, NULL, 0, NULL, 'Konservasi Hutan Papua', 'Melindungi keanekaragaman hayati Papua melalui konservasi hutan adat.', '', 'Jayapura, Papua', 'Merbau', 30000.00, 1500, 323, 100, 'https://images.unsplash.com/photo-1425913397330-cf8af2ff40a1', NULL, 'Umum', 'active', 'Lembaga Adat Papua', '2026-02-16 14:16:54', '2026-06-30', NULL),
(6, NULL, 0, NULL, 'Mangrove Pesisir Jakarta', 'Mencegah banjir rob dan abrasi di pesisir Jakarta Utara.', '', 'Jakarta Utara', 'Mangrove', 10000.00, 3500, 1276, 840, 'uploads/campaigns/6998cb5719c88.jpg', NULL, 'Umum', 'active', 'Forum Komunitas Hijau', '2026-02-16 14:16:54', '2026-03-25', NULL),
(16, NULL, 0, NULL, 'test-1', 'tes', 'tes', 'Magelang', 'Pinus', 21.00, 1, 5, 0, 'uploads/campaigns/6a0d9f618a1b9.jpg', 'https://www.google.com/maps/place/Kontrasun/@-7.4589508,110.2227495,17.14z/data=!4m6!3m5!1s0x2e7a850053438bed:0xc0a761e5f440d914!8m2!3d-7.4564443!4d110.2274099!16s%2Fg%2F11vwwxcmn6?entry=ttu&g_ep=EgoyMDI2MDMwOS4wIKXMDSoASAFQAw%3D%3D', 'Umum', 'active', '1', '2026-05-20 13:47:45', '2026-05-14', '2026-05-20 19:11:30');

-- --------------------------------------------------------

--
-- Table structure for table `campaign_benefits`
--

CREATE TABLE `campaign_benefits` (
  `id` int(11) NOT NULL,
  `campaign_id` int(11) NOT NULL,
  `benefit` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `campaign_benefits`
--

INSERT INTO `campaign_benefits` (`id`, `campaign_id`, `benefit`) VALUES
(1, 1, 'Melindungi garis pantai dari abrasi'),
(2, 1, 'Menciptakan habitat baru bagi biota laut'),
(3, 1, 'Menyerap karbon hingga 4x lebih banyak'),
(4, 1, 'Memberdayakan masyarakat lokal'),
(5, 2, 'Mencegah tanah longsor'),
(6, 2, 'Menjaga sumber mata air'),
(7, 2, 'Habitat satwa liar'),
(8, 2, 'Ekowisata'),
(9, 3, 'Restorasi lahan kritis'),
(10, 3, 'Mengurangi risiko banjir'),
(11, 3, 'Kesuburan tanah'),
(12, 4, 'Ketahanan pangan masyarakat'),
(13, 4, 'Sumber penghasilan tambahan'),
(14, 4, 'Konservasi plasma nutfah'),
(15, 6, 'Pengendalian banjir rob'),
(16, 6, 'Pariwisata edukasi'),
(17, 6, 'Habitat burung migran'),
(18, 16, 'testing 1');

-- --------------------------------------------------------

--
-- Table structure for table `campaign_gallery`
--

CREATE TABLE `campaign_gallery` (
  `id` int(11) NOT NULL,
  `campaign_id` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `caption` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `campaign_gallery`
--

INSERT INTO `campaign_gallery` (`id`, `campaign_id`, `image_url`, `caption`, `created_at`) VALUES
(1, 1, 'https://images.unsplash.com/photo-1621451498295-af1ea68616ee', 'Penanaman mangrove tahap 1', '2026-02-16 14:16:54'),
(2, 1, 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09', 'Bibit mangrove siap tanam', '2026-02-16 14:16:54'),
(3, 1, 'https://images.unsplash.com/photo-1624535168245-0f9d5d773c2e', 'Relawan menanam mangrove', '2026-02-16 14:16:54'),
(4, 2, 'https://images.unsplash.com/photo-1472214103451-9374bd1c798e', 'Penanaman di lereng Merapi', '2026-02-16 14:16:54'),
(5, 6, 'https://images.unsplash.com/photo-1621451498295-af1ea68616ee', 'Edukasi mangrove di Jakarta', '2026-02-16 14:16:54');

-- --------------------------------------------------------

--
-- Table structure for table `campaign_submissions`
--

CREATE TABLE `campaign_submissions` (
  `id` int(11) NOT NULL,
  `submitter_name` varchar(255) NOT NULL,
  `submitter_email` varchar(255) NOT NULL,
  `submitter_phone` varchar(20) NOT NULL,
  `organization_name` varchar(255) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `location` varchar(255) NOT NULL,
  `tree_type` varchar(100) NOT NULL,
  `target_trees` int(11) NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `image` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `campaign_submissions`
--

INSERT INTO `campaign_submissions` (`id`, `submitter_name`, `submitter_email`, `submitter_phone`, `organization_name`, `title`, `description`, `location`, `tree_type`, `target_trees`, `status`, `image`, `created_at`, `updated_at`) VALUES
(2, 'zreal', 'zreal@gmail.com', '08123456789', 'Komunitas', 'testing untuk nambah foto', 'deskripsi', 'Magelang', 'Pinus', 1, 'rejected', 'uploads/campaign_submissions/sub_6a0de36713c96.jpg', '2026-05-20 23:37:59', '2026-05-20 23:41:31'),
(3, 'zreal', 'zreal@gmail.com', '08123456789', 'Komunitas', 'testung foto', 'a', 'Magelang', 'Pinus', 1, 'approved', NULL, '2026-05-20 23:42:52', '2026-05-21 09:54:06'),
(4, 'zreal', 'zreal@gmail.com', '08123456789', 'Komunitas', 'test foto lagi', 'a', 'Magelang', 'Pinus', 11, 'pending', '[\"uploads\\/campaign_submissions\\/sub_6a0de4d4b11ac_0.jpg\",\"uploads\\/campaign_submissions\\/sub_6a0de4d4b1384_1.jpeg\"]', '2026-05-20 23:44:04', '2026-05-20 23:44:04'),
(5, 'zreal', 'zreal@gmail.com', '085156476828', 'Komunitas', 'test wa', 'as', 'Desa Selo, Magelang', 'Pinus', 1, 'pending', NULL, '2026-05-20 23:51:05', '2026-05-20 23:51:05'),
(6, 'Restu Wibisono', 'restu@gmail.com', '085156476828', 'Komunitas', 'testing-profil', 'asd', 'Magelang', 'Pinus', 11, 'approved', '[\"uploads\\/campaign_submissions\\/sub_6a0e767e2c1a9_0.jpg\",\"uploads\\/campaign_submissions\\/sub_6a0e767e2c3f1_1.jpeg\"]', '2026-05-21 10:05:34', '2026-05-21 10:05:52');

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` int(11) NOT NULL,
  `session_id` varchar(255) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `cart_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`cart_data`)),
  `subtotal` decimal(12,2) DEFAULT 0.00,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `certificates`
--

CREATE TABLE `certificates` (
  `id` int(11) NOT NULL,
  `certificate_number` varchar(100) NOT NULL,
  `donation_id` int(11) NOT NULL,
  `donor_name` varchar(255) NOT NULL,
  `campaign_name` varchar(255) NOT NULL,
  `trees_count` int(11) NOT NULL DEFAULT 1,
  `issued_at` date NOT NULL,
  `issued_by` varchar(100) DEFAULT 'Sodakoh Pohon',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `certificates`
--

INSERT INTO `certificates` (`id`, `certificate_number`, `donation_id`, `donor_name`, `campaign_name`, `trees_count`, `issued_at`, `issued_by`, `created_at`) VALUES
(1, 'CERT-202602-0001', 1, 'Ahmad Pratama', 'Restorasi Mangrove Demak', 10, '2026-02-19', 'Sodakoh Pohon', '2026-04-01 22:45:09'),
(2, 'CERT-202602-0002', 2, 'Siti Nurhaliza', 'Restorasi Mangrove Demak', 5, '2026-02-19', 'Sodakoh Pohon', '2026-04-01 22:45:09'),
(3, 'CERT-202602-0003', 3, 'Hamba Allah', 'Restorasi Mangrove Demak', 3, '2026-02-19', 'Sodakoh Pohon', '2026-04-01 22:45:09'),
(4, 'CERT-202602-0005', 5, 'Rina Amelia', 'Reboisasi Lereng Merapi', 8, '2026-02-19', 'Sodakoh Pohon', '2026-04-01 22:45:09'),
(5, 'CERT-202602-0006', 6, 'Dimas Saputra', 'Reboisasi Lereng Merapi', 15, '2026-02-19', 'Sodakoh Pohon', '2026-04-01 22:45:09'),
(6, 'CERT-202602-0010', 10, 'Agus Setiawan', 'Hutan Pangan Kalimantan', 4, '2026-02-19', 'Sodakoh Pohon', '2026-04-01 22:45:09'),
(7, 'SP-CERT-20260520-0005', 21, 'Restu Wibisono', 'Mangrove Pesisir Jakarta', 25, '2026-05-20', 'Sodakoh Pohon', '2026-05-21 00:56:34');

-- --------------------------------------------------------

--
-- Table structure for table `donations`
--

CREATE TABLE `donations` (
  `id` int(11) NOT NULL,
  `donation_number` varchar(50) NOT NULL,
  `donor_name` varchar(255) NOT NULL,
  `donor_email` varchar(255) DEFAULT NULL,
  `donor_phone` varchar(20) DEFAULT NULL,
  `anonymous` tinyint(1) DEFAULT 0,
  `campaign_id` int(11) NOT NULL,
  `trees_count` int(11) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `status` enum('pending','paid','failed','cancelled') DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_proof` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `certificate_number` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donations`
--

INSERT INTO `donations` (`id`, `donation_number`, `donor_name`, `donor_email`, `donor_phone`, `anonymous`, `campaign_id`, `trees_count`, `amount`, `status`, `payment_method`, `payment_proof`, `message`, `certificate_number`, `created_at`, `updated_at`) VALUES
(15, 'SP-20260401-0001', 'Anonymous', 'anjay@gmail.com', '', 1, 6, 1, 10000.00, 'paid', 'OVO', NULL, 'semoga jadi pohon cemara kek keluarga', 'SP-CERT-20260401-0001', '2026-04-01 17:24:15', '2026-04-01 17:25:06'),
(16, 'SP-20260508-0001', 'restu wibisono', 'restu1@gmail.com', '', 0, 5, 3, 90000.00, 'paid', 'BCA Virtual Account', NULL, '', 'SP-CERT-20260508-0001', '2026-05-08 03:04:49', '2026-05-08 03:07:19'),
(17, 'SP-20260520-0001', 'Restu Wibisonoi', 'restu@gmail.com', '081234567899', 0, 16, 5, 105.00, 'paid', 'BCA Virtual Account', NULL, '', 'SP-CERT-20260520-0001', '2026-05-20 14:28:20', '2026-05-20 14:28:36'),
(20, 'SP-20260520-0004', 'Restu Wibisono', 'restu@gmail.com', '081234567899', 0, 3, 20, 300000.00, 'paid', 'BCA Virtual Account', NULL, '', 'SP-CERT-20260520-0004', '2026-05-20 19:16:14', '2026-05-20 19:16:26'),
(21, 'SP-20260520-0005', 'Restu Wibisono', 'restu@gmail.com', '081234567899', 0, 6, 25, 250000.00, 'paid', 'BCA Virtual Account', NULL, '', 'SP-CERT-20260520-0005', '2026-05-20 19:56:22', '2026-05-20 19:56:34');

-- --------------------------------------------------------

--
-- Table structure for table `plantings`
--

CREATE TABLE `plantings` (
  `id` int(11) NOT NULL,
  `campaign_id` int(11) NOT NULL,
  `trees_planted` int(11) NOT NULL,
  `planting_date` date NOT NULL,
  `location` varchar(255) NOT NULL,
  `volunteers` int(11) DEFAULT 0,
  `coordinator` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('scheduled','completed','cancelled') DEFAULT 'scheduled',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `planting_gallery`
--

CREATE TABLE `planting_gallery` (
  `id` int(11) NOT NULL,
  `planting_id` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `caption` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `planting_gallery`
--

INSERT INTO `planting_gallery` (`id`, `planting_id`, `image_url`, `caption`, `created_at`) VALUES
(1, 16, 'uploads/planting_gallery/pg_69b570533c079.png', NULL, '2026-03-14 15:27:31'),
(2, 16, 'uploads/planting_gallery/pg_69b570638d8a9.png', NULL, '2026-03-14 15:27:47'),
(3, 16, 'uploads/planting_gallery/pg_69b5708477ef0.jpg', NULL, '2026-03-14 15:28:20'),
(4, 16, 'uploads/planting_gallery/pg_69b5708478328.png', NULL, '2026-03-14 15:28:20'),
(5, 16, 'uploads/planting_gallery/pg_69b57084786c2.jpeg', NULL, '2026-03-14 15:28:20'),
(6, 17, 'uploads/planting_gallery/pg_6a0da6e697552.jpg', NULL, '2026-05-20 14:19:50'),
(7, 17, 'uploads/planting_gallery/pg_6a0da6e697815.jpeg', NULL, '2026-05-20 14:19:50'),
(8, 18, 'uploads/planting_gallery/pg_6a0db5f820e76.jpg', NULL, '2026-05-20 15:24:08');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `avatar`, `role`, `created_at`, `updated_at`, `last_login`) VALUES
(1, 'Admin Sodakoh', 'admin@sodakohpohon.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, 'admin', '2026-02-16 14:16:54', NULL, '2026-05-21 09:52:04'),
(2, 'Ilham', 'lham@gmail.com', '$2y$10$aYHP.IqH9GYd.2IcMbh.OOJSsfHINWWaC9WBWpgUbLF1Raro.FVDy', '0812345678', NULL, 'user', '2026-03-07 02:42:20', NULL, '2026-03-07 02:42:30'),
(3, 'Brian Watson', 'anjay@gmail.com', '$2y$10$gMCI4TeLqGy7bYGL2ZkcA.e29Of900bgE9ClOq6yzKOgBDHDWoIMy', '08123123123', 'uploads/avatars/avatar_3_1778130875.jpg', 'user', '2026-03-26 23:21:51', '2026-05-07 12:14:35', '2026-05-08 07:59:04'),
(4, 'restu wibisono', 'restu1@gmail.com', '$2y$10$jyf4XMcw5y0TUB.larobbec4EuSKXcwE/kBWWDo4C7Du4Bu.hoQz6', '0877777777777', NULL, 'user', '2026-05-08 08:00:51', NULL, '2026-05-08 08:01:06'),
(5, 'Restu Wibisono', 'restu@gmail.com', '$2y$10$FfmeBVyihajMVOg6WMFA7edcrqGoqMSLFp4N1v4AJLVODZN1KNRhm', '08123456789', NULL, 'user', '2026-05-21 01:20:52', NULL, '2026-05-21 09:40:40');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_logs`
--
ALTER TABLE `admin_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_admin` (`admin_id`),
  ADD KEY `idx_action` (`action`);

--
-- Indexes for table `campaigns`
--
ALTER TABLE `campaigns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_location` (`location`);

--
-- Indexes for table `campaign_benefits`
--
ALTER TABLE `campaign_benefits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `campaign_id` (`campaign_id`);

--
-- Indexes for table `campaign_gallery`
--
ALTER TABLE `campaign_gallery`
  ADD PRIMARY KEY (`id`),
  ADD KEY `campaign_id` (`campaign_id`);

--
-- Indexes for table `campaign_submissions`
--
ALTER TABLE `campaign_submissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_session` (`session_id`);

--
-- Indexes for table `certificates`
--
ALTER TABLE `certificates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `certificate_number` (`certificate_number`),
  ADD KEY `donation_id` (`donation_id`);

--
-- Indexes for table `donations`
--
ALTER TABLE `donations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `donation_number` (`donation_number`),
  ADD UNIQUE KEY `certificate_number` (`certificate_number`),
  ADD KEY `campaign_id` (`campaign_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_donation_number` (`donation_number`),
  ADD KEY `idx_email` (`donor_email`);

--
-- Indexes for table `plantings`
--
ALTER TABLE `plantings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_campaign` (`campaign_id`),
  ADD KEY `idx_date` (`planting_date`);

--
-- Indexes for table `planting_gallery`
--
ALTER TABLE `planting_gallery`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_planting` (`planting_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_role` (`role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_logs`
--
ALTER TABLE `admin_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `campaigns`
--
ALTER TABLE `campaigns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `campaign_benefits`
--
ALTER TABLE `campaign_benefits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `campaign_gallery`
--
ALTER TABLE `campaign_gallery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `campaign_submissions`
--
ALTER TABLE `campaign_submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `certificates`
--
ALTER TABLE `certificates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `donations`
--
ALTER TABLE `donations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `plantings`
--
ALTER TABLE `plantings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `planting_gallery`
--
ALTER TABLE `planting_gallery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `campaign_benefits`
--
ALTER TABLE `campaign_benefits`
  ADD CONSTRAINT `campaign_benefits_ibfk_1` FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `campaign_gallery`
--
ALTER TABLE `campaign_gallery`
  ADD CONSTRAINT `campaign_gallery_ibfk_1` FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `donations`
--
ALTER TABLE `donations`
  ADD CONSTRAINT `donations_ibfk_1` FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`id`);

--
-- Constraints for table `plantings`
--
ALTER TABLE `plantings`
  ADD CONSTRAINT `plantings_ibfk_1` FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
