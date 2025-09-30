-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 30, 2025 at 01:03 PM
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

-- --------------------------------------------------------

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
(1, 0.00, 0.00, 0.00, 934.56, 369.24, 692.28, 369.24);

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

--
-- Dumping data for table `collection_audit`
--

INSERT INTO `collection_audit` (`service_fee`, `notary`, `doc_stamp`, `interest`, `LRF`, `savings`, `damayan`, `date_modified`, `modified_by`) VALUES
(0.00, 0.00, 0.00, 77.88, 30.77, 57.69, 30.77, '2025-02-10 11:18:33', NULL),
(0.00, 0.00, 0.00, 155.76, 61.54, 115.38, 61.54, '2025-02-10 11:24:35', NULL),
(0.00, 0.00, 0.00, 233.64, 92.31, 173.07, 92.31, '2025-02-10 11:30:35', NULL),
(0.00, 0.00, 0.00, 311.52, 123.08, 230.76, 123.08, '2025-08-10 18:05:53', NULL),
(0.00, 0.00, 0.00, 389.40, 153.85, 288.45, 153.85, '2025-08-10 18:07:16', NULL),
(0.00, 0.00, 0.00, 467.28, 184.62, 346.14, 184.62, '2025-08-10 18:11:15', NULL),
(0.00, 0.00, 0.00, 545.16, 215.39, 403.83, 215.39, '2025-08-10 18:35:00', NULL),
(0.00, 0.00, 0.00, 623.04, 246.16, 461.52, 246.16, '2025-08-10 18:35:25', NULL),
(0.00, 0.00, 0.00, 700.92, 276.93, 519.21, 276.93, '2025-08-10 18:36:14', NULL),
(0.00, 0.00, 0.00, 778.80, 307.70, 576.90, 307.70, '2025-08-10 18:46:27', NULL),
(0.00, 0.00, 0.00, 856.68, 338.47, 634.59, 338.47, '2025-08-10 18:46:37', NULL),
(0.00, 0.00, 0.00, 934.56, 369.24, 692.28, 369.24, '2025-08-10 18:46:47', NULL);

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

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`custno`, `firstname`, `middlename`, `surname`, `suffix`, `address`, `mobileno`, `groupno`, `account_officer_id`, `image`, `date_added`, `added_by`, `date_modified`, `balance`) VALUES
(8, 'Elmer', 'Asilo', 'Maravillas', '', 'Blk 2, Lot 16 Villa St. Joseph, Lower Zacate, Basak, Pardo, Cebu City', '09223212345', NULL, 1, '1737887023_6d7428759489da82d7eb.jpg', '2025-01-26 18:23:43', 'abe', '2025-02-08 20:57:52', 8210.00),
(9, 'Marita', 'Sanson', 'Salazar', '', 'Basak, Pardo, Cebu City', '09655662727', NULL, 2, '1737887054_828e724436067d95d41a.jpg', '2025-01-26 18:24:14', 'abe', '2025-02-10 11:30:35', 0.00),
(10, 'Lilebeth', 'Agad', 'Maravillas', '', 'Baang, Catigbian', '09884445555', NULL, 3, '1737887088_c7564b36aa20aa20bb42.jpg', '2025-01-26 18:24:48', 'abe', '2025-08-06 09:49:10', 13150.00),
(11, 'Mae', 'Mai', 'Bartosa', '', 'Manila City', '069345345645', NULL, 1, '1739020656_29e1ca4ce91b26b35abb.jpg', '2025-02-08 21:17:36', 'guest', '2025-03-02 11:01:08', 8827.50),
(12, 'Ali', '', 'Gee', '', 'Hamburg', '213124555', 0, 2, '1739020834_771d0ae9076856399719.jpg', '2025-02-08 21:20:34', 'guest', NULL, 0.00),
(13, 'Gere', '', 'asdf', '', 'Davao', '35123213', 0, 1, '1739020931_11ca7cf1fe3a7049aea5.jpg', '2025-02-08 21:22:11', 'guest', '2025-08-10 18:46:47', 0.00),
(14, 'Michel', '', 'Gambu', '', 'Palawan', '1234567', 1, 4, '1739021027_fd16fb7a3ba691f87437.jpg', '2025-02-08 21:23:47', 'guest', '2025-02-08 22:20:00', 0.00),
(15, 'Ella', '', 'Grem', '', 'Cebu', '2131244', 2, 1, '1739024435_6af39bc4ae74da1df770.jpg', '2025-02-08 22:20:35', 'guest', NULL, 0.00);

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

--
-- Dumping data for table `groupx`
--

INSERT INTO `groupx` (`groupno`, `name`, `date_added`, `added_by`) VALUES
(1, 'Group1', '2025-02-08 21:45:15', ''),
(2, 'Group2', '2025-02-08 21:47:02', 'guest'),
(3, 'Group3', '2025-02-08 21:49:13', 'guest');

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
(25, 8, '2025-01-01', 6000.00, 348.00, 50.00, 50.00, 5552.00, 810.00, 400.00, 600.00, 400.00, 8210.00, 8210.00, 631.54, '2025-01-26 18:39:53', 'abe', NULL, ''),
(26, 9, '2025-01-01', 7500.00, 435.00, 50.00, 50.00, 6965.00, 1012.50, 400.00, 750.00, 400.00, 10062.50, 0.00, 774.04, '2025-01-26 18:45:30', 'abe', '2025-02-10 11:30:35', ''),
(27, 11, '2025-03-02', 6500.00, 377.00, 50.00, 50.00, 6023.00, 877.50, 400.00, 650.00, 400.00, 8827.50, 8827.50, 679.04, '2025-03-02 11:01:08', 'abe', NULL, ''),
(30, 10, '2025-08-01', 10000.00, 580.00, 50.00, 50.00, 9320.00, 1350.00, 400.00, 1000.00, 400.00, 13150.00, 13150.00, 1011.54, '2025-08-06 09:49:10', 'abe', NULL, ''),
(33, 13, '2025-08-01', 7500.00, 435.00, 50.00, 50.00, 6965.00, 1012.50, 400.00, 750.00, 400.00, 10062.50, 0.00, 774.04, '2025-08-10 18:45:55', 'abe', '2025-08-10 18:46:47', '');

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

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`custno`, `notes`, `date_added`, `added_by`) VALUES
(8, '[LOAN ADDED] ; [loan_amount] 6000', '2025-01-26 18:39:53', 'abe'),
(9, '[LOAN ADDED] ; [loan_amount] 7500', '2025-01-26 18:45:30', 'abe'),
(11, '[CUSTOMER CREATED] [firstname] Mae; [middlename] Mai; [surname] Bartosa; [suffix] ; [address] Manila City; [mobileno] 069345345645', '2025-02-08 21:17:36', 'guest'),
(12, '[CUSTOMER CREATED] [firstname] Ali; [middlename] ; [surname] Gee; [suffix] ; [address] Hamburg; [mobileno] 213124555', '2025-02-08 21:20:34', 'guest'),
(13, '[CUSTOMER CREATED] [firstname] Gere; [middlename] ; [surname] asdf; [suffix] ; [address] Davao; [mobileno] 35123213', '2025-02-08 21:22:11', 'guest'),
(14, '[CUSTOMER CREATED] [firstname] Michel; [middlename] ; [surname] Gambu; [suffix] ; [address] Palawan; [mobileno] 1234567', '2025-02-08 21:23:47', 'guest'),
(14, '[CUSTOMER UPDATED] firstname: Michel; [middlename] ; [surname] Gambu; [suffix] ; [address] Palawan; [mobileno] 1234567', '2025-02-08 22:18:38', 'guest'),
(14, '[CUSTOMER UPDATED] firstname: Michel; [middlename] ; [surname] Gambu; [suffix] ; [address] Palawan; [mobileno] 1234567', '2025-02-08 22:18:55', 'guest'),
(14, '[CUSTOMER UPDATED] firstname: Michel; [middlename] ; [surname] Gambu; [suffix] ; [address] Palawan; [mobileno] 1234567', '2025-02-08 22:20:00', 'guest'),
(15, '[CUSTOMER CREATED] [firstname] Ella; [middlename] ; [surname] Grem; [suffix] ; [address] Cebu; [mobileno] 2131244', '2025-02-08 22:20:35', 'guest'),
(9, '[PAYMENT ADDED] [loan_record_row_id] 26; [amount] 774.04; [payment_date] 2025-02-10', '2025-02-10 11:18:33', 'abe'),
(9, '[PAYMENT ADDED] [loan_record_row_id] 26; [amount] 3870.20; [payment_date] 2025-02-10', '2025-02-10 11:24:35', 'abe'),
(9, '[COLLECTION ADDED] [interest] 155.76461538462; [savings] 115.38230769231; [LRF] 61.539230769231; [damayan] 61.539230769231', '2025-02-10 11:24:35', 'abe'),
(9, '[PAYMENT ADDED] [loan_record_row_id] 26; [amount] 5418.26; [payment_date] 2025-02-10', '2025-02-10 11:30:35', 'abe'),
(9, '[COLLECTION ADDED] [interest] 233.64461538462; [savings] 173.07230769231; [LRF] 92.309230769231; [damayan] 92.309230769231', '2025-02-10 11:30:35', 'abe'),
(11, '[LOAN ADDED] ; [loan_amount] 6500', '2025-03-02 11:01:08', 'abe'),
(10, '[LOAN ADDED] ; [loan_amount] 10000', '2025-08-06 08:55:03', 'abe'),
(10, '[LOAN ADDED] ; [loan_amount] 10000', '2025-08-06 09:46:18', 'abe'),
(10, '[LOAN ADDED] ; [loan_amount] 10000', '2025-08-06 09:49:10', 'abe'),
(13, '[LOAN ADDED] ; [loan_amount] 7500', '2025-08-10 18:01:06', 'abe'),
(13, '[PAYMENT ADDED] [loan_record_row_id] 31; [amount] 774.04; [payment_date] 2025-08-10', '2025-08-10 18:05:53', 'abe'),
(13, '[COLLECTION ADDED] [interest] 311.52461538462; [savings] 230.76230769231; [LRF] 123.07923076923; [damayan] 123.07923076923', '2025-08-10 18:05:53', 'abe'),
(13, '[PAYMENT ADDED] [loan_record_row_id] 31; [amount] 3870.20; [payment_date] 2025-08-11', '2025-08-10 18:07:16', 'abe'),
(13, '[COLLECTION ADDED] [interest] 389.40461538462; [savings] 288.45230769231; [LRF] 153.84923076923; [damayan] 153.84923076923', '2025-08-10 18:07:16', 'abe'),
(13, '[PAYMENT ADDED] [loan_record_row_id] 31; [amount] 5418.26; [payment_date] 2025-08-12', '2025-08-10 18:11:15', 'abe'),
(13, '[COLLECTION ADDED] [interest] 467.28461538462; [savings] 346.14230769231; [LRF] 184.61923076923; [damayan] 184.61923076923', '2025-08-10 18:11:15', 'abe'),
(13, '[LOAN ADDED] ; [loan_amount] 7500', '2025-08-10 18:34:35', 'abe'),
(13, '[PAYMENT ADDED] [loan_record_row_id] 32; [amount] 774.04; [payment_date] 2025-08-10', '2025-08-10 18:35:00', 'abe'),
(13, '[COLLECTION ADDED] [interest] 545.16461538462; [savings] 403.83230769231; [LRF] 215.38923076923; [damayan] 215.38923076923', '2025-08-10 18:35:00', 'abe'),
(13, '[PAYMENT ADDED] [loan_record_row_id] 32; [amount] 3870.20; [payment_date] 2025-08-11', '2025-08-10 18:35:25', 'abe'),
(13, '[COLLECTION ADDED] [interest] 623.04461538462; [savings] 461.52230769231; [LRF] 246.15923076923; [damayan] 246.15923076923', '2025-08-10 18:35:25', 'abe'),
(13, '[PAYMENT ADDED] [loan_record_row_id] 32; [amount] 5418.26; [payment_date] 2025-08-10', '2025-08-10 18:36:14', 'abe'),
(13, '[COLLECTION ADDED] [interest] 700.92461538462; [savings] 519.21230769231; [LRF] 276.92923076923; [damayan] 276.92923076923', '2025-08-10 18:36:14', 'abe'),
(13, '[LOAN ADDED] ; [loan_amount] 7500', '2025-08-10 18:45:55', 'abe'),
(13, '[PAYMENT ADDED] [loan_record_row_id] 33; [amount] 774.04; [payment_date] 2025-08-10', '2025-08-10 18:46:27', 'abe'),
(13, '[COLLECTION ADDED] [interest] 778.80461538462; [savings] 576.90230769231; [LRF] 307.69923076923; [damayan] 307.69923076923', '2025-08-10 18:46:27', 'abe'),
(13, '[PAYMENT ADDED] [loan_record_row_id] 33; [amount] 3870.20; [payment_date] 2025-08-11', '2025-08-10 18:46:37', 'abe'),
(13, '[COLLECTION ADDED] [interest] 856.68461538462; [savings] 634.59230769231; [LRF] 338.46923076923; [damayan] 338.46923076923', '2025-08-10 18:46:37', 'abe'),
(13, '[PAYMENT ADDED] [loan_record_row_id] 33; [amount] 5418.26; [payment_date] 2025-08-13', '2025-08-10 18:46:47', 'abe'),
(13, '[COLLECTION ADDED] [interest] 934.56461538462; [savings] 692.28230769231; [LRF] 369.23923076923; [damayan] 369.23923076923', '2025-08-10 18:46:47', 'abe');

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
(18, 774.04, '2025-02-10', '2025-02-10 11:18:33', 'abe', 26),
(19, 3870.20, '2025-02-10', '2025-02-10 11:24:35', 'abe', 26),
(20, 5418.26, '2025-02-10', '2025-02-10 11:30:35', 'abe', 26),
(27, 774.04, '2025-08-10', '2025-08-10 18:46:27', 'abe', 33),
(28, 3870.20, '2025-08-11', '2025-08-10 18:46:37', 'abe', 33),
(29, 5418.26, '2025-08-13', '2025-08-10 18:46:47', 'abe', 33);

-- --------------------------------------------------------

--
-- Table structure for table `scheduled_payment`
--

CREATE TABLE `scheduled_payment` (
  `row_id` int(11) NOT NULL,
  `weekno` int(11) NOT NULL,
  `amount` decimal(15,2) NOT NULL COMMENT 'amount paid for this scheduled payment',
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

INSERT INTO `scheduled_payment` (`row_id`, `weekno`, `amount`, `date_paid`, `scheduled_date`, `added_by`, `is_paid`, `remaining_debt`, `loan_record_row_id`) VALUES
(303, 1, 0.00, NULL, '2025-01-08', NULL, b'00', 631.54, 25),
(304, 2, 0.00, NULL, '2025-01-15', NULL, b'00', 631.54, 25),
(305, 3, 0.00, NULL, '2025-01-22', NULL, b'00', 631.54, 25),
(306, 4, 0.00, NULL, '2025-01-29', NULL, b'00', 631.54, 25),
(307, 5, 0.00, NULL, '2025-02-05', NULL, b'00', 631.54, 25),
(308, 6, 0.00, NULL, '2025-02-12', NULL, b'00', 631.54, 25),
(309, 7, 0.00, NULL, '2025-02-19', NULL, b'00', 631.54, 25),
(310, 8, 0.00, NULL, '2025-02-26', NULL, b'00', 631.54, 25),
(311, 9, 0.00, NULL, '2025-03-05', NULL, b'00', 631.54, 25),
(312, 10, 0.00, NULL, '2025-03-12', NULL, b'00', 631.54, 25),
(313, 11, 0.00, NULL, '2025-03-19', NULL, b'00', 631.54, 25),
(314, 12, 0.00, NULL, '2025-03-26', NULL, b'00', 631.54, 25),
(315, 13, 0.00, NULL, '2025-04-02', NULL, b'00', 631.54, 25),
(316, 1, 774.04, '2025-02-10 00:00:00', '2025-01-08', 'abe', b'01', 0.00, 26),
(317, 2, 774.04, '2025-02-10 00:00:00', '2025-01-15', 'abe', b'01', 0.00, 26),
(318, 3, 774.04, '2025-02-10 00:00:00', '2025-01-22', 'abe', b'01', 0.00, 26),
(319, 4, 774.04, '2025-02-10 00:00:00', '2025-01-29', 'abe', b'01', 0.00, 26),
(320, 5, 774.04, '2025-02-10 00:00:00', '2025-02-05', 'abe', b'01', 0.00, 26),
(321, 6, 774.04, '2025-02-10 00:00:00', '2025-02-12', 'abe', b'01', 0.00, 26),
(322, 7, 774.04, '2025-02-10 00:00:00', '2025-02-19', 'abe', b'01', 0.00, 26),
(323, 8, 774.04, '2025-02-10 00:00:00', '2025-02-26', 'abe', b'01', 0.00, 26),
(324, 9, 774.04, '2025-02-10 00:00:00', '2025-03-05', 'abe', b'01', 0.00, 26),
(325, 10, 774.04, '2025-02-10 00:00:00', '2025-03-12', 'abe', b'01', 0.00, 26),
(326, 11, 774.04, '2025-02-10 00:00:00', '2025-03-19', 'abe', b'01', 0.00, 26),
(327, 12, 774.04, '2025-02-10 00:00:00', '2025-03-26', 'abe', b'01', 0.00, 26),
(328, 13, 0.00, NULL, '2025-04-02', NULL, b'00', 0.02, 26),
(329, 1, 0.00, NULL, '2025-03-09', NULL, b'00', 679.04, 27),
(330, 2, 0.00, NULL, '2025-03-16', NULL, b'00', 679.04, 27),
(331, 3, 0.00, NULL, '2025-03-23', NULL, b'00', 679.04, 27),
(332, 4, 0.00, NULL, '2025-03-30', NULL, b'00', 679.04, 27),
(333, 5, 0.00, NULL, '2025-04-06', NULL, b'00', 679.04, 27),
(334, 6, 0.00, NULL, '2025-04-13', NULL, b'00', 679.04, 27),
(335, 7, 0.00, NULL, '2025-04-20', NULL, b'00', 679.04, 27),
(336, 8, 0.00, NULL, '2025-04-27', NULL, b'00', 679.04, 27),
(337, 9, 0.00, NULL, '2025-05-04', NULL, b'00', 679.04, 27),
(338, 10, 0.00, NULL, '2025-05-11', NULL, b'00', 679.04, 27),
(339, 11, 0.00, NULL, '2025-05-18', NULL, b'00', 679.04, 27),
(340, 12, 0.00, NULL, '2025-05-25', NULL, b'00', 679.04, 27),
(341, 13, 0.00, NULL, '2025-06-01', NULL, b'00', 679.04, 27),
(368, 1, 0.00, NULL, '2025-08-04', NULL, b'00', 1011.54, 30),
(369, 2, 0.00, NULL, '2025-08-11', NULL, b'00', 1011.54, 30),
(370, 3, 0.00, NULL, '2025-08-18', NULL, b'00', 1011.54, 30),
(371, 4, 0.00, NULL, '2025-08-25', NULL, b'00', 1011.54, 30),
(372, 5, 0.00, NULL, '2025-09-01', NULL, b'00', 1011.54, 30),
(373, 6, 0.00, NULL, '2025-09-08', NULL, b'00', 1011.54, 30),
(374, 7, 0.00, NULL, '2025-09-15', NULL, b'00', 1011.54, 30),
(375, 8, 0.00, NULL, '2025-09-22', NULL, b'00', 1011.54, 30),
(376, 9, 0.00, NULL, '2025-09-29', NULL, b'00', 1011.54, 30),
(377, 10, 0.00, NULL, '2025-10-06', NULL, b'00', 1011.54, 30),
(378, 11, 0.00, NULL, '2025-10-13', NULL, b'00', 1011.54, 30),
(379, 12, 0.00, NULL, '2025-10-20', NULL, b'00', 1011.54, 30),
(380, 13, 0.00, NULL, '2025-10-27', NULL, b'00', 1011.54, 30),
(407, 1, 774.04, '2025-08-10 00:00:00', '2025-08-10', 'abe', b'01', 0.00, 33),
(408, 2, 774.04, '2025-08-11 00:00:00', '2025-08-17', 'abe', b'01', 0.00, 33),
(409, 3, 774.04, '2025-08-11 00:00:00', '2025-08-24', 'abe', b'01', 0.00, 33),
(410, 4, 774.04, '2025-08-11 00:00:00', '2025-08-31', 'abe', b'01', 0.00, 33),
(411, 5, 774.04, '2025-08-11 00:00:00', '2025-09-07', 'abe', b'01', 0.00, 33),
(412, 6, 774.04, '2025-08-11 00:00:00', '2025-09-14', 'abe', b'01', 0.00, 33),
(413, 7, 774.04, '2025-08-13 00:00:00', '2025-09-21', 'abe', b'01', 0.00, 33),
(414, 8, 774.04, '2025-08-13 00:00:00', '2025-09-28', 'abe', b'01', 0.00, 33),
(415, 9, 774.04, '2025-08-13 00:00:00', '2025-10-05', 'abe', b'01', 0.00, 33),
(416, 10, 774.04, '2025-08-13 00:00:00', '2025-10-12', 'abe', b'01', 0.00, 33),
(417, 11, 774.04, '2025-08-13 00:00:00', '2025-10-19', 'abe', b'01', 0.00, 33),
(418, 12, 774.04, '2025-08-13 00:00:00', '2025-10-26', 'abe', b'01', 0.00, 33),
(419, 13, 0.00, NULL, '2025-11-02', NULL, b'00', 0.02, 33);

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
  MODIFY `custno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `groupx`
--
ALTER TABLE `groupx`
  MODIFY `groupno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `loan_record`
--
ALTER TABLE `loan_record`
  MODIFY `row_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `row_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `scheduled_payment`
--
ALTER TABLE `scheduled_payment`
  MODIFY `row_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=420;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
