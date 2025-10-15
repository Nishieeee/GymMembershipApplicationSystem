-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 13, 2025 at 07:09 PM
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
-- Database: `gym`
--
CREATE DATABASE IF NOT EXISTS `gym` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `gym`;

-- --------------------------------------------------------

--
-- Table structure for table `members`
--
-- Creation: Oct 11, 2025 at 01:40 PM
--

CREATE TABLE `members` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `age` int(11) NOT NULL,
  `gender` enum('male','female','Others') NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone_no` varchar(55) NOT NULL,
  `password` varchar(15) NOT NULL,
  `role` enum('admin','member') DEFAULT 'member',
  `created_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`user_id`, `first_name`, `last_name`, `middle_name`, `age`, `gender`, `email`, `phone_no`, `password`, `role`, `created_at`) VALUES
(1, 'Jhon Clein', 'Pagarogan', 'Toribio', 20, 'male', 'test@gmail.com', '09366600119', 'test1234', 'member', '2025-10-01'),
(4, 'John', 'Smith', 'Toribio', 20, 'male', 'test02@gmail.com', '09366600119', 'test1234', 'member', '2025-10-05'),
(7, 'Roboute', 'Guilliman', 'Smith', 20, 'male', 'admin@gmail.com', '09366600119', 'admin123', 'admin', '2025-10-09');

-- --------------------------------------------------------

--
-- Table structure for table `membership_plans`
--
-- Creation: Oct 13, 2025 at 03:59 PM
--

CREATE TABLE `membership_plans` (
  `plan_id` int(11) NOT NULL,
  `plan_name` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  `duration_months` int(11) NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `status` enum('active','inactive','removed','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `membership_plans`
--

INSERT INTO `membership_plans` (`plan_id`, `plan_name`, `description`, `duration_months`, `price`, `status`) VALUES
(1, 'Basic Plan', 'Get access to gym equipment, locker room and with WIFI Access!', 1, 255.00, 'active'),
(2, 'Premium Plan', 'All basic features plus a personal trainer for 2x a week (with Nutrition Consultation)', 1, 599.00, 'active'),
(3, 'Elite Plan', 'All Premium features with unlimited personal training with Spa & Sauna Access!', 1, 899.00, 'active'),
(4, 'Giga plan', 'Get all elite plan access plus an unli subscription to our gym products', 1, 2999.00, 'inactive'),
(5, 'dull plan', 'access to gym equipment and water supply', 1, 59.00, 'removed'),
(6, 'Mr.Oympia Plan', 'get trained by hardocre body builders to achieve a greek like physique', 2, 1599.00, 'active');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--
-- Creation: Oct 01, 2025 at 08:24 AM
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `subscription_id` int(11) DEFAULT NULL,
  `amount` decimal(8,2) NOT NULL,
  `payment_date` date NOT NULL,
  `status` enum('paid','pending','failed') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `subscription_id`, `amount`, `payment_date`, `status`) VALUES
(1, 1, 599.00, '2025-11-11', 'paid');

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--
-- Creation: Oct 01, 2025 at 08:18 AM
--

CREATE TABLE `subscriptions` (
  `subscription_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('active','expired','cancelled') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subscriptions`
--

INSERT INTO `subscriptions` (`subscription_id`, `user_id`, `plan_id`, `start_date`, `end_date`, `status`) VALUES
(1, 1, 2, '2025-10-11', '2025-12-11', 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `membership_plans`
--
ALTER TABLE `membership_plans`
  ADD PRIMARY KEY (`plan_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `fk_subscription` (`subscription_id`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`subscription_id`),
  ADD KEY `fk_user` (`user_id`),
  ADD KEY `fk_plan` (`plan_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `membership_plans`
--
ALTER TABLE `membership_plans`
  MODIFY `plan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `subscription_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `fk_subscription` FOREIGN KEY (`subscription_id`) REFERENCES `subscriptions` (`subscription_id`);

--
-- Constraints for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `fk_plan` FOREIGN KEY (`plan_id`) REFERENCES `membership_plans` (`plan_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `members` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
