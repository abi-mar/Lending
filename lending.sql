-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 08, 2025 at 03:25 PM
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
-- Table structure for table `account_officer`
--

CREATE TABLE `account_officer` (
  `row_id` int(11) NOT NULL,
  `firstname` varchar(100) DEFAULT NULL,
  `middlename` varchar(100) DEFAULT NULL,
  `surname` varchar(100) DEFAULT NULL,
  `date_added` datetime NOT NULL DEFAULT current_timestamp(),
  `added_by` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account_officer`
--

INSERT INTO `account_officer` (`row_id`, `firstname`, `middlename`, `surname`, `date_added`, `added_by`) VALUES
(1, 'Topher', NULL, NULL, '2025-02-08 19:59:30', 'abe'),
(2, 'Denmark', NULL, NULL, '2025-02-08 19:59:30', 'abe'),
(3, 'Calvin', NULL, NULL, '2025-02-08 20:00:07', 'abe'),
(4, 'Gilbert', NULL, NULL, '2025-02-08 20:00:07', 'abe');

--
-- Table structure for table `collection`
--

CREATE TABLE `collection` (
  `id` int(11) NOT NULL,
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

INSERT INTO `collection` (`id`, `service_fee`, `notary`, `doc_stamp`, `interest`, `LRF`, `savings`, `damayan`) VALUES
(1, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00);

--
-- Triggers `collection`
--
DELIMITER $$
CREATE TRIGGER `before_collection_update` BEFORE UPDATE ON `collection` FOR EACH ROW BEGIN
    INSERT INTO collection_audit (service_fee, notary, doc_stamp, interest, LRF, savings, damayan, date_modified)
    VALUES (NEW.service_fee, NEW.notary, NEW.doc_stamp, NEW.interest, NEW.LRF, NEW.savings, NEW.damayan, NOW());
END
$$
DELIMITER ;

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
  `modified_by` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `groupno` int(11) DEFAULT NULL COMMENT 'client group where it belongs. Can be null if client does not belong to a group.',
  `account_officer_id` int(11) NOT NULL COMMENT 'account_officer_row_id',
  `image` varchar(100) DEFAULT NULL,
  `date_added` datetime NOT NULL DEFAULT current_timestamp(),
  `added_by` varchar(50) NOT NULL,
  `date_modified` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `balance` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------

--
-- Table structure for table `groupx`
--

CREATE TABLE `groupx` (
  `groupno` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `date_added` datetime NOT NULL DEFAULT current_timestamp(),
  `added_by` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


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

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `custno` int(11) DEFAULT NULL,
  `notes` text NOT NULL,
  `date_added` datetime NOT NULL DEFAULT current_timestamp(),
  `added_by` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(4, 'abe', '$2y$10$9QJyV8Gmv0fSNN2MByylOO.bKWyVmRb4BG4LEvCzDtzhHD3J2THa2', '2024-12-25 09:50:25', '2024-12-25 09:50:25'),
(5, 'guest', '$2y$10$ehPLp4jW4rJ6Y7au2DNqauXZABUIwLKwbmlGjpEZeZDDY1NYcczOm', '2025-01-27 11:28:57', '2025-01-27 11:28:57');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account_officer`
--
ALTER TABLE `account_officer`
  ADD PRIMARY KEY (`row_id`);

--
-- Indexes for table `collection`
--
ALTER TABLE `collection`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`custno`),
  ADD UNIQUE KEY `custno` (`custno`);

--
-- Indexes for table `groupx`
--
ALTER TABLE `groupx`
  ADD PRIMARY KEY (`groupno`);

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
-- AUTO_INCREMENT for table `account_officer`
--
ALTER TABLE `account_officer`
  MODIFY `row_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `collection`
--
ALTER TABLE `collection`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `custno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `loan_record`
--
ALTER TABLE `loan_record`
  MODIFY `row_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `row_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `scheduled_payment`
--
ALTER TABLE `scheduled_payment`
  MODIFY `row_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

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
