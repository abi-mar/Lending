-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 16, 2025 at 04:36 AM
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
-- Database: `lending`
--

-- --------------------------------------------------------

--
-- Table structure for table `collection`
--

CREATE TABLE `collection` (
  `service_fee` decimal(15,2) NOT NULL,
  `notary` decimal(15,2) NOT NULL,
  `doc_stamp` decimal(15,2) NOT NULL,
  `interest` decimal(15,2) NOT NULL,
  `LRF` decimal(15,2) NOT NULL,
  `savings` decimal(15,2) NOT NULL,
  `damayan` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Accumulation of collections saved when loan is applied';

--
-- Dumping data for table `collection`
--

INSERT INTO `collection` (`service_fee`, `notary`, `doc_stamp`, `interest`, `LRF`, `savings`, `damayan`) VALUES
(730.80, 50.00, 50.00, 1701.00, 400.00, 1260.00, 400.00);

-- --------------------------------------------------------

--
-- Table structure for table `collection_audit`
--

CREATE TABLE `collection_audit` (
  `service_fee` decimal(15,2) NOT NULL,
  `notary` decimal(15,2) NOT NULL,
  `doc_stamp` decimal(15,2) NOT NULL,
  `interest` decimal(15,2) NOT NULL,
  `LRF` decimal(15,2) NOT NULL,
  `savings` decimal(15,2) NOT NULL,
  `damayan` decimal(15,2) NOT NULL,
  `date_modified` datetime NOT NULL,
  `modified_by` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `collection_audit`
--

INSERT INTO `collection_audit` (`service_fee`, `notary`, `doc_stamp`, `interest`, `LRF`, `savings`, `damayan`, `date_modified`, `modified_by`) VALUES
(551.00, 50.00, 50.00, 1282.50, 400.00, 950.00, 400.00, '2025-01-03 03:40:23', 'abe'),
(730.80, 50.00, 50.00, 1701.00, 400.00, 1260.00, 400.00, '2025-01-03 03:55:24', 'abe');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `custno` int(11) NOT NULL,
  `firstname` varchar(100) DEFAULT NULL,
  `middlename` varchar(100) DEFAULT NULL,
  `surname` varchar(100) DEFAULT NULL,
  `suffix` varchar(10) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `mobileno` varchar(20) DEFAULT NULL,
  `image` varchar(100) DEFAULT NULL,
  `date_added` datetime NOT NULL DEFAULT current_timestamp(),
  `added_by` varchar(50) NOT NULL,
  `date_modified` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `balance` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`custno`, `firstname`, `middlename`, `surname`, `suffix`, `address`, `mobileno`, `image`, `date_added`, `added_by`, `date_modified`, `balance`) VALUES
(3, 'Lilebeth', 'Agad', 'Maravillas', '', 'Blk 2, Lot 16 Villa St. Joseph, Lower Zacate, Basak, Pardo, Cebu City', '09223212345', '1735873234_bb3b6b01ada84b2e9ebf.mp4', '2025-01-03 11:00:34', 'abe', '2025-01-12 19:36:55', 9288.46),
(4, 'John', 'Santos', 'Agoncillo', '', 'Quinano St., Alabang, Muntinlupa', '09655662727', '1735873704_faf601f5dba9b22d3365.jpg', '2025-01-03 11:08:24', 'abe', '2025-01-12 19:36:20', 7578.46),
(5, 'Elmer', 'Maravillas', 'Salazar', '', 'Basak, Pardo, Cebu City', '09884445555', '1735967877_256ea688d890aed6c2ec.jpg', '2025-01-04 13:17:57', 'abe', '2025-01-12 19:35:03', 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `loan_record`
--

CREATE TABLE `loan_record` (
  `row_id` int(11) NOT NULL,
  `custno` int(11) NOT NULL,
  `loan_date` date NOT NULL,
  `loan_amount` decimal(15,2) NOT NULL,
  `service_fee` decimal(15,2) NOT NULL,
  `notary` decimal(15,2) NOT NULL,
  `doc_stamp` decimal(15,2) NOT NULL,
  `net_proceeds` decimal(15,2) NOT NULL,
  `interest` decimal(15,2) NOT NULL,
  `lrf` decimal(15,2) NOT NULL,
  `savings` decimal(15,2) NOT NULL,
  `damayan` decimal(15,2) NOT NULL,
  `amount_topay` decimal(15,2) NOT NULL,
  `balance` decimal(15,2) NOT NULL,
  `weekly_amortization` decimal(15,2) NOT NULL,
  `date_added` datetime NOT NULL DEFAULT current_timestamp(),
  `added_by` varchar(50) NOT NULL,
  `date_modified` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `modified_by` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loan_record`
--

INSERT INTO `loan_record` (`row_id`, `custno`, `loan_date`, `loan_amount`, `service_fee`, `notary`, `doc_stamp`, `net_proceeds`, `interest`, `lrf`, `savings`, `damayan`, `amount_topay`, `balance`, `weekly_amortization`, `date_added`, `added_by`, `date_modified`, `modified_by`) VALUES
(18, 4, '2025-01-12', 6000.00, 348.00, 50.00, 50.00, 5552.00, 810.00, 400.00, 600.00, 400.00, 8210.00, 7578.46, 631.54, '2025-01-12 19:35:50', 'abe', '2025-01-12 19:36:20', ''),
(19, 3, '2025-01-12', 7500.00, 435.00, 50.00, 50.00, 6965.00, 1012.50, 400.00, 750.00, 400.00, 10062.50, 9288.46, 774.04, '2025-01-12 19:36:02', 'abe', '2025-01-12 19:36:55', '');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `row_id` int(11) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `payment_date` date NOT NULL,
  `date_added` datetime NOT NULL DEFAULT current_timestamp(),
  `added_by` varchar(50) NOT NULL,
  `loan_record_row_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`row_id`, `amount`, `payment_date`, `date_added`, `added_by`, `loan_record_row_id`) VALUES
(14, 631.54, '2025-01-12', '2025-01-12 19:36:20', 'abe', 18),
(15, 774.04, '2025-01-12', '2025-01-12 19:36:55', 'abe', 19);

-- --------------------------------------------------------

--
-- Table structure for table `scheduled_payment`
--

CREATE TABLE `scheduled_payment` (
  `row_id` int(11) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `date_paid` datetime DEFAULT NULL,
  `scheduled_date` date DEFAULT NULL,
  `added_by` varchar(40) DEFAULT NULL,
  `is_paid` bit(2) NOT NULL COMMENT '0=not paid\r\n1=paid',
  `remaining_debt` decimal(15,2) DEFAULT NULL COMMENT 'For tracking excess or deficient payment',
  `loan_record_row_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `scheduled_payment`
--

INSERT INTO `scheduled_payment` (`row_id`, `amount`, `date_paid`, `scheduled_date`, `added_by`, `is_paid`, `remaining_debt`, `loan_record_row_id`) VALUES
(248, 631.54, '2025-01-12 00:00:00', '2025-01-19', 'abe', b'01', 0.00, 18),
(249, 0.00, NULL, '2025-01-26', NULL, b'00', 631.54, 18),
(250, 0.00, NULL, '2025-02-02', NULL, b'00', 631.54, 18),
(251, 0.00, NULL, '2025-02-09', NULL, b'00', 631.54, 18),
(252, 0.00, NULL, '2025-02-16', NULL, b'00', 631.54, 18),
(253, 0.00, NULL, '2025-02-23', NULL, b'00', 631.54, 18),
(254, 0.00, NULL, '2025-03-02', NULL, b'00', 631.54, 18),
(255, 0.00, NULL, '2025-03-09', NULL, b'00', 631.54, 18),
(256, 0.00, NULL, '2025-03-16', NULL, b'00', 631.54, 18),
(257, 0.00, NULL, '2025-03-23', NULL, b'00', 631.54, 18),
(258, 0.00, NULL, '2025-03-30', NULL, b'00', 631.54, 18),
(259, 0.00, NULL, '2025-04-06', NULL, b'00', 631.54, 18),
(260, 0.00, NULL, '2025-04-13', NULL, b'00', 631.54, 18),
(261, 774.04, '2025-01-12 00:00:00', '2025-01-19', 'abe', b'01', 0.00, 19),
(262, 0.00, NULL, '2025-01-26', NULL, b'00', 774.04, 19),
(263, 0.00, NULL, '2025-02-02', NULL, b'00', 774.04, 19),
(264, 0.00, NULL, '2025-02-09', NULL, b'00', 774.04, 19),
(265, 0.00, NULL, '2025-02-16', NULL, b'00', 774.04, 19),
(266, 0.00, NULL, '2025-02-23', NULL, b'00', 774.04, 19),
(267, 0.00, NULL, '2025-03-02', NULL, b'00', 774.04, 19),
(268, 0.00, NULL, '2025-03-09', NULL, b'00', 774.04, 19),
(269, 0.00, NULL, '2025-03-16', NULL, b'00', 774.04, 19),
(270, 0.00, NULL, '2025-03-23', NULL, b'00', 774.04, 19),
(271, 0.00, NULL, '2025-03-30', NULL, b'00', 774.04, 19),
(272, 0.00, NULL, '2025-04-06', NULL, b'00', 774.04, 19),
(273, 0.00, NULL, '2025-04-13', NULL, b'00', 774.04, 19);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `date_added` datetime NOT NULL,
  `date_modified` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `date_added`, `date_modified`) VALUES
(4, 'abe', '$2y$10$9QJyV8Gmv0fSNN2MByylOO.bKWyVmRb4BG4LEvCzDtzhHD3J2THa2', '2024-12-25 09:50:25', '2024-12-25 09:50:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`custno`),
  ADD UNIQUE KEY `custno` (`custno`);

--
-- Indexes for table `loan_record`
--
ALTER TABLE `loan_record`
  ADD PRIMARY KEY (`row_id`),
  ADD UNIQUE KEY `row_id` (`row_id`),
  ADD KEY `FK_customer_custno` (`custno`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`row_id`),
  ADD KEY `FK_loan_record_row_id` (`loan_record_row_id`);

--
-- Indexes for table `scheduled_payment`
--
ALTER TABLE `scheduled_payment`
  ADD PRIMARY KEY (`row_id`),
  ADD KEY `FK_sp_loan_record_row_id` (`loan_record_row_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `custno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `loan_record`
--
ALTER TABLE `loan_record`
  MODIFY `row_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `row_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `scheduled_payment`
--
ALTER TABLE `scheduled_payment`
  MODIFY `row_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=274;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `loan_record`
--
ALTER TABLE `loan_record`
  ADD CONSTRAINT `FK_customer_custno` FOREIGN KEY (`custno`) REFERENCES `customer` (`custno`) ON DELETE CASCADE;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `FK_loan_record_row_id` FOREIGN KEY (`loan_record_row_id`) REFERENCES `loan_record` (`row_id`) ON DELETE CASCADE;

--
-- Constraints for table `scheduled_payment`
--
ALTER TABLE `scheduled_payment`
  ADD CONSTRAINT `FK_sp_loan_record_row_id` FOREIGN KEY (`loan_record_row_id`) REFERENCES `loan_record` (`row_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
