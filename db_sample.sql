-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 16, 2025 at 12:27 PM
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
-- Database: `db_sample`
--

-- --------------------------------------------------------

--
-- Table structure for table `barcode`
--

CREATE TABLE `barcode` (
  `barcode_ean` char(13) NOT NULL,
  `item_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `barcode`
--

INSERT INTO `barcode` (`barcode_ean`, `item_id`) VALUES
('2239872376872', 11),
('3453458677628', 5),
('4587263646878', 9),
('6241234586487', 8),
('6241527746363', 4),
('6241527836173', 1),
('6241574635234', 2),
('6264537836173', 3),
('6434564564544', 6),
('8476736836876', 7),
('9473625532534', 8),
('9473627464543', 8),
('9879879837489', 11);

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_id` int(11) NOT NULL,
  `title` char(4) DEFAULT NULL,
  `fname` varchar(32) DEFAULT NULL,
  `lname` varchar(32) NOT NULL,
  `addressline` varchar(64) DEFAULT NULL,
  `town` varchar(32) DEFAULT NULL,
  `zipcode` char(10) NOT NULL,
  `phone` varchar(16) DEFAULT NULL,
  `user_id` int(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customer_id`, `title`, `fname`, `lname`, `addressline`, `town`, `zipcode`, `phone`, `user_id`) VALUES
(1, 'Miss', 'jenny', 'stones', '27 Rowan Avenue', 'hightown', 'NT21AQ', '023 9876', NULL),
(2, 'Mr', 'Andrew', 'stones', '52 The willows', 'lowtown', 'LT57RA', '876 3527', NULL),
(3, 'Miss', 'Alex', 'Matthew', '4 The Street', 'Nicetown', 'NT22TX', '010 4567', NULL),
(4, 'Mr', 'Adrian', 'MAtthew', 'The Barn', 'Yuleville', 'YV672WR', '487 3871', NULL),
(5, 'Mr', 'Simon', 'Cozens', '7 Shady Lane', 'Oahenham', 'OA36Qw', '514 5926', NULL),
(6, 'Mr', 'Neil', 'Matther', '5 Pasture Lane', 'Nicetown', 'NT37RT', '267 1232', NULL),
(7, 'Mr', 'Richard', 'stones', '34 Holly Way', 'Bingham', 'BG42WE', '342 5982', NULL),
(8, 'Mrs', 'Ann', 'stones', '34 Holly Way', 'Bingham', 'BG42WE', '342 5982', NULL),
(9, 'Mrs', 'Christine', 'Hickman', '36 Queen Street', 'Histon', 'HT35EM', '342 5432', NULL),
(10, 'Mr', 'Mike', 'Howard', '86 Dysart Street', 'Tibsville', 'TB37FG', '505 5482', NULL),
(11, 'Mr', 'Dave', 'Jones', '54 Vale Rise', 'Bingham', 'BG38GD', '342 8264', NULL),
(12, 'Mr', 'Richard', 'Neil', '42 Thached Way', 'Winersbay', 'WB36GQ', '505 6482', NULL),
(13, 'Mrs', 'Laura', 'Hendy', '73 MArgaritta Way', 'Oxbridge', 'OX23HX', '821 2335', NULL),
(14, 'Mr', 'Bill', 'ONeil', '2 Beamer Street', 'Welltown', 'WT38GM', '435 1234', NULL),
(15, 'Mr', 'David', 'Hudson', '4 The Square', 'Milltown', 'MT26RT', '961 4526', NULL),
(17, 'ike', 'Ike', 'Eveland', 'Japan', 'Tokyo', '100-0000', '09123456780', 2),
(18, 'Mr.', 'Luca', 'Kaneshiro', 'United States', 'America', '2025', '09999999999', 3),
(19, 'Mr.', 'Vox', 'Akuma', 'UK', 'London', '1234-567', '0977777777', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `item_id` int(11) NOT NULL,
  `title` text NOT NULL,
  `description` varchar(64) NOT NULL,
  `cost_price` decimal(7,2) DEFAULT NULL,
  `sell_price` decimal(7,2) DEFAULT NULL,
  `img_path` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`item_id`, `title`, `description`, `cost_price`, `sell_price`, `img_path`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '', 'Wood Puzzel', 15.23, 21.95, '', NULL, NULL, NULL),
(2, '', 'Rubik Cube', 7.45, 11.49, '', NULL, NULL, NULL),
(3, '', 'Linux CD', 1.99, 2.49, '', NULL, NULL, NULL),
(4, '', 'Tissues', 2.11, 3.99, '', NULL, NULL, NULL),
(5, '', 'PIcture Frame', 7.54, 9.95, '', NULL, NULL, NULL),
(6, '', 'Fan Small', 9.23, 15.75, '', NULL, NULL, NULL),
(7, 'Fan', 'Fan', 13.36, 25.00, 'images/item_7_1760454872.jpg', NULL, NULL, NULL),
(8, '', 'ToothBrush', 0.75, 1.45, '', NULL, NULL, NULL),
(9, '', 'Roman Coin', 2.34, 2.45, '', NULL, NULL, NULL),
(10, '', 'Carrier Bag', 0.01, 0.00, '', NULL, NULL, NULL),
(11, '', 'Speakers', 19.73, 25.32, '', NULL, NULL, NULL),
(12, 'Harry Potter', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit', 10.00, 15.00, 'https://images.freeimages.com/images/large-previews/c0d/little-jars-with-stones-on-the-white-background-1641022.jpg', '2023-03-07 23:11:48', '2023-03-07 23:11:48', NULL),
(13, 'Harry Potter', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit', 10.00, 15.00, 'https://images.freeimages.com/images/large-previews/c0d/little-jars-with-stones-on-the-white-background-1641022.jpg', '2023-03-07 23:11:48', '2023-03-07 23:11:48', NULL),
(14, 'Harry Potter', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit', 10.00, 15.00, 'https://images.freeimages.com/images/large-previews/c0d/little-jars-with-stones-on-the-white-background-1641022.jpg', '2023-03-07 23:11:48', '2023-03-07 23:11:48', NULL),
(15, 'Harry Potter', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit', 10.00, 15.00, 'https://images.freeimages.com/images/large-previews/c0d/little-jars-with-stones-on-the-white-background-1641022.jpg', '2023-03-07 23:11:48', '2023-03-07 23:11:48', NULL),
(16, 'Harry Potter', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit', 200.00, 15.00, 'https://images.freeimages.com/images/large-previews/c0d/little-jars-with-stones-on-the-white-background-1641022.jpg', '2023-03-07 23:11:48', '2023-03-07 23:11:48', NULL),
(17, 'Apple', 'mansanas', 12.00, 50.00, 'images/item_1760438587_68ee293b5fca5.png', NULL, NULL, '2025-10-14 11:30:22'),
(18, 'Samsung', 'Samsung', 1000.00, 2000.00, 'images/item_18_1760453936.png', '2025-10-14 11:50:26', '2025-10-14 14:58:56', '2025-10-14 15:01:48'),
(19, 'Charger', 'Charge', 50.00, 99.00, 'images/item_1760453766_68ee6486559ea.jpg', '2025-10-14 14:56:06', '2025-10-14 14:56:06', NULL),
(20, 'Extension', 'Extension', 200.00, 300.00, 'images/item_1760454034_68ee6592bbc36.jpg', '2025-10-14 15:00:34', '2025-10-14 15:00:34', NULL),
(21, 'Shrek', 'Shrek', 50.00, 100.00, 'images/item_1760454796_68ee688c69bed.png', '2025-10-14 15:13:16', '2025-10-14 15:13:16', '2025-10-14 15:14:47');

-- --------------------------------------------------------

--
-- Table structure for table `orderinfo`
--

CREATE TABLE `orderinfo` (
  `orderinfo_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `date_placed` date NOT NULL,
  `date_shipped` date DEFAULT NULL,
  `shipping` decimal(7,2) DEFAULT NULL,
  `status` enum('Processing','Delivered','Canceled') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `orderinfo`
--

INSERT INTO `orderinfo` (`orderinfo_id`, `customer_id`, `date_placed`, `date_shipped`, `shipping`, `status`, `created_at`, `updated_at`) VALUES
(1, 3, '2000-03-13', '2000-03-17', 2.99, 'Processing', NULL, NULL),
(2, 8, '2000-06-23', '2000-06-23', 0.00, 'Processing', NULL, NULL),
(3, 15, '2000-09-02', '2000-09-12', 3.99, 'Processing', NULL, NULL),
(4, 13, '2000-09-03', '2000-09-10', 2.99, 'Processing', NULL, NULL),
(5, 8, '2000-07-21', '2000-07-24', 0.00, 'Processing', NULL, NULL),
(15, 1, '2023-03-09', '2023-03-09', 10.00, 'Processing', NULL, NULL),
(16, 1, '2023-03-09', '2023-03-09', 10.00, 'Processing', NULL, NULL),
(18, 1, '2023-03-10', '2023-03-10', 10.00, 'Processing', '2023-03-09 22:57:10', '2023-03-09 22:57:10'),
(21, 1, '2023-03-10', '2023-03-10', 10.00, 'Processing', '2023-03-09 23:20:35', '2023-03-09 23:20:35'),
(22, 1, '2023-03-10', '2023-03-10', 10.00, 'Processing', '2023-03-09 23:21:13', '2023-03-09 23:21:13'),
(23, 2, '2025-10-14', NULL, NULL, 'Processing', NULL, NULL),
(24, 2, '2025-10-14', NULL, NULL, 'Processing', NULL, NULL),
(25, 2, '2025-10-14', NULL, NULL, 'Processing', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `orderline`
--

CREATE TABLE `orderline` (
  `orderinfo_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `orderline`
--

INSERT INTO `orderline` (`orderinfo_id`, `item_id`, `quantity`) VALUES
(1, 4, 1),
(1, 7, 1),
(1, 9, 1),
(2, 1, 1),
(2, 10, 1),
(2, 7, 2),
(2, 4, 2),
(3, 2, 1),
(3, 1, 1),
(4, 5, 2),
(5, 1, 1),
(5, 3, 1),
(15, 1, 1),
(15, 2, 1),
(15, 4, 1),
(16, 1, 3),
(16, 2, 2),
(18, 1, 2),
(18, 2, 2),
(18, 4, 2),
(21, 4, 1),
(21, 1, 1),
(22, 1, 1),
(23, 1, 1),
(24, 7, 8),
(25, 7, 1);

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

CREATE TABLE `stock` (
  `item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `stock`
--

INSERT INTO `stock` (`item_id`, `quantity`) VALUES
(1, 5),
(2, 8),
(4, 7),
(5, 3),
(7, 2),
(8, 18),
(10, 1),
(19, 100),
(20, 100);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `img_path` varchar(255) DEFAULT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'customer',
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `img_path`, `role`, `created_at`) VALUES
(2, '', 'ikeeveland@gmail.com', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'images/user_2_1760441920.png', 'customer', NULL),
(3, 'Luca Kaneshiro', 'lucakaneshiro@gmail.com', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'images/user_3_1760442691.png', 'customer', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barcode`
--
ALTER TABLE `barcode`
  ADD PRIMARY KEY (`barcode_ean`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `orderinfo`
--
ALTER TABLE `orderinfo`
  ADD PRIMARY KEY (`orderinfo_id`);

--
-- Indexes for table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `orderinfo`
--
ALTER TABLE `orderinfo`
  MODIFY `orderinfo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `stock`
--
ALTER TABLE `stock`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
