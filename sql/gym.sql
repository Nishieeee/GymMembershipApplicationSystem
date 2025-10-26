-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 25, 2025 at 11:59 AM
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

-- --------------------------------------------------------

--
-- Table structure for table `members`
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
  `created_at` date DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`user_id`, `first_name`, `last_name`, `middle_name`, `age`, `gender`, `email`, `phone_no`, `password`, `role`, `created_at`, `status`) VALUES
(1, 'Jhon Clein', 'Pagarogan', 'Toribio', 20, 'male', 'test@gmail.com', '09366600119', 'test1234', 'member', '2025-10-01', 'active'),
(4, 'John', 'Smith', 'Toribio', 20, 'male', 'test02@gmail.com', '09366600119', 'test1234', 'member', '2025-10-05', 'active'),
(7, 'Roboute', 'Guilliman', 'Smith', 20, 'male', 'admin@gmail.com', '09366600119', 'admin123', 'admin', '2025-10-09', 'active'),
(8, 'Jane', 'Doe', 'S', 32, 'female', 'test03@gmail.com', '', 'test1234', 'member', '2025-10-21', 'inactive');

-- --------------------------------------------------------

--
-- Table structure for table `membership_plans`
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
(5, 'Walk In', 'access to gym equipment and water supply', 1, 59.00, 'active'),
(6, 'Mr.Oympia Plan', 'get trained by hardcore body builders to achieve a greek like physique', 2, 1599.00, 'inactive');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
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
(1, 1, 599.00, '2025-11-11', 'paid'),
(11, 17, 899.00, '2025-11-20', 'paid'),
(12, 18, 899.00, '2025-11-21', 'paid'),
(13, 19, 899.00, '2025-11-21', 'paid');

-- --------------------------------------------------------

--
-- Table structure for table `payment_transaction`
--

CREATE TABLE `payment_transaction` (
  `transaction_id` int(11) NOT NULL,
  `subscription_id` int(11) NOT NULL,
  `payment_id` int(11) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `transaction_type` enum('new','renewal','upgrade','downgrade','refund') DEFAULT 'new',
  `payment_status` enum('completed','pending','failed','refunded') DEFAULT 'pending',
  `remarks` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment_transaction`
--

INSERT INTO `payment_transaction` (`transaction_id`, `subscription_id`, `payment_id`, `payment_method`, `transaction_type`, `payment_status`, `remarks`, `created_at`, `updated_at`) VALUES
(15, 19, 13, 'gcash', 'new', NULL, '', '2025-10-25 14:40:15', '2025-10-25 14:40:15');

-- --------------------------------------------------------

--
-- Table structure for table `plan_features`
--

CREATE TABLE `plan_features` (
  `user_id` int(11) NOT NULL,
  `feature_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
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
(1, 1, 2, '2025-10-21', '2025-11-20', 'active'),
(17, 4, 3, '2025-10-21', '2025-11-22', 'expired'),
(18, 4, 3, '2025-10-22', '2025-11-21', 'cancelled'),
(19, 4, 3, '2025-10-22', '2025-11-21', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `walk_ins`
--

CREATE TABLE `walk_ins` (
  `walkin_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `contact_no` varchar(20) DEFAULT NULL,
  `session_type` int(11) NOT NULL,
  `payment_amount` int(11) NOT NULL,
  `visit_time` datetime DEFAULT current_timestamp(),
  `end_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `walk_ins`
--

INSERT INTO `walk_ins` (`walkin_id`, `first_name`, `last_name`, `middle_name`, `email`, `contact_no`, `session_type`, `payment_amount`, `visit_time`, `end_date`) VALUES
(1, 'Jhon Clein', 'Toribio', NULL, 'test@gmail.com', '09366600119', 0, 59, '2025-10-25 17:57:17', '2025-10-26 00:00:00');

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
-- Indexes for table `payment_transaction`
--
ALTER TABLE `payment_transaction`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `fk_transaction_user` (`subscription_id`),
  ADD KEY `fk_payment_id` (`payment_id`);

--
-- Indexes for table `plan_features`
--
ALTER TABLE `plan_features`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`subscription_id`),
  ADD KEY `fk_user` (`user_id`),
  ADD KEY `fk_plan` (`plan_id`);

--
-- Indexes for table `walk_ins`
--
ALTER TABLE `walk_ins`
  ADD PRIMARY KEY (`walkin_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `membership_plans`
--
ALTER TABLE `membership_plans`
  MODIFY `plan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `payment_transaction`
--
ALTER TABLE `payment_transaction`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `subscription_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `walk_ins`
--
ALTER TABLE `walk_ins`
  MODIFY `walkin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `fk_subscription` FOREIGN KEY (`subscription_id`) REFERENCES `subscriptions` (`subscription_id`);

--
-- Constraints for table `payment_transaction`
--
ALTER TABLE `payment_transaction`
  ADD CONSTRAINT `fk_payment_id` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`payment_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_transaction_user` FOREIGN KEY (`subscription_id`) REFERENCES `subscriptions` (`subscription_id`) ON DELETE CASCADE;

--
-- Constraints for table `plan_features`
--
ALTER TABLE `plan_features`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `members` (`user_id`) ON UPDATE CASCADE;

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
