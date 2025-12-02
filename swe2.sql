-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 02, 2025 at 05:48 PM
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
-- Database: `swe2`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `username` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(150) NOT NULL,
  `admin_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`username`, `email`, `password`, `admin_id`) VALUES
('asd', 'asd@gmail.com', '$2y$10$Ml0g72q.0FWoKoq7.maSAeN8ZS3EU7wcHukylsAYzVSa5j58HCBv6', 5),
('ratbugod', '23-68023@g.batstate-u.edu.ph', '$2y$10$27V6iD0UUdUXTRv/lZ0LKerngpnfetoXnh1fRWU8tt40ci1hn7GzK', 6);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `category_name`) VALUES
(2, 'Laces'),
(5, 'Pins'),
(6, 'Textiles'),
(9, 'Bags/Pouches'),
(10, 'Woodworks'),
(12, 'Umbrellas'),
(13, 'Accessories'),
(14, 'Toiletries '),
(15, 'Mats'),
(16, 'Clothing'),
(17, 'Hats'),
(18, 'Containers'),
(19, 'Drinkware'),
(21, 'School Supplies');

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `order_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `data_created` varchar(64) DEFAULT NULL,
  `total_cost` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`order_id`, `customer_id`, `quantity`, `data_created`, `total_cost`, `created_at`) VALUES
(1, 3, 1, '11-19-2025 09:47 am', 23.00, '2025-11-19 08:47:01'),
(2, 3, 1, '11-19-2025 09:47 am', 67.00, '2025-11-19 08:47:37'),
(3, 3, 2, '11-19-2025 10:00 am', 90.00, '2025-11-19 09:00:09'),
(4, 4, 1, '12-01-2025 05:44 am', 67.00, '2025-12-01 04:44:26'),
(5, 6, 3, '12-01-2025 06:22 am', 69.00, '2025-12-01 05:22:05'),
(6, 9, 1, '12-02-2025 12:55 pm', 375.00, '2025-12-02 11:55:24'),
(7, 9, 1, '12-02-2025 02:02 pm', 150.00, '2025-12-02 13:02:21'),
(8, 9, 1, '12-02-2025 02:02 pm', 150.00, '2025-12-02 13:02:39'),
(9, 9, 1, '12-02-2025 02:03 pm', 150.00, '2025-12-02 13:03:20'),
(10, 9, 4, '12-02-2025 02:28 pm', 1305.00, '2025-12-02 13:28:22'),
(11, 9, 1, '12-02-2025 03:09 pm', 150.00, '2025-12-02 14:09:57'),
(12, 9, 1, '12-02-2025 04:11 pm', 315.00, '2025-12-02 15:11:59');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `unit_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `line_total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_name`, `quantity`, `unit_price`, `line_total`, `created_at`) VALUES
(1, 1, 'waieaiwydu', 1, 23.00, 23.00, '2025-11-19 08:47:01'),
(2, 2, 'dsjfsdjnkfdvkjhb', 1, 67.00, 67.00, '2025-11-19 08:47:37'),
(3, 3, 'dsjfsdjnkfdvkjhb', 1, 67.00, 67.00, '2025-11-19 09:00:09'),
(4, 3, 'waieaiwydu', 1, 23.00, 23.00, '2025-11-19 09:00:09'),
(5, 4, 'dsjfsdjnkfdvkjhb', 1, 67.00, 67.00, '2025-12-01 04:44:26'),
(6, 5, 'lace', 3, 23.00, 69.00, '2025-12-01 05:22:05'),
(7, 6, 'Laptop Mat', 1, 375.00, 375.00, '2025-12-02 11:55:24'),
(8, 7, 'Uniform Textile', 1, 150.00, 150.00, '2025-12-02 13:02:21'),
(9, 8, 'Uniform Textile', 1, 150.00, 150.00, '2025-12-02 13:02:39'),
(10, 9, 'Uniform Textile', 1, 150.00, 150.00, '2025-12-02 13:03:20'),
(11, 10, 'University Pin', 1, 50.00, 50.00, '2025-12-02 13:28:22'),
(12, 10, 'Jute Bag', 1, 315.00, 315.00, '2025-12-02 13:28:22'),
(13, 10, 'Wooden Tray', 2, 470.00, 940.00, '2025-12-02 13:28:22'),
(14, 11, 'Uniform Textile', 1, 150.00, 150.00, '2025-12-02 14:09:57'),
(15, 12, 'Jute Bag', 1, 315.00, 315.00, '2025-12-02 15:11:59');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product_id` double NOT NULL,
  `product_name` varchar(150) NOT NULL,
  `product_price` int(11) NOT NULL,
  `image_file_name` varchar(300) NOT NULL,
  `category_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  `category_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `product_name`, `product_price`, `image_file_name`, `category_id`, `quantity`, `category_name`) VALUES
(34, 'Lace', 70, 'product-image/24b5a36d251d34b5a3108691b4fbd1d0cat-1.jpg', 0, 56, 'Laces'),
(35, 'Uniform Textile', 150, 'product-image/e42a638f10be8ba0b27342fc16266b8acat-5.jpg', 0, 56, 'Textiles'),
(36, 'University Pin', 50, 'product-image/579c309dad1a4e52441519de42cb7c00cat-2.jpg', 0, 76, 'Pins'),
(37, 'Notebook', 120, 'product-image/6120bbcdb6c5fe1864cb9b036a0ae8cdcat-4.jpg', 0, 77, 'School Supplies'),
(38, 'Bath Towel', 350, 'product-image/4b780e6dcf43eb242263ccbe0062e66aBATHTOWEL.PNG', 0, 50, 'Toiletries '),
(39, 'Jute Bag', 315, 'product-image/a2efe0a29ed9743139d3eb6b4ab9eda4JUTEBAGMED.PNG', 0, 50, 'Bags/Pouches'),
(40, 'Laptop Mat', 375, 'product-image/487468e3bdac72ee1aaf46cdd94be362LAPTOPMAT.PNG', 0, 50, 'Mats'),
(41, 'Corporate Jacket', 1100, 'product-image/b799aceb095bb1ab9230102cbdc64e5fCORPORATEJACKET.PNG', 0, 50, 'Clothing'),
(42, 'Eco Bag', 40, 'product-image/5b633bfbf7cff48a7a1142a0c2a8ca25ECOBAG.PNG', 0, 50, 'Bags/Pouches'),
(43, 'Two Toned Umbrella ', 220, 'product-image/34c193772b6ec662b6a72e47b857472fTWOTONEDUMB.PNG', 0, 40, 'Umbrellas'),
(44, 'Folding Umbrella', 255, 'product-image/55d392d75eb6118d72147ee1b5812938FOLDINGUMBRELLA.PNG', 0, 40, 'Umbrellas'),
(45, 'LunchBox', 220, 'product-image/cfbc8c1c9b3df54a79008cb140b9b3f7LUNCHBOX.PNG', 0, 50, 'Containers'),
(46, 'BI-Fold Wallet', 265, 'product-image/7235aab40435611d83c65c5f449359acBIFOLDWALLET.PNG', 0, 50, 'Accessories'),
(47, 'Wooden CheeseBoard', 530, 'product-image/af41d06566efbe4d812541105f88b34bWOODENCHEESEBOARD.PNG', 0, 50, 'Woodworks'),
(48, 'Wooden Tray', 470, 'product-image/322c32aa38474df3bd4194708886dcf5WOODENTRAY.PNG', 0, 50, 'Woodworks'),
(49, 'Bucket Hat', 330, 'product-image/b7f0d3c68bc20995028224950ddc0c30BUCKETHAT.PNG', 0, 50, 'Hats'),
(50, 'Pad Paper', 70, 'product-image/c09dd1ac8c8e6453f6ef614418040a54PADPAPER.PNG', 0, 50, 'School Supplies'),
(51, 'Travel Bag Tag', 205, 'product-image/c1d1f354b9e97257dd8962a95e8a2330TRAVELBAGTAG.PNG', 0, 90, 'Accessories'),
(52, 'Wooden Coaster', 145, 'product-image/57362e67ae3a0beb19dbd461eb113aa9WOODENCOASTER.PNG', 0, 50, 'Woodworks'),
(53, 'Pencils', 20, 'product-image/89519d00cac41da59e3588728468f705PENCIL.PNG', 0, 100, 'School Supplies'),
(54, 'Tumbler with Cup', 410, 'product-image/a701069f0244c1585a0353871bca5662TUMBLERWITHCUP.PNG', 0, 50, 'Drinkware'),
(55, 'Travel Pouch', 280, 'product-image/a0d7fbb315c39b154788315fa73c2df9TRAVELPOUCH.PNG', 0, 50, 'Bags/Pouches'),
(56, 'Clear Mug', 150, 'product-image/3556db51e434d522bcb2cdf39ec4d173CLEARMUG.PNG', 0, 50, 'Drinkware');

-- --------------------------------------------------------

--
-- Table structure for table `shopping_cart`
--

CREATE TABLE `shopping_cart` (
  `user_email` varchar(300) NOT NULL,
  `product_name` varchar(300) NOT NULL,
  `cart_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `UserId` int(11) NOT NULL,
  `email` varchar(150) NOT NULL,
  `role` varchar(70) NOT NULL,
  `session` varchar(200) NOT NULL,
  `phone_number` varchar(300) NOT NULL,
  `customerName` varchar(150) NOT NULL,
  `password` varchar(150) DEFAULT NULL,
  `IMG_URL` varchar(500) NOT NULL DEFAULT 'https://external-content.duckduckgo.com/iu/?u=http%3A%2F%2Fwww.pngall.com%2Fwp-content%2Fuploads%2F5%2FProfile-Avatar-PNG.png&f=1&nofb=1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`UserId`, `email`, `role`, `session`, `phone_number`, `customerName`, `password`, `IMG_URL`) VALUES
(6, 'asd@gmail.com', 'customer', '', '09455813309', 'asd', '$2y$10$X73v/X.DLo6rlu1kG90uS.c45fflP1ROffgFjgST9X9OPUT7CgzfC', 'https://external-content.duckduckgo.com/iu/?u=http%3A%2F%2Fwww.pngall.com%2Fwp-content%2Fuploads%2F5%2FProfile-Avatar-PNG.png&f=1&nofb=1'),
(8, '23-64282@g.batstate-u.edu.ph', 'customer', '', '232323', 'asd', '$2y$10$f8Wk812VRnQ58DZeei/AD.TuynObFQKPNam8un0chnItdKgzwxG2u', 'https://external-content.duckduckgo.com/iu/?u=http%3A%2F%2Fwww.pngall.com%2Fwp-content%2Fuploads%2F5%2FProfile-Avatar-PNG.png&f=1&nofb=1'),
(9, '23-68023@g.batstate-u.edu.ph', 'customer', '', '', 'ratbugod', '$2y$10$WU3X0IPlXMeyolyHk8bPXuvXRV1e0oWkKM3etCTGzRp7FtKdTkbEm', 'https://external-content.duckduckgo.com/iu/?u=http%3A%2F%2Fwww.pngall.com%2Fwp-content%2Fuploads%2F5%2FProfile-Avatar-PNG.png&f=1&nofb=1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `shopping_cart`
--
ALTER TABLE `shopping_cart`
  ADD PRIMARY KEY (`cart_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`UserId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `product_id` double NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `shopping_cart`
--
ALTER TABLE `shopping_cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `UserId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
