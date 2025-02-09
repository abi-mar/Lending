-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 08, 2025 at 03:29 PM
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
USE `lending`;

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
-- Indexes for dumped tables
--

--
-- Indexes for table `account_officer`
--
ALTER TABLE `account_officer`
  ADD PRIMARY KEY (`row_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account_officer`
--
ALTER TABLE `account_officer`
  MODIFY `row_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;


-- GROUPX table
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
-- Indexes for dumped tables
--

--
-- Indexes for table `groupx`
--
ALTER TABLE `groupx`
  ADD PRIMARY KEY (`groupno`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `groupx`
--
ALTER TABLE `groupx`
  MODIFY `groupno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
COMMIT;

-- ALTER customer table

ALTER TABLE `customer`
  ADD `groupno` int(11) DEFAULT NULL COMMENT 'client group where it belongs. Can be null if client does not belong to a group.',
  ADD `account_officer_id` int(11) NOT NULL COMMENT 'account_officer_row_id';

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
