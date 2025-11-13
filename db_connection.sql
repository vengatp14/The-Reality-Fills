-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 13, 2025 at 11:41 AM
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
-- Database: `db_connection`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', 'admin123');

-- --------------------------------------------------------

--
-- Table structure for table `joint_ventures`
--

CREATE TABLE `joint_ventures` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `taluk` varchar(255) NOT NULL,
  `district` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `acre_extent` varchar(50) NOT NULL,
  `plot_extent` varchar(50) NOT NULL,
  `road_width` varchar(50) NOT NULL,
  `zone_classification` varchar(100) NOT NULL,
  `dry_land` tinyint(1) DEFAULT 0,
  `wet_land` tinyint(1) DEFAULT 0,
  `development_category` text DEFAULT NULL,
  `finance_zone` varchar(100) NOT NULL,
  `advance_amount` decimal(15,2) NOT NULL,
  `ratio` varchar(50) NOT NULL,
  `refundable` varchar(3) DEFAULT 'No',
  `non_refundable` varchar(3) DEFAULT 'No',
  `user_status` enum('Pending','Accepted','Rejected') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `joint_ventures`
--

INSERT INTO `joint_ventures` (`id`, `full_name`, `email`, `mobile`, `user_email`, `location`, `taluk`, `district`, `state`, `country`, `acre_extent`, `plot_extent`, `road_width`, `zone_classification`, `dry_land`, `wet_land`, `development_category`, `finance_zone`, `advance_amount`, `ratio`, `refundable`, `non_refundable`, `user_status`, `created_at`) VALUES
(5, 'test6', 'test6@gmail.com', '8936376237', 'test@gmail.com', 'Madurai', 'Madurai North', 'Madurai', 'Tamil Nadu', 'India', '10', '10', '10', '67', 1, 0, '[\"Farm Land\"]', '10', 50000000.00, '4', 'Yes', 'No', 'Accepted', '2025-11-10 15:22:53'),
(9, 'test7', 'test7@gmail.com', '8936376200', 'test@gmail.com', 'Madurai', 'Madurai North', 'Madurai', 'Tamil Nadu', 'India', '10', '10', '10', '67', 1, 0, '[\"Farm Land\"]', '10', 50000000.00, '4/7', 'Yes', 'No', 'Pending', '2025-11-10 12:08:24');

-- --------------------------------------------------------

--
-- Table structure for table `properties`
--

CREATE TABLE `properties` (
  `id` int(11) NOT NULL,
  `property_code` varchar(20) DEFAULT NULL,
  `client_name` varchar(100) NOT NULL,
  `client_mobile` varchar(15) NOT NULL,
  `property_title` varchar(255) NOT NULL,
  `property_images` text DEFAULT NULL,
  `property_documents` text DEFAULT NULL,
  `property_type` varchar(100) DEFAULT NULL,
  `listing_type` varchar(20) NOT NULL,
  `property_price` decimal(12,2) NOT NULL,
  `property_size` decimal(10,2) DEFAULT NULL,
  `property_unit` varchar(50) DEFAULT NULL,
  `full_address` varchar(500) NOT NULL,
  `property_address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','accepted','rejected') NOT NULL,
  `email` varchar(255) NOT NULL,
  `user_status` enum('active','inactive') DEFAULT 'inactive',
  `views` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `properties`
--

INSERT INTO `properties` (`id`, `property_code`, `client_name`, `client_mobile`, `property_title`, `property_images`, `property_documents`, `property_type`, `listing_type`, `property_price`, `property_size`, `property_unit`, `full_address`, `property_address`, `created_at`, `status`, `email`, `user_status`, `views`) VALUES
(21, 'PROP021', 'User3', '9834467789', 'Apartment', '[\"Haven.jpg\"]', '[\"Form-11-1.pdf\"]', 'Apartment', 'rent', 200000000.00, 10000.00, 'sqft', '', 'Mumbai', '2025-11-09 16:17:43', '', '', 'active', 33),
(22, 'PROP022', 'User2', '9732467729', 'Villa', '1762871067_Pool_villa.jpg', '1762871067_6913471ba02e4_Form-11.pdf', 'villa', 'lease', 500000000.00, 1200.00, 'sqft', '', 'Madurai', '2025-11-11 14:24:27', '', 'user2@gmail.com', 'active', 0),
(23, NULL, 'User3', '9834467789', 'Apartment', '[\"Pool_villa.jpg\"]', '[]', 'Apartment', 'sell', 200000000.00, 10000.00, 'sqft', '', 'Madurai', '2025-11-11 15:26:52', '', '', 'active', 0),
(24, NULL, 'test', '9834467789', 'Villa', '[\"Cozy_Cottage.jpg\"]', '[\"Form-11-1.pdf\"]', 'Villa', 'rent', 200000000.00, 10000.00, 'sqft', '', 'Coimbatore', '2025-11-11 15:28:47', '', '', 'active', 0),
(25, NULL, 'test1', '9834467789', 'Apartment', '[\"Cozy_Cottage.jpg\"]', '[\"Form-11-1.pdf\"]', 'Apartment', 'lease', 200000000.00, 10000.00, 'sqft', '', 'Madurai', '2025-11-11 15:45:16', '', '', 'active', 0),
(26, NULL, 'test1', '9834467789', 'Apartment', '[\"view.jpg\"]', '[\"Form-11-1.pdf\"]', 'Apartment', 'rent', 200000000.00, 10000.00, 'sqft', '', 'Mumbai', '2025-11-11 16:01:36', '', '', 'active', 0),
(27, 'PROP027', 'User2', '9732467729', 'Villa', '1762882958_6913758e1d25c.jpg', '1762882958_6913758e2bb6e_Form-11.pdf', '0', 'rent', 500000000.00, 1200.00, '0', '', 'Trichy', '2025-11-11 17:42:38', '', 'user2@gmail.com', 'inactive', 0),
(28, 'PROP028', 'Client0', '9732467729', 'Villa', '1762884541_69137bbd05a74.jpg', '1762884541_69137bbd0609c_Form-11.pdf', '0', 'sell', 600000000.00, 1200.00, '0', '', 'Pondicherry', '2025-11-11 18:09:01', '', 'Client0@gmail.com', 'active', 0),
(29, 'PROP029', 'Client1', '9732467729', 'Bungalow', '1762886477_6913834df30bf.jpg', '1762886478_6913834e02c33_Forms.pdf', '0', 'lease', 600000000.00, 1200.00, '0', '', 'Goa', '2025-11-11 18:41:18', '', 'Client1@gmail.com', 'active', 0),
(30, 'PROP030', 'Test2', '9732467729', 'Bungalow', '1762920744', NULL, '0', 'sell', 700000000.00, 1200.00, '0', '', 'Delhi', '2025-11-12 04:12:24', '', 'Test2@gmail.com', 'active', 2),
(31, 'PROP031', 'Test3', '9732467729', 'Bungalow', '1762921132', '[\"1762921132_69140aacf1752_Form-11-1.pdf\"]', '0', 'lease', 700000000.00, 1200.00, '0', '', 'Chennai', '2025-11-12 04:18:52', '', 'Test3@gmail.com', 'active', 0),
(32, NULL, 'test00', '9834467789', 'Apartment', '[\"Premium_Villa.jpg\"]', '[\"Form-11.pdf\"]', 'Villa', 'sell', 200000000.00, 10000.00, 'sqft', '', 'Mumbai', '2025-11-12 05:31:01', 'accepted', 'test@gmail.com', 'inactive', 1),
(33, NULL, 'test11', '9834467789', 'Apartment', '[\"Premium_Villa.jpg\"]', '[\"Form-11.pdf\"]', 'Villa', 'sell', 200000000.00, 10000.00, 'sqft', '', 'Adayar, Chennai', '2025-11-12 09:31:04', '', 'test@gmail.com', 'active', 1),
(34, NULL, 'test12', '9834467789', 'Apartment', '[\"view.jpg\"]', '[\"Form-11-1.pdf\"]', 'Apartment', 'sell', 200000000.00, 10000.00, 'sqft', '', 'Sowcarpet, Chennai', '2025-11-12 10:26:51', 'accepted', 'test@gmail.com', 'active', 1),
(35, 'PROP035', 'Test3', '9732467729', 'Bungalow', '1762943633_69146291e12ae.jpg', '[\"1762943633_69146291e3252_Form-11.pdf\"]', 'Bungalow', 'sell', 700000000.00, 1200.00, 'sqft', '', 'Madurai', '2025-11-12 10:33:53', 'accepted', 'Test3@gmail.com', 'active', 0),
(36, 'PROP036', 'Test30', '9732467729', 'Villa', '1762944065_691464413faa8.jpg', '[\"1762944065_691464413feb0_Form-11.pdf\"]', 'Villa', 'rent', 700000000.00, 1200.00, 'sqft', '', 'Pune', '2025-11-12 10:41:05', 'accepted', 'Test30@gmail.com', 'active', 0),
(37, 'PROP037', 'User7', '9732467729', 'Apartment', '1762944863_6914675ff39bf.jpg', '[\"1762944863_6914675ff3fb5_Form-11.pdf\"]', 'Apartment', '0', 40000001.00, 1200.00, 'sqft', '', 'Banglore', '2025-11-12 10:54:24', 'accepted', 'user3@gmail.com', 'active', 0),
(38, 'PROP038', 'Client4', '9732467729', 'Building', '1762945386_6914696a45576.jpg', '[\"1762945386_6914696a45fa7_Form-11.pdf\"]', 'Commercial Building', 'Sell', 500000000.00, 1200.00, 'sqft', '', 'Banglore', '2025-11-12 11:03:06', 'accepted', 'client4@gmail.com', 'active', 1),
(39, NULL, 'Client09', '09834467789', 'Apartment', '[\"view.jpg\"]', '[\"Form-11-1.pdf\"]', 'Apartment', 'Lease', 200000000.00, 10000.00, 'sqft', '', 'Mumbai', '2025-11-13 05:36:38', 'pending', 'test5@gmail.com', 'active', 0),
(42, NULL, 'Client12', '09834467789', 'Apartment', '[\"view.jpg\"]', '[\"Form-11.pdf\"]', 'Apartment', 'Sell', 200000000.00, 10000.00, 'sqft', 'No.104 Happy Apartment KK street Madipakkam, Chennai - 600091.', 'Madipakkam, Chennai - 600091.', '2025-11-13 09:01:46', 'accepted', 'test5@gmail.com', 'active', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `usertype` enum('Buyer','Seller','Renter','Lease') NOT NULL DEFAULT 'Buyer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `mobile`, `usertype`, `created_at`) VALUES
(1, 'Test User', 'test@gmail.com', '12345', '', 'Buyer', '2025-10-31 17:02:11'),
(8, 'test5', 'test5@gmail.com', '12345', '0000080009', 'Buyer', '2025-11-03 05:35:59'),
(10, 'test', 'test9@gmail.com', '12345', '0000030005', 'Buyer', '2025-11-03 05:39:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `joint_ventures`
--
ALTER TABLE `joint_ventures`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `properties`
--
ALTER TABLE `properties`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `property_code` (`property_code`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mobile_number` (`mobile`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `joint_ventures`
--
ALTER TABLE `joint_ventures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `properties`
--
ALTER TABLE `properties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
