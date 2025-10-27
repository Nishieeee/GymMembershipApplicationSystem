-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 27, 2025 at 02:51 PM
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
  `date_of_birth` date NOT NULL,
  `gender` enum('male','female','Others') NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone_no` varchar(55) NOT NULL,
  `password` varchar(15) NOT NULL,
  `role` enum('admin','member','trainer') DEFAULT 'member',
  `created_at` date DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`user_id`, `first_name`, `last_name`, `middle_name`, `date_of_birth`, `gender`, `email`, `phone_no`, `password`, `role`, `created_at`, `status`) VALUES
(1, 'Jhon Clein', 'Pagarogan', 'Toribio', '2005-10-30', 'male', 'test@gmail.com', '09366600119', 'test1234', 'member', '2025-10-01', 'active'),
(4, 'John', 'Smith', 'Toribio', '2021-10-13', 'male', 'test02@gmail.com', '09366600119', 'test1234', 'member', '2025-10-05', 'active'),
(7, 'Roboute', 'Guilliman', 'Smith', '2015-10-04', 'male', 'admin@gmail.com', '09366600119', 'admin123', 'admin', '2025-10-09', 'active'),
(8, 'Jane', 'Doe', 'S', '2025-10-01', 'female', 'test03@gmail.com', '', 'test1234', 'member', '2025-10-21', 'inactive'),
(17, 'Jamsik', 'Doe', '', '2010-07-21', 'male', 'test04@gmail.com', '', '', 'member', '2025-10-26', 'active'),
(18, 'Kevin', 'Herodias', 'Ramos', '2011-10-30', 'male', 'trainer@gmail.com', '09123456789', 'test1234', 'trainer', '2025-10-27', 'active');

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
  `status` enum('paid','pending','failed','refunded') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `subscription_id`, `amount`, `payment_date`, `status`) VALUES
(1, 1, 599.00, '2025-11-11', 'paid'),
(11, 17, 899.00, '2025-11-20', 'paid'),
(12, 18, 899.00, '2025-11-21', 'refunded'),
(13, 19, 899.00, '2025-11-21', 'paid'),
(14, 22, 599.00, '2025-11-25', 'paid');

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
  `payment_status` enum('completed','pending','failed','refunded') NOT NULL DEFAULT 'completed',
  `remarks` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment_transaction`
--

INSERT INTO `payment_transaction` (`transaction_id`, `subscription_id`, `payment_id`, `payment_method`, `transaction_type`, `payment_status`, `remarks`, `created_at`, `updated_at`) VALUES
(39, 22, 14, 'gcash', 'new', 'completed', '', '2025-10-26 17:12:33', '2025-10-26 23:33:06'),
(40, 18, 12, 'gcash', 'upgrade', 'completed', NULL, '2025-10-27 00:30:23', '2025-10-27 00:34:24'),
(41, 17, 11, 'paymaya', 'renewal', 'completed', NULL, '2025-10-27 00:33:36', '2025-10-27 00:33:57'),
(42, 19, 13, 'paymaya', 'new', 'completed', NULL, '2025-10-27 00:35:28', '2025-10-27 00:51:15'),
(45, 1, 1, 'card', 'new', 'completed', '', '2025-10-27 00:48:01', '2025-10-27 00:52:25'),
(55, 19, 13, 'paymaya', 'new', 'completed', '', '2025-10-27 14:26:49', '2025-10-27 14:26:49');

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
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `session_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `trainer_id` int(11) NOT NULL,
  `session_date` datetime NOT NULL,
  `status` enum('scheduled','completed','cancelled') DEFAULT 'scheduled',
  `notes` text NOT NULL,
  `created_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`session_id`, `user_id`, `trainer_id`, `session_date`, `status`, `notes`, `created_at`) VALUES
(3, 1, 1, '2025-11-03 10:10:00', 'cancelled', 'cardio session', '2025-10-27'),
(11, 1, 1, '2025-11-03 22:23:00', 'completed', '', '2025-10-27'),
(13, 4, 1, '2025-11-27 22:26:00', 'scheduled', '', '2025-10-27');

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
(19, 4, 3, '2025-10-22', '2025-11-21', 'active'),
(22, 17, 2, '2025-10-26', '2025-11-25', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `trainers`
--

CREATE TABLE `trainers` (
  `trainer_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `specialization` varchar(100) DEFAULT NULL,
  `experience_years` int(11) DEFAULT NULL,
  `contact_no` varchar(20) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `join_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trainers`
--

INSERT INTO `trainers` (`trainer_id`, `user_id`, `specialization`, `experience_years`, `contact_no`, `status`, `join_date`) VALUES
(1, 18, 'Cardio', 10, '09123456789', 'active', '2025-10-27 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `trainer_members`
--

CREATE TABLE `trainer_members` (
  `assignment_id` int(11) NOT NULL,
  `trainer_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `assigned_date` datetime DEFAULT current_timestamp(),
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trainer_members`
--

INSERT INTO `trainer_members` (`assignment_id`, `trainer_id`, `user_id`, `assigned_date`, `status`) VALUES
(2, 1, 4, '2025-10-27 00:00:00', 'active'),
(3, 1, 1, '2025-10-27 00:00:00', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `trainer_requests`
--

CREATE TABLE `trainer_requests` (
  `request_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `trainer_id` int(11) NOT NULL,
  `status` enum('pending','accepted','rejected') DEFAULT 'pending',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trainer_requests`
--

INSERT INTO `trainer_requests` (`request_id`, `user_id`, `trainer_id`, `status`, `created_at`) VALUES
(1, 17, 1, 'pending', '2025-10-17 00:00:00');

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
  `session_type` varchar(50) NOT NULL,
  `payment_method` varchar(100) NOT NULL,
  `payment_amount` int(11) NOT NULL,
  `visit_time` datetime DEFAULT current_timestamp(),
  `end_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `walk_ins`
--

INSERT INTO `walk_ins` (`walkin_id`, `first_name`, `last_name`, `middle_name`, `email`, `contact_no`, `session_type`, `payment_method`, `payment_amount`, `visit_time`, `end_date`) VALUES
(1, 'Jhon Clein', 'Toribio', NULL, 'test@gmail.com', '09366600119', 'Day Pass', 'Gcash', 59, '2025-10-25 17:57:17', '2025-10-26 00:00:00'),
(2, 'Jane', 'Doe', 'S', 'test03@gmail.com', '+639694685197', 'single', 'gcash', 20, '2025-10-26 07:27:49', '2025-10-27 07:27:49');

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
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`session_id`),
  ADD KEY `fk2_user_id` (`user_id`),
  ADD KEY `fk_trainer_id` (`trainer_id`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`subscription_id`),
  ADD KEY `fk_user` (`user_id`),
  ADD KEY `fk_plan` (`plan_id`);

--
-- Indexes for table `trainers`
--
ALTER TABLE `trainers`
  ADD PRIMARY KEY (`trainer_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `trainer_members`
--
ALTER TABLE `trainer_members`
  ADD PRIMARY KEY (`assignment_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `trainer_members_ibfk_1` (`trainer_id`);

--
-- Indexes for table `trainer_requests`
--
ALTER TABLE `trainer_requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `trainer_id` (`trainer_id`);

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
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `membership_plans`
--
ALTER TABLE `membership_plans`
  MODIFY `plan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `payment_transaction`
--
ALTER TABLE `payment_transaction`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `session_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `subscription_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `trainers`
--
ALTER TABLE `trainers`
  MODIFY `trainer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `trainer_members`
--
ALTER TABLE `trainer_members`
  MODIFY `assignment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `trainer_requests`
--
ALTER TABLE `trainer_requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `walk_ins`
--
ALTER TABLE `walk_ins`
  MODIFY `walkin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
-- Constraints for table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `fk2_user_id` FOREIGN KEY (`user_id`) REFERENCES `members` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_trainer_id` FOREIGN KEY (`trainer_id`) REFERENCES `trainers` (`trainer_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `fk_plan` FOREIGN KEY (`plan_id`) REFERENCES `membership_plans` (`plan_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `members` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `trainers`
--
ALTER TABLE `trainers`
  ADD CONSTRAINT `trainers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `members` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `trainer_members`
--
ALTER TABLE `trainer_members`
  ADD CONSTRAINT `trainer_members_ibfk_1` FOREIGN KEY (`trainer_id`) REFERENCES `trainers` (`trainer_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `trainer_members_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `members` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `trainer_requests`
--
ALTER TABLE `trainer_requests`
  ADD CONSTRAINT `trainer_requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `members` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `trainer_requests_ibfk_2` FOREIGN KEY (`trainer_id`) REFERENCES `trainers` (`trainer_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
