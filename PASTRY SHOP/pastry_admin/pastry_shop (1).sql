-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 22, 2026 at 09:23 AM
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
-- Database: `pastry_shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'admin12345', '$2y$10$emZflPKuvXuQGvLd2aPfI.kcnTTcwBwq4AG/rZx2YxkWzjXQ0WeDW', '2026-02-21 06:17:18');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_description` varchar(255) DEFAULT NULL,
  `product_price` decimal(10,2) NOT NULL,
  `product_quantity` int(11) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `status` enum('active','archived','deleted') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `product_description`, `product_price`, `product_quantity`, `image_path`, `status`) VALUES
(1, 'Croissant', 'Sweet Croissant', 95.00, 50, 'img/1771666557_croissant.png', 'active'),
(13, 'Cringkols', 'Cookie', 8.00, 10, 'img/1771745020_crinkles.jpg', 'deleted'),
(14, 'Chewy Dubai Chocolate', 'Pistachio', 180.00, 50, 'img/1771745256_Chewy.jpg', 'active'),
(15, 'Tiramisu Cake', 'No Bake Dessert', 58.00, 50, 'img/1771746197_tiramisu.jpg', 'active'),
(16, 'Bagel', 'Ring-shaped Bread Roll', 65.00, 50, 'img/1771746679_bagel.jpg', 'active'),
(17, 'Matcha Cookie', 'Japanese-inspired', 35.00, 50, 'img/1771747123_matcha.jpg', 'active'),
(19, 'Dubai Chocolate Bars', 'Pistachio, Biscoff, Peanut Butter & Jelly, and Nutella', 55.00, 50, 'img/1771747311_chocobar.jpg', 'active'),
(20, 'Letche Flan', 'So Sweet', 40.00, 50, 'img/1771747441_letche.jpg', 'active'),
(21, 'Cinnamon Roll', 'A sweet baked dough filled with a cinnamon-sugar filling', 95.00, 50, 'img/1771747571_cinnamonrolls.jpg', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `password`, `created_at`) VALUES
(1, 'pastry', 'pastry@gmail.com', '$2y$10$vxmheJsXMyDzGZNkcVZv0OCfqEI/a0FRCk3C94JZnnrE.a6XgGQNa', '2026-01-11 12:44:29'),
(2, 'Marvin Cadorna', 'marvincadorna07@gmail.com', '$2y$10$kHz8NlY5rXbs7kXgMd5/SOWPbWXFSk2w4ZXaEvRwVs1tvyJHBdldC', '2026-02-21 09:40:23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
