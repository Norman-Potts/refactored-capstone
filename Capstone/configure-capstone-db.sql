-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 07, 2021 at 02:25 AM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 8.0.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;



--
-- Database: `capstone`
--
-- --------------------------------------------------------

--
-- Table structure for table `Chatboxmessages`
--

CREATE TABLE `Chatboxmessages` (
  `CreatedDateAndTime` datetime NOT NULL,
  `Message` blob NOT NULL,
  `employeeID` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- --------------------------------------------------------

--
-- Table structure for table `Employees`
--

CREATE TABLE `Employees` (
  `Username` text NOT NULL,
  `password` text NOT NULL,
  `employeeID` int(255) NOT NULL,
  `Supervisor` tinyint(1) NOT NULL,
  `Instructor` tinyint(1) NOT NULL,
  `Lifeguard` tinyint(1) NOT NULL,
  `Headguard` tinyint(1) NOT NULL,
  `Firstname` text NOT NULL,
  `Lastname` text NOT NULL,
  `NewNotfications` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `Availability` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Inserting Two Defualt Employee Accounts. 
--

INSERT INTO `Employees` (`Username`, `password`, `employeeID`, `Supervisor`, `Instructor`, `Lifeguard`, `Headguard`, `Firstname`, `Lastname`, `NewNotfications`, `Availability`) VALUES
('Norman.Potts', '63fPDotkhXh', 1, 1, 0, 0, 0, 'Norman', 'Potts', NULL, '{\"Mondays\":{\"A1\":true,\"A2\":true,\"A3\":true,\"A4\":true,\"A5\":true,\"A6\":true,\"A7\":true,\"ANOT\":false,\"Anytime\":true},\"Tuesday\":{\"A1\":true,\"A2\":true,\"A3\":true,\"A4\":true,\"A5\":true,\"A6\":true,\"A7\":true,\"ANOT\":false,\"Anytime\":true},\"Wednesday\":{\"A1\":true,\"A2\":true,\"A3\":true,\"A4\":true,\"A5\":true,\"A6\":true,\"A7\":true,\"ANOT\":false,\"Anytime\":true},\"Thrusday\":{\"A1\":false,\"A2\":false,\"A3\":false,\"A4\":false,\"A5\":false,\"A6\":false,\"A7\":false,\"ANOT\":true,\"Anytime\":false},\"Friday\":{\"A1\":false,\"A2\":false,\"A3\":false,\"A4\":false,\"A5\":false,\"A6\":false,\"A7\":false,\"ANOT\":true,\"Anytime\":false},\"Saturday\":{\"A1\":true,\"A2\":true,\"A3\":true,\"A4\":true,\"A5\":\" \",\"A6\":\" \",\"A7\":\" \",\"ANOT\":false,\"Anytime\":true},\"Sundays\":{\"A1\":false,\"A2\":false,\"A3\":false,\"A4\":false,\"A5\":false,\"A6\":false,\"A7\":false,\"ANOT\":true,\"Anytime\":false},\"Notes\":\"Yeet Can work anytime \"}'),
('John.Doe', 'ExamplePass', 2, 0, 0, 1, 0, 'John', 'Doe', NULL, 'Nothing yet');

-- --------------------------------------------------------

--
-- Table structure for table `Notifications`
--

CREATE TABLE `Notifications` (
  `employeeID` int(255) NOT NULL,
  `type` int(255) NOT NULL,
  `message` blob NOT NULL,
  `readOrUnread` tinyint(1) NOT NULL,
  `CreatedDateAndTime` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `Notifications` ADD `notificationID` INT NOT NULL AUTO_INCREMENT AFTER `CreatedDateAndTime`, ADD UNIQUE (`notificationID`);


--
-- Table structure for table `Shifts`
--

CREATE TABLE `Shifts` (
  `ShiftID` int(255) NOT NULL,
  `DefaultOwnerEmployeeID` int(255) NOT NULL,
  `CurrentOwnerEmployeeID` int(255) NOT NULL,
  `date` date NOT NULL,
  `startTime` mediumtext NOT NULL,
  `endTime` mediumtext NOT NULL,
  `Position` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


--
-- Table structure for table `Subslips`
--

CREATE TABLE `Subslips` (
  `CreatorID` int(255) NOT NULL,
  `TakerID` int(255) DEFAULT NULL,
  `ShiftDate` date NOT NULL,
  `startTime` varchar(255) NOT NULL,
  `endTime` varchar(255) NOT NULL,
  `Position` int(5) NOT NULL,
  `Reason` text NOT NULL,
  `TakenTrueorFalse` tinyint(1) NOT NULL,
  `TakenDateAndTime` text DEFAULT NULL,
  `ShiftID` int(255) DEFAULT NULL,
  `completed` tinyint(1) NOT NULL,
  `subslipID` int(11) NOT NULL,
  `CreatedDateAndTime` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Employees`
--
ALTER TABLE `Employees`
  ADD UNIQUE KEY `employeeID` (`employeeID`);

--
-- Indexes for table `Shifts`
--
ALTER TABLE `Shifts`
  ADD PRIMARY KEY (`ShiftID`),
  ADD UNIQUE KEY `ShiftID` (`ShiftID`);

--
-- Indexes for table `Subslips`
--
ALTER TABLE `Subslips`
  ADD PRIMARY KEY (`subslipID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Employees`
--
ALTER TABLE `Employees`
  MODIFY `employeeID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `Shifts`
--
ALTER TABLE `Shifts`
  MODIFY `ShiftID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;



