-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 05, 2024 at 06:05 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sargento`
--

-- --------------------------------------------------------

--
-- Table structure for table `mto`
--

CREATE TABLE `mto` (
  `order_id` int NOT NULL,
  `height` float DEFAULT NULL,
  `width` float DEFAULT NULL,
  `depth` float DEFAULT NULL,
  `material` varchar(32) DEFAULT NULL,
  `fabric` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int NOT NULL,
  `user_id` int NOT NULL,
  `furniture_type` varchar(32) NOT NULL,
  `order_type` enum('repair','mto') NOT NULL,
  `order_status` enum('new_order','pending_downpayment','ready_for_pickup','in_production','pending_fullpayment','out_for_delivery','received') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'new_order',
  `del_method` enum('third_party','self') NOT NULL,
  `del_address` text NOT NULL,
  `note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `quoted_price` float DEFAULT NULL,
  `is_accepted` enum('pending','accepted','rejected') NOT NULL DEFAULT 'pending',
  `refusal_reason` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Triggers `orders`
--
DELIMITER $$
CREATE TRIGGER `insert_payment_and_order_date` AFTER INSERT ON `orders` FOR EACH ROW BEGIN
    DECLARE p_date DATE;
    SET p_date = CURRENT_DATE; -- Assuming placement date is current date

    INSERT INTO payment (order_id)
    VALUES (NEW.order_id); -- Assuming the initial amount is 0

    INSERT INTO order_date (order_id, placement_date, est_completion_date)
    VALUES (NEW.order_id, p_date, DATE_ADD(p_date, INTERVAL 2 WEEK));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `order_date`
--

CREATE TABLE `order_date` (
  `order_id` int NOT NULL,
  `placement_date` date NOT NULL,
  `est_completion_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `order_id` int NOT NULL,
  `payment_status` enum('unpaid','partially_paid','fully_paid') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'unpaid',
  `payment_method_downpayment` enum('gcash','paymaya','cash','bank_transfer') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `downpayment_img_path` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `payment_method_fullpayment` enum('gcash','paymaya','cash','bank_transfer') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `fullpayment_img_path` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `repair`
--

CREATE TABLE `repair` (
  `order_id` int NOT NULL,
  `pickup_method` enum('third_party','self') NOT NULL,
  `pickup_address` text NOT NULL,
  `repair_img_path` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` char(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `user_type` enum('customer','admin') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `user_address` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `contact_number` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `mto`
--
ALTER TABLE `mto`
  ADD KEY `order_id_fk_mto` (`order_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id_fk` (`user_id`);

--
-- Indexes for table `order_date`
--
ALTER TABLE `order_date`
  ADD KEY `order_id_fk_order_date` (`order_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD KEY `order_id_fk_payment` (`order_id`);

--
-- Indexes for table `repair`
--
ALTER TABLE `repair`
  ADD KEY `order_id_fk_repair` (`order_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `mto`
--
ALTER TABLE `mto`
  ADD CONSTRAINT `order_id_fk_mto` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `order_date`
--
ALTER TABLE `order_date`
  ADD CONSTRAINT `order_id_fk_order_date` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `order_id_fk_payment` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `repair`
--
ALTER TABLE `repair`
  ADD CONSTRAINT `order_id_fk_repair` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
